<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Vinkla\Hashids\Facades\Hashids;


class OrderInfoResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $canuse = ($this->delivery_at || date('Y-m-d H:i:s') > $this->lower_at) ?0:1;

        $goods = $this->goods;
        $boss = $goods->boss;
        $boss->avatar = config('app.url').'/uploads/'.$boss->avatar;
        $token = Hashids::encode($this->id);
        return [
            'id'   => (string)$this->id,
            'token'   => $token,
            'token_talk'   => $token,
            'name' => $this->name,
            'cash_value' => $this->cash_value,
            'point' => $this->point,
            'num' => $this->num,
            'goods' => $goods,
            'boss' => $boss,
            'canuse' => $canuse, 
            'lower_at' => $this->lower_at,  // 失效时间 (如果已经发货，不再显示失效时间)
            'delivery_at' => $this->delivery_at, // 发货时间
            'cover' =>  config('app.url').'/uploads/'.$this->cover,
            'wxto'  => '/pages/user/exchange?id='.$this->id,
        ];

        return parent::toArray($request);
    }
}
