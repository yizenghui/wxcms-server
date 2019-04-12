<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Http\Resources\ArticleResource;
use Intervention\Image\ImageManagerStatic as Image;

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


    public function poster(Request $request){
        
        $img = Image::canvas(600, 600, '#ffffff');

        $data = Article::orderBy('id','desc')->simplePaginate(10);

        $img->text('美文荐读：',50,30, function($font) {
            $font->file(storage_path('font.ttf'));
            $font->size(36);
            // $font->color('#fdf6e3');
            $font->color('#000000');
        //        $font->align('center');
            $font->valign('top');
        //        $font->angle(90);
        });

        foreach($data as $k=>$article){
            $img->text(str_limit('* '.$article->title,44),50,80+40*$k, function($font) {
                $font->file(storage_path('font.ttf'));
                $font->size(24);
                // $font->color('#fdf6e3');
                $font->color('#000000');
            //        $font->align('center');
                $font->valign('top');
            //        $font->angle(90);
            });
        }

        $img2 = Image::make(storage_path('wxcms.png'));
        $img2->resize(100, 100);
        $img->insert($img2, 'bottom-right',20,20);

        return  $img->response();
    }

}
