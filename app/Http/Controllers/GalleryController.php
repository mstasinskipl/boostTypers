<?php

namespace App\Http\Controllers;

use App\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function showGalleries(Request $request)
    {
        $galleries = Gallery::all();
        return view('welcome', ['galleries' => $galleries]);
    }

    public function showImage($idGallery)
    {
        $gallery = Gallery::find($idGallery);
        $images = $gallery->images()->paginate(1);

        return view('image', ['images' => $images]);
    }
}
