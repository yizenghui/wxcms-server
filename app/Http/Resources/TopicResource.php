<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TopicResource extends Resource
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
            'intro' => $this->intro,
            'cover' =>  config('app.url').'/uploads/'.$this->cover,
            'wxto'  => '/pages/topic/list?id='.$this->id,
        ];

        return parent::toArray($request);
    }
}
