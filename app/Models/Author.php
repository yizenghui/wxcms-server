<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appid','name','intro', 'state','user_id'
    ];

    
    /**
     * 自定义分享
     */
    public function share()
    {
        return $this->belongsTo(Share::class,'share_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
