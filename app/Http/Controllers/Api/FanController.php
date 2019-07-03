<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PointLogResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\FanResource;
use App\Models\Fan;

class FanController extends Controller
{
    // 
    public function footprint(Request $request){
        $user = $request->user();
        $data = $user->bookmarks(\App\Models\Article::class)->simplePaginate(20);
        $articles = ArticleResource::collection($data);
        return response()->json($articles);
    }

    public function like(Request $request){
        $user = $request->user();
        $data = $user->likes(\App\Models\Article::class)->simplePaginate(20);
        $articles = ArticleResource::collection($data);
        return response()->json($articles);
    }


    public function getme(Request $request){
        $user = $request->user();
        $user->task = $user->todaytask();
        $user->task->total = intval( $user->task->total );
        $user->showlogin = $user->name?false:true;
        $user->name = $user->name?$user->name:'游客'.$user->id;
        return response()->json($user);
    }

    public function order(Request $request){
        $user = $request->user();
        $orders = OrderResource::collection($user->orders()->simplePaginate(10));
        return response()->json($orders);
    }

    public function getuserinfo(Request $request){
        $user = $request->user();
        $user->task = $user->todaytask();
        $user->task->total = intval( $user->task->total );
        
        $user->showlogin = $user->name?false:true;
        $user->name = $user->name?$user->name:'游客'.$user->id;
        
        return response()->json($user);
    }

    public function pointlog(Request $request){
        $user = $request->user();
        $pointlogs = $user->pointlogs()->orderBy('id','desc')->simplePaginate(20);
        return response()->json(PointLogResource::collection($pointlogs));
    }

    public function rank(Request $request){
        $fans = Fan::where('appid', '=', $request->get('appid'))->where('sign_at', '=', date('Ymd'))->orderBy('total_point','desc')->simplePaginate(20);
        return response()->json(FanResource::collection($fans));
    }

    public function tasklog(Request $request){
        $user = $request->user();
        $logs = $user->tasks()->orderBy('id','desc')->simplePaginate(10);
        return response()->json(TaskResource::collection($logs));
    }

    /**
     * 每日组队
     */
    public function team(Request $request){
        $user = $request->user();
        return response()->json($user);
    }
}
