<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Aws\S3\S3Client;

class Photo extends Model
{
    use HasFactory;

    public function getImageUrls()
    {
        // AWS S3 Clientのインスタンスを作成
        $s3Client = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $bucket = env('AWS_BUCKET');

        $objects = $s3Client->listObjects([
            'Bucket' => $bucket
        ]);

        $urls = [];
        foreach ($objects['Contents'] as $object) {
            $cmd = $s3Client->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key'    => $object['Key']
            ]);

            $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');

            $urls[] = (string) $request->getUri();
        }

        return $urls;
    }
}
