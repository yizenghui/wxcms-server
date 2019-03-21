<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Http\Resources\GoodsResource;

class GoodsController extends Controller
{
    //
    
    public function index(Request $request){
        $data = Goods::all();
        $goodes = GoodsResource::collection($data);
        return response()->json($goodes);
    }

}
