<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActionController extends Controller
{

    public function view(Request $request){
        
        // $topic = Topic::findOrFail($id);
        
        return response()->json($request->user());
    }

}
