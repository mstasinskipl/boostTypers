<?php

namespace App\Http\Controllers\API;

use App\Gallery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galleries = Gallery::all();

        return response()->json(['status' => Response::HTTP_OK, 'data' => $galleries]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gallery = Gallery::find($id)->images()->paginate(1);

        return response()->json(['status' => Response::HTTP_OK, 'data' => $gallery]);
    }


}
