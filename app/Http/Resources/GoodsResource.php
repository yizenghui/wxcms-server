<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class GoodsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'   => (string)$this->id,
            'name' => $this->name,
            'cash_value' => $this->cash_value,
            'point' => $this->point,
            'intro' => $this->intro,
            'stock' => $this->stock,
            'out' => $this->out,
            'tag' => $this->tag,
            'tag_style' => $this->tag_style,
            'lower_at' => $this->lower_at,
            'invalid_at' => $this->invalid_at,
            'cover' =>  \Storage::disk(config('admin.upload.disk'))->downloadUrl($this->cover,'https'),
            'wxto'  => '/pages/user/goodsinfo?id='.$this->id,
        ];

        return parent::toArray($request);
    }
}
