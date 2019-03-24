<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Model
{
    use SoftDeletes;

    protected $table = 'goodses';

    
    /**
     * 获取商品的订单
     */
    public function orders()
    {
         return $this->hasMany(Order::class,'goods_id');
    }

    
    /**
     * 这个商品由一个老板发货
     */
    public function boss()
    {
        return $this->belongsTo(Boss::class,'boss_id');
    }
}
