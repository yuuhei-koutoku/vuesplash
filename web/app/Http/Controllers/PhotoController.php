<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Aws\S3\S3Client;

class PhotoController extends Controller
{
    public function getPhoto($id)
    {
        // DBからPhotoのデータを取得する
        $photo = Photo::findOrFail($id);

        // AWS S3 Clientのインスタンスを作成する
        $s3Client = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $bucket = env('AWS_BUCKET');

        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key'    => $photo->filepath
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');

        // Photoの一時的な公開URLを取得する
        $imageUrl = (string) $request->getUri();

        // PhotoのIDとURLを返す
        return ['id' => $id, 'url' => $imageUrl];
    }

    public function getAllImages()
    {
        // データベースから全てのPhotoのデータを取得
        $photos = Photo::all();

        $results = [];
        foreach ($photos as $photo) {
            // 各PhotoのURLを取得
            $photoData = $this->getPhoto($photo->id);

            // PhotoのIDとURLを配列に格納
            $results[] = $photoData;
        }

        return $results;
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

    // 「いいね」のデータを取得
    public function getLike()
    {
        $likes = Like::all();
        return response()->json($likes);
    }

    // Photoの「いいね」を追加
    public function like(Request $request)
    {
        $photo = Photo::findOrFail($request->input('image_id'));
        $like = Like::firstOrCreate([
            'user_id' => $request->input('user_id'),
            'photo_id' => $photo->id,
        ]);

        return response()->json($like);
    }

    // Photoの「いいね」を削除
    public function unlike(Request $request)
    {
        $photo = Photo::findOrFail($request->input('image_id'));
        $like = Like::where('user_id', $request->input('user_id'))->where('photo_id', $photo->id)->first();

        if ($like) {
            $like->delete();
        }

        return response()->json(['message' => 'Successfully unliked']);
    }
}
