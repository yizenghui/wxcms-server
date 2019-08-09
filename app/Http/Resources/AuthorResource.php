<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AuthorResource extends Resource
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
            'cover' => $this->cover,
            'wxto'  => '/pages/author/list?id='.$this->id,
        ];

        return parent::toArray($request);
    }
}
