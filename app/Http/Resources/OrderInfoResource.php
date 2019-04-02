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
        $boss->avatar = \Storage::disk(config('admin.upload.disk'))->url($boss->avatar);
        $boss->qrcode = \Storage::disk(config('admin.upload.disk'))->url($boss->qrcode);
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
            'cover' => \Storage::disk(config('admin.upload.disk'))->url($this->cover),
            'wxto'  => '/pages/user/exchange?id='.$this->id,
            'guidesteps'=>[
                [ 'icon'=>'usefullfill','name'=>'联系客服','intro'=>'点击售后客服名片，识别名片中的二维码，将售后客服添加为微信好友。' ], 
                [ 'icon'=>'radioboxfill','name'=>'发送密令','intro'=>'复制兑换卷密令并将其发给售后客服（提醒：兑换密令不要告诉别人，以免被盗用、冒领）。' ],
                [ 'icon'=>'radioboxfill','name'=>'核对信息','intro'=>'售后客服收到密令核对无误后，收集、确认必要的信息（如：收货地址、话费充值手机号）。'],
                [ 'icon'=>'radioboxfill','name'=>'物品发放','intro'=>'向您发放相应兑换物品，并提供回执（快递单号、充值记录、转帐记录等）。'],
                [ 'icon'=>'roundcheckfill','name'=>'完成兑换','intro'=>'发放兑换物品后，完成兑换。如有疑问或问题，可咨询售后客服或向在线客服申诉。']
            ]
        ];

        return parent::toArray($request);
    }
}
