<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class FanResource extends Resource
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
            'name' =>  $this->name?$this->name:'??'.$this->id,
            'gender' => $this->gender,
            'city' => $this->city,
            'avatar' => $this->avatar,
            'total_point' => $this->total_point
        ];
        // return parent::toArray($request);
    }
}
