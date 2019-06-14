<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class CommentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        $time = $this->created_at->format('Y-m-d H:i:s');
        return [
            'id'   => (string)$this->id,
            'username' => $this->commented->name,
            'useravatar' => $this->commented->avatar,
            'comment'  => $this->comment, //评论
            'liked'  => 0, //$this->liked, //
            'comment_at'  => $time, //
        ];
        // return parent::toArray($request);
    }
}
