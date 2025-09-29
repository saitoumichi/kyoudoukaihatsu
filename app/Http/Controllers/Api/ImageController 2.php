<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    /**
     * 画像一覧取得
     */
    public function index(Place $place): JsonResponse
    {
        $images = $place->images()->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $images
        ]);
    }

    /**
     * 画像アップロード
     */
    public function upload(Request $request, Place $place): JsonResponse
    {
        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'alt_texts' => 'nullable|array',
            'alt_texts.*' => 'nullable|string|max:120',
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $index => $image) {
            // ファイル名生成
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

            // ストレージに保存
            $path = $image->storeAs('places/' . $place->id, $filename, 'public');

            // データベースに保存
            $placeImage = PlaceImage::create([
                'place_id' => $place->id,
                'path' => $path,
                'alt_text' => $request->alt_texts[$index] ?? null,
                'sort_order' => $place->images()->count() + 1,
            ]);

            $uploadedImages[] = $placeImage;
        }

        return response()->json([
            'success' => true,
            'message' => '画像をアップロードしました。',
            'data' => $uploadedImages
        ], 201);
    }

    /**
     * 画像削除
     */
    public function destroy(PlaceImage $image): JsonResponse
    {
        // ファイルをストレージから削除
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        // データベースから削除
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => '画像を削除しました。'
        ]);
    }

    /**
     * 画像の並び順更新
     */
    public function updateSort(Request $request, Place $place): JsonResponse
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:place_images,id',
            'images.*.sort_order' => 'required|integer|min:1',
        ]);

        foreach ($request->images as $imageData) {
            PlaceImage::where('id', $imageData['id'])
                     ->where('place_id', $place->id)
                     ->update(['sort_order' => $imageData['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => '並び順を更新しました。'
        ]);
    }

    /**
     * 画像の代替テキスト更新
     */
    public function updateAltText(Request $request, PlaceImage $image): JsonResponse
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:120',
        ]);

        $image->update(['alt_text' => $request->alt_text]);

        return response()->json([
            'success' => true,
            'message' => '代替テキストを更新しました。',
            'data' => $image
        ]);
    }

    /**
     * 画像詳細取得
     */
    public function show(PlaceImage $image): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $image
        ]);
    }
}
