<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\FreeMarket;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $places = Place::where('user_id', $user->id)->latest()->get();
        $freeItems = FreeMarket::where('user_id', $user->id)->latest()->get();

        return view('bkc.mypage', compact('places', 'freeItems'));
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
            'description' => ['required', 'string'],
            'type' => ['required', 'string', 'in:drive,karaoke,izakaya'],
            'address' => ['required', 'string'],
            'phone' => ['nullable', 'string'],
            'website' => ['nullable', 'url'],
        ]);

        Place::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'address' => $request->address,
            'phone' => $request->phone,
            'website' => $request->website,
            'user_id' => Auth::id(),
        ]);

        return redirect('/my')->with('success', '掲載を作成しました。');
    }

    /**
     * 掲載編集（フリマ以外）フォーム表示
     */
    public function editPlace(Place $place): View
    {
        $this->authorize('update', $place);
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
        ]);

        $place->update($validated);

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
        ]);

        FreeMarket::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'category' => $request->category,
            'user_id' => Auth::id(),
        ]);

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
        ]);

        $free->update($request->all());

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
        $places = Place::where('user_id', Auth::id())->latest()->get();
        return view('my.places.index', compact('places'));
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
        // データベースから実際のデータを取得
        $freeItems = FreeMarket::where('user_id', Auth::id())->latest()->get();

        return view('my.free.index', compact('freeItems'));
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
}
