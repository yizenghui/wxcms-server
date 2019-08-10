<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;

class Visitor extends Model
{
    use SortableTrait;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','did','appid','fromid','scene',
    ];

    public $sortable = [
        'order_column_name' => 'id',
        'sort_when_creating' => true,
    ];
}
