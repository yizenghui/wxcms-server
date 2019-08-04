<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Overtrue\LaravelFollow\Traits\CanBeLiked;
use Overtrue\LaravelFollow\Traits\CanBeVoted;

class Comment extends Model
{

    use CanBeLiked,CanBeVoted;

    protected $fillable = [
        'comment','approve','rank','reply_id','commented_id','commented_type','commentable_id','commentable_type',
    ];

    use SoftDeletes;

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function commented(): MorphTo
    {
        return $this->morphTo();
    }

    public function replies() //这个评论的回复
    {
         return $this->hasMany(Comment::class,'reply_id', 'id');
    }
    
    public function quote()  //这个评论是评论谁的评论的 引用谁的评论
    {
         return $this->belongsTo(Comment::class,'reply_id', 'id');
    }

    public function scopeApprovedComments(Builder $query): Builder
    {
        return $query->where('approve', 1);
    }
}
