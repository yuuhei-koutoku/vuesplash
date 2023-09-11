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
}
