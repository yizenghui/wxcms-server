<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Http\Resources\TopicResource;

class TopicController extends Controller
{
    //
    public function index(){
        $data = Topic::where('appid', '=', request()->get('appid'))->where('state','=',1)->orderBy('order')->simplePaginate(20);
        $topics = TopicResource::collection($data);
        return response()->json($topics);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $topic = Topic::findOrFail($id);
        return response()->json($topic);
    }

}
