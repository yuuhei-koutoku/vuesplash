<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;

class PhotoController extends Controller
{
    public function getAllImages()
    {
        $photo = new Photo();
        $images = $photo->getImageUrls();

        return response()->json($images);
    }

    public function addImage(Request $request)
    {
        // バリデーション
        $request->validate([
            'photo' => 'required|file|mimes:jpg,jpeg,png,gif'
        ]);

        // リクエストからファイルを取得する
        $file = $request->file('photo');

        // ユーザーIDを取得する
        $userId = auth()->id();

        // ユーザーIDがNULLの場合は、「ログインしてください。」と記載されたアラートを表示する
        if (!$userId) {
            return response()->json(['message' => 'You must be logged in to post photos'], 401);
        }

        // Photoモデルのインスタンスを生成し、storePhotoメソッドを実行する
        $photo = (new Photo)->storePhoto($file, $userId);

        return response()->json([
            'message' => 'Success',
            'photo' => $photo
        ]);
    }
}
