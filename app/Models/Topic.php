<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use SoftDeletes;
    
    /**
     * 自定义分享
     */
    public function share()
    {
        return $this->belongsTo(Share::class,'share_id');
    }
}
