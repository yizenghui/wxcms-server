<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Http\Resources\CarouselResource;

class CarouselController extends Controller
{


    public function index(Request $request){
        $data = Carousel::where('appid','=',$request->get('appid'))->where('state','=',1)
        ->orderBy('priority','desc')
        ->simplePaginate(10);
        $articles = CarouselResource::collection($data);
        return response()->json($articles);
    }

}
