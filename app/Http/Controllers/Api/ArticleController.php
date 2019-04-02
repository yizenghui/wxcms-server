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
        $topic_id = $request->get('topic');
        if( $topic_id ){
            $data = Article::where('topic_id','=',$topic_id)->orderBy('id','desc')->simplePaginate(10);
        }else{
            $data = Article::orderBy('id','desc')->simplePaginate(10);
        }
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
        $article->author;
        $article->userlikearticle = $request->user()->hasLiked($article);
        return response()->json($article);
    }

    /**
     * 获取推荐文章(小程序首页用)
     */
    public function recommend(){
        $data = Article::orderBy('id','desc')->simplePaginate(10);
        $articles = ArticleResource::collection($data);
        return response()->json($articles);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function likeusers($id,Request $request)
    {
        $article = Article::findOrFail($id);
        $fans = $article->likers()->get();
        return response()->json($fans);
    }
}
