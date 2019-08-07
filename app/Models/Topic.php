<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appid','name','intro', 'cover','state','order'
    ];
    /**
     * 自定义分享
     */
    public function share()
    {
        return $this->belongsTo(Share::class,'share_id');
    }
}
