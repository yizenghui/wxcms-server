<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Http\Resources\AdResource;

class AdController extends Controller
{


    public function index(Request $request){
        $data = Ad::where('appid','=',$request->get('appid'))->where('state','=',1)
        ->orderBy('priority','desc')
        ->simplePaginate(30);
        $articles = AdResource::collection($data);
        return response()->json($articles);
    }

}
