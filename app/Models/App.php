<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class App extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','position','genre','state','start_at','end_at',
    ];



    
    public function admin()
    {
        return $this->belongsTo(config('admin.database.users_model'));
    }
    
}
