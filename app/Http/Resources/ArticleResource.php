<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ArticleResource extends Resource
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
            'title' => $this->title,
            'intro' => $this->intro?$this->intro:'',
            'cover' =>  $this->cover?config('app.url').'/uploads/'.$this->cover:'',
            'view'  => $this->view, //浏览数
            'commented'  => $this->commented, //评论数
            'liked'  => $this->liked, //点赞数
            'wxto'  => '/pages/article/info?id='.$this->id,
        ];
        // return parent::toArray($request);
    }
}
