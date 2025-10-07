<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\PlaceImage;
use App\Models\Drive;
use App\Models\FreeMarket;
use App\Models\FreeMarketMessage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MyController extends Controller
{
    use AuthorizesRequests;
    /**
     * マイページ表示
     */
    public function index(): View
    {
        $user = Auth::user();
        $places = Place::where('user_id', $user->id)->with(['images', 'drive'])->latest()->get();
        $freeItems = FreeMarket::where('user_id', $user->id)
            ->withCount('messages')
            ->latest()
            ->take(5)
            ->get();

        // やり取り中のDMを取得（自分が出品者の商品に対するメッセージ）
        $activeConversations = FreeMarketMessage::whereIn('free_market_id', function($query) {
                $query->select('id')
                    ->from('free_markets')
                    ->where('user_id', Auth::id());
            })
            ->with(['sender', 'receiver', 'freeMarket'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) {
                // 相手（購入希望者）でグループ化
                return ($message->sender_id == Auth::id() ? $message->receiver_id : $message->sender_id) . '-' . $message->free_market_id;
            })
            ->map(function($messages) {
                $firstMessage = $messages->first();
                return [
                    'user' => $firstMessage->sender_id == Auth::id() 
                        ? $firstMessage->receiver 
                        : $firstMessage->sender,
                    'free_market' => $firstMessage->freeMarket,
                    'last_message' => $firstMessage,
                    'unread_count' => $messages->where('receiver_id', Auth::id())->where('is_read', false)->count(),
                    'total_count' => $messages->count(),
                ];
            })
            ->take(5);

        return view('bkc.mypage', compact('places', 'freeItems', 'activeConversations'));
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * 掲載作成（フリマ以外）フォーム表示
     */
    public function createPlace(): View
    {
        return view('my.places.create');
    }

    /**
     * 掲載保存（フリマ以外）
     */
    public function storePlace(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:drive,karaoke,izakaya'],
            'address' => ['nullable', 'string'],
            'tel' => ['nullable', 'string'],
            'url' => ['nullable', 'url'],
            'campus_time_min' => ['nullable', 'integer', 'min:0'],
            'score' => ['nullable', 'integer', 'min:0', 'max:5'],
            'reason' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $place = Place::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'address' => $request->address,
            'tel' => $request->tel,
            'url' => $request->url,
            'campus_time_min' => $request->campus_time_min,
            'score' => $request->score,
            'reason' => $request->reason,
            'user_id' => Auth::id(),
            'is_active' => true,
        ]);

        // ドライブの場合、Driveテーブルにもレコード作成
        if ($request->type === 'drive' && $request->category_id) {
            Drive::create([
                'place_id' => $place->id,
                'category_id' => $request->category_id,
            ]);
        }

        // 画像アップロード処理
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = $image->storeAs('place-images', $filename, 's3');
            $imageUrl = Storage::disk('s3')->url($path);

            PlaceImage::create([
                'place_id' => $place->id,
                'path' => $imageUrl,
                'alt_text' => $request->name,
                'sort_order' => 1,
            ]);
        }

        return redirect('/my')->with('success', '掲載を作成しました。');
    }

    /**
     * 掲載編集（フリマ以外）フォーム表示
     */
    public function editPlace(Place $place): View
    {
        $this->authorize('update', $place);
        $place->load(['images', 'drive']);
        return view('my.places.edit', compact('place'));
    }

    /**
     * 掲載更新（フリマ以外）
     */
    public function updatePlace(Request $request, Place $place): RedirectResponse
    {
        $this->authorize('update', $place);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'tel' => 'nullable|string|max:20',
            'campus_time_min' => 'nullable|integer|min:0',
            'url' => 'nullable|url|max:255',
            'type' => 'required|in:drive,karaoke,izakaya',
            'description' => 'nullable|string',
            'score' => 'nullable|integer|min:0|max:5',
            'reason' => 'nullable|string',
            'category_id' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $place->update($validated);

        // ドライブの場合、Driveテーブルも更新
        if ($request->type === 'drive' && $request->category_id) {
            if ($place->drive) {
                $place->drive->update(['category_id' => $request->category_id]);
            } else {
                Drive::create([
                    'place_id' => $place->id,
                    'category_id' => $request->category_id,
                ]);
            }
        }

        // 画像アップロード処理
        if ($request->hasFile('image')) {
            // 既存の画像を削除
            $existingImages = $place->images;
            if ($existingImages->count() > 0) {
                foreach ($existingImages as $existingImage) {
                    // S3から画像を削除
                    $oldPath = parse_url($existingImage->path, PHP_URL_PATH);
                    if ($oldPath) {
                        Storage::disk('s3')->delete(ltrim($oldPath, '/'));
                    }
                    $existingImage->delete();
                }
            }

            // 新しい画像をアップロード
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = $image->storeAs('place-images', $filename, 's3');
            $imageUrl = Storage::disk('s3')->url($path);

            PlaceImage::create([
                'place_id' => $place->id,
                'path' => $imageUrl,
                'alt_text' => $request->name,
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('my.places.index')
            ->with('success', '場所情報を更新しました。');
    }

    /**
     * 掲載削除（フリマ以外）
     */
    public function destroyPlace(Place $place): RedirectResponse
    {
        $this->authorize('delete', $place);
        $place->delete();

        return redirect('/my')->with('success', '掲載を削除しました。');
    }

    /**
     * 掲載作成（フリマ）フォーム表示
     */
    public function createFree(): View
    {
        return view('my.free.create');
    }

    /**
     * 作成保存（フリマ）
     */
    public function storeFree(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'condition' => ['required', 'string', 'in:new,like_new,good,fair'],
            'category' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'category' => $request->category,
            'user_id' => Auth::id(),
        ];

        // 画像アップロード処理
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = $image->storeAs('free-market-images', $filename, 's3');
            $data['image_url'] = Storage::disk('s3')->url($path);
        }

        FreeMarket::create($data);

        return redirect('/my')->with('success', '出品を作成しました。');
    }

    /**
     * 掲載編集（フリマ）フォーム表示
     */
    public function editFree($id): View
    {
        $free = FreeMarket::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('my.free.edit', compact('free'));
    }

    /**
     * 掲載更新（フリマ）
     */
    public function updateFree(Request $request, FreeMarket $free): RedirectResponse
    {
        $this->authorize('update', $free);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'condition' => ['required', 'string', 'in:new,like_new,good,fair'],
            'category' => ['required', 'string'],
            'status' => ['nullable', 'string', 'in:active,sold,cancelled'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data = $request->only(['title', 'description', 'price', 'condition', 'category', 'status']);

        // 画像アップロード処理
        if ($request->hasFile('image')) {
            // 古い画像を削除（オプション）
            if ($free->image_url) {
                $oldPath = parse_url($free->image_url, PHP_URL_PATH);
                if ($oldPath) {
                    Storage::disk('s3')->delete(ltrim($oldPath, '/'));
                }
            }

            // 新しい画像をアップロード
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = $image->storeAs('free-market-images', $filename, 's3');
            $data['image_url'] = Storage::disk('s3')->url($path);
        }

        $free->update($data);

        return redirect('/my')->with('success', '出品を更新しました。');
    }

    /**
     * 掲載削除（フリマ）
     */
    public function destroyFree(Request $request, $id): RedirectResponse
    {
        try {
            $free = FreeMarket::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $free->delete();
            return redirect('/my')->with('success', '出品を削除しました。');
        } catch (\Exception $e) {
            return redirect('/my')->with('error', '削除処理でエラーが発生しました。');
        }
    }

    /**
     * 掲載一覧（フリマ以外）
     */
    public function places(): View
    {
        $places = Place::where('user_id', Auth::id())->with(['images', 'drive'])->latest()->get();
        
        // やり取り中のDMを取得（フリマ関連のみ）
        $activeConversations = FreeMarketMessage::whereIn('free_market_id', function($query) {
                $query->select('id')
                    ->from('free_markets')
                    ->where('user_id', Auth::id());
            })
            ->with(['sender', 'receiver', 'freeMarket'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) {
                return ($message->sender_id == Auth::id() ? $message->receiver_id : $message->sender_id) . '-' . $message->free_market_id;
            })
            ->map(function($messages) {
                $firstMessage = $messages->first();
                return [
                    'user' => $firstMessage->sender_id == Auth::id() 
                        ? $firstMessage->receiver 
                        : $firstMessage->sender,
                    'free_market' => $firstMessage->freeMarket,
                    'last_message' => $firstMessage,
                    'unread_count' => $messages->where('receiver_id', Auth::id())->where('is_read', false)->count(),
                    'total_count' => $messages->count(),
                ];
            })
            ->take(5);
        
        return view('my.places.index', compact('places', 'activeConversations'));
    }


    /**
     * 詳細表示（フリマ以外）
     */
    public function showPlace(Place $place): View
    {
        $this->authorize('view', $place);
        return view('my.places.show', compact('place'));
    }

    /**
     * 掲載一覧（フリマ）
     */
    public function free(): View
    {
        // データベースから実際のデータを取得（メッセージ数も含む）
        $freeItems = FreeMarket::where('user_id', Auth::id())
            ->withCount('messages')
            ->latest()
            ->get();

        // やり取り中のDMを取得
        $activeConversations = FreeMarketMessage::whereIn('free_market_id', function($query) {
                $query->select('id')
                    ->from('free_markets')
                    ->where('user_id', Auth::id());
            })
            ->with(['sender', 'receiver', 'freeMarket'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) {
                return ($message->sender_id == Auth::id() ? $message->receiver_id : $message->sender_id) . '-' . $message->free_market_id;
            })
            ->map(function($messages) {
                $firstMessage = $messages->first();
                return [
                    'user' => $firstMessage->sender_id == Auth::id() 
                        ? $firstMessage->receiver 
                        : $firstMessage->sender,
                    'free_market' => $firstMessage->freeMarket,
                    'last_message' => $firstMessage,
                    'unread_count' => $messages->where('receiver_id', Auth::id())->where('is_read', false)->count(),
                    'total_count' => $messages->count(),
                ];
            });

        return view('my.free.index', compact('freeItems', 'activeConversations'));
    }

    /**
     * 詳細表示（フリマ）
     */
    public function showFree($id): View
    {
        $free = FreeMarket::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('user')
            ->firstOrFail();

        return view('my.free.show', compact('free'));
    }

    /**
     * フリマ商品のメッセージ一覧
     */
    public function freeMessages($id): View
    {
        $free = FreeMarket::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // この商品に関するメッセージを、購入希望者ごとにグループ化
        $conversations = FreeMarketMessage::where('free_market_id', $id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) {
                // 自分が出品者なので、相手（購入希望者）でグループ化
                return $message->sender_id == Auth::id() ? $message->receiver_id : $message->sender_id;
            })
            ->map(function($messages) {
                return [
                    'user' => $messages->first()->sender_id == Auth::id() 
                        ? $messages->first()->receiver 
                        : $messages->first()->sender,
                    'last_message' => $messages->first(),
                    'unread_count' => $messages->where('receiver_id', Auth::id())->where('is_read', false)->count(),
                    'total_count' => $messages->count(),
                ];
            });

        return view('my.free.messages', compact('free', 'conversations'));
    }

    /**
     * 全てのDM一覧表示
     */
    public function allMessages(): View
    {
        // すべての商品に対するDMを取得
        $conversations = FreeMarketMessage::whereIn('free_market_id', function($query) {
                $query->select('id')
                    ->from('free_markets')
                    ->where('user_id', Auth::id());
            })
            ->with(['sender', 'receiver', 'freeMarket'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) {
                return ($message->sender_id == Auth::id() ? $message->receiver_id : $message->sender_id) . '-' . $message->free_market_id;
            })
            ->map(function($messages) {
                $firstMessage = $messages->first();
                return [
                    'user' => $firstMessage->sender_id == Auth::id() 
                        ? $firstMessage->receiver 
                        : $firstMessage->sender,
                    'free_market' => $firstMessage->freeMarket,
                    'last_message' => $firstMessage,
                    'unread_count' => $messages->where('receiver_id', Auth::id())->where('is_read', false)->count(),
                    'total_count' => $messages->count(),
                ];
            })
            ->sortByDesc(function($conversation) {
                return $conversation['last_message']->created_at;
            });

        return view('my.messages', compact('conversations'));
    }
}
