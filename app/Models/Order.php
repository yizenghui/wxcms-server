<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /**
     * 该订单对应的一个商品 (一个订单只能下一个商品)
     */
    public function goods()
    {
        return $this->belongsTo(Goods::class,'goods_id');
    }
    /**
     * 下单用户
     */
    public function user()
    {
        return $this->belongsTo(Fan::class,'user_id');
    }
}
