<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AdResource extends Resource
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
            'url' => $this->url,
            'cover' =>  $this->cover, //后台保存完整路径
            'poster' => $this->poster,
            'gotoapp' => $this->gotoapp,
            'position' => $this->position,
            'genre' => $this->genre,
            'text' => $this->text,
            'wxto'  => '/pages/article/info?id='.$this->id,
        ];
        // return parent::toArray($request);
    }
}
