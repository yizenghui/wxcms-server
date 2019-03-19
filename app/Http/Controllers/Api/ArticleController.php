<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    //
    public function index(Request $request){
        $data = Article::all();
        // dd($data);
        $articles = ArticleResource::collection($data);
        return response()->json($articles);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {

        $article = Article::findOrFail($id);
        
        return response()->json($article);
    }

    /**
     * 获取推荐文章(小程序首页用)
     */
    public function recommend(){
        $data = Article::all();
        $articles = ArticleResource::collection($data);
        return response()->json($articles);
    }
}
