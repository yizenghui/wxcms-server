<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Http\Resources\AuthorResource;

class AuthorController extends Controller
{
    //
    public function index(){
        $data = Author::where('appid', '=', request()->get('appid'))->where('state','=',1)->simplePaginate(20);
        $authors = AuthorResource::collection($data);
        return response()->json($authors);
    }

    /**
     * Display the specified resource.s
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $author = Author::findOrFail($id);
        $share = $author->share;
        $author->share_title = $share?$share->oneTitle:'';
        $author->share_cover = $share?$share->oneCover:'';
        return response()->json($author);
    }

}
