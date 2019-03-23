<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\Order;
use App\Http\Resources\GoodsResource;

class GoodsController extends Controller
{
    //
    
    public function index(Request $request){
        $data = Goods::all();
        $goodes = GoodsResource::collection($data);
        return response()->json($goodes);
    }

    public function buy(Request $request){
        $goods_id = $request->get('goods_id');
        $num = $request->get('num');
        $goods = Goods::findOrFail($goods_id);
        $user = $request->user();
        if($goods->lower_at < date('Y-m-d H:i:s')) return response()->json(['message'=>'商品已过期']);
        if($goods->stock < $num ) return response()->json(['message'=>'库存不足']);
        
        $order = new Order;
        $order->user_id = $user->id;
        $order->goods_id = $goods->id;
        $order->name = $goods->name;
        $order->num = $num;
        $order->point = $goods->point;
        $order->point_total = $num * $goods->point;
        $order->cash_total = $num * $goods->cash_value;
        $order->cover = $goods->cover;
        $order->lower_at = $goods->invalid_at?$goods->invalid_at:$goods->lower_at; // 失效时间等值商品设置的卷无效时间

        if($user->point < $order->point_total) return response()->json(['message'=>'剩余积分不足']);
        // todo 允许部分商品用剩余积分进行结算?
        if( $user->current_point < $order->point_total ) return response()->json(['message'=>'当前可用积分不足']);

        // 用户扣减积分
        $user->point -= $order->point_total; 
        $user->current_point -= $order->point_total;
        
        $goods->out += $num; // 加销量
        $goods->stock -= $num; //减库存
        $user->save(); // 保存用户数据
        $goods->save(); // 保存商品
        $order->save(); // 保存订单
        // todo 下单通知
        return response()->json($order);

    }

}
