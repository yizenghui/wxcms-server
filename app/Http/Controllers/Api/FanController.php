<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\OrderResource;

class FanController extends Controller
{
    // 
    public function footprint(Request $request){
        $user = $request->user();
        $data = $user->bookmarks(\App\Models\Article::class)->get();
        $articles = ArticleResource::collection($data);
        return response()->json($articles);
    }

    public function like(Request $request){
        $user = $request->user();
        $data = $user->likes(\App\Models\Article::class)->get();
        $articles = ArticleResource::collection($data);
        return response()->json($articles);
    }

    public function getme(Request $request){
        $user = $request->user();
        return response()->json($user);
    }

    public function order(Request $request){
        $user = $request->user();
        $orders = OrderResource::collection($user->orders);
        return response()->json($orders);
    }

}
