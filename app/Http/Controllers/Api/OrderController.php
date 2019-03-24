<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Resources\GoodsResource;
use App\Http\Resources\OrderInfoResource;

class OrderController extends Controller
{

    public function show($id, Request $request){
        $order = Order::findOrFail($id);
        if($order->user_id != $request->user()->id ) return response()->json(['message'=>'无权查看该订单信息']);
        
        // todo 权限判断
        return response()->json(new OrderInfoResource($order));
    }

}
