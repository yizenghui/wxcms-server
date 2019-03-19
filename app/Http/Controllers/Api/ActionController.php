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
}
