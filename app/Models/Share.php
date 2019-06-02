<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Share extends Model
{
    use SoftDeletes;


    public function setCoverAttribute($pictures)
    {
        // dd($pictures);
        if (is_array($pictures)) {
            $this->attributes['cover'] = json_encode($pictures);
        }
    }

    public function getCoverAttribute($pictures)
    { 
        // dd($pictures);
        return json_decode($pictures, true);
    }

    

    public function getOneTitleAttribute()
    { 
        if(!$this->title) return '';
        $arr = explode("\r\n", $this->title);
        return $arr[array_rand($arr)];
    }

    public function getOneCoverAttribute()
    {
        // \Storage::disk(config('admin.upload.disk'))->downloadUrl()
        return $this->cover?\Storage::disk(config('admin.upload.disk'))->url($this->cover[array_rand($this->cover)]):'';
    }

    
}
