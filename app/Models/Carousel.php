<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Carousel extends Model
{
    use SoftDeletes;


    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appid','name','cover','position','genre', 'state','start_at','end_at','priority'
    ];
    
}
