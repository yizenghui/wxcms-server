<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Fan;

class ActionController extends Controller
{

    public function view(Request $request){
        $article = Article::findOrFail($request->get('article_id'));
        // $this->userView($request->user(),$article);
        return response()->json($this->userView($request->user(),$article));
    }

    public function userView(Fan $user,Article $article){

        $article->view ++;
        $article->save();
        return '';
    }

    /**
     * 喜欢某个文章
     */
    public function likearticle(Request $request){
        $article = Article::findOrFail($request->get('article_id'));
        // $this->userView($request->user(),$article);
        return response()->json($this->userLikeArticle($request->user(),$article));
    }
    
    public function userLikeArticle(Fan $user,Article $article){
        $article->liked ++;
        $article->save();
        $user->like($article);
        return '';
    }

    
    /**
     * 喜欢取消某个文章
     */
    public function unlikearticle(Request $request){
        $article = Article::findOrFail($request->get('article_id'));
        // $this->userView($request->user(),$article);
        return response()->json($this->userUnLikeArticle($request->user(),$article));
    }
    
    public function userUnLikeArticle(Fan $user,Article $article){
        $article->liked --;
        $article->save();
        $user->unlike($article);
        return '';
    }
}
