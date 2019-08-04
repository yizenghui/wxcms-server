<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Auth;

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
        
        $reply_msg = null;
        if($this->reply_id){
            $reply_msg = [
                'id'=>$this->quote->id,
                'user_id'=>$this->quote->commented->id,
                'user_name'=>$this->quote->commented->name?$this->quote->commented->name:'游客'.$this->quote->commented->id,
                'user_avatar'=>$this->quote->commented->avatar,
                'comment'=>$this->quote->comment,
            ];
        }

        $user = Auth::user();
        
        return [
            'id'   => (string)$this->id,
            'article_id'   => (string)$this->commentable_id,
            'user_id'   => (string)$this->commented_id,
            'comment' => $this->approve>-1?$this->comment:'评论内容已屏蔽',
            'created_at' => $this->created_at->toDateTimeString(),
            'reply_id' => intval($this->reply_id),
            'user_name' => $this->commented->name?$this->commented->name:'游客'.$this->commented->id, //后台保存完整路径
            'user_avatar' => $this->commented->avatar,
            'quote'=> $reply_msg,
            'like'=>intval($this->like),
            'userliked'=>$user->hasLiked($this->resource), // todo待优化(访问速度很慢)
            // 'userunlike'=>$user->hasDownvoted($this->resource),
            'reply'=>intval($this->reply),
            'rank'=>intval($this->rank),
        ];
        // return parent::toArray($request);
    }
}
