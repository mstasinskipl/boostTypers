<?php

namespace App\Http\Controllers;

use App\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showGalleries(Request $request)
    {
        $galleries = Gallery::all();
        return view('welcome', ['galleries' => $galleries]);
    }

    /**
     * @param $idGallery
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showImage($idGallery)
    {
        $gallery = Gallery::find($idGallery);
        $images = $gallery->images()->paginate(1);

        return view('image', ['images' => $images]);
    }
}
