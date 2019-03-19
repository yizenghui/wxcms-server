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
        $topics = TopicResource::collection(Topic::all());
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
