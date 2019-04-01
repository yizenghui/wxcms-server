<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'did','name','user_id'
    ];
    
    /**
     * 用户所加入的队伍
     */
    public function users()
    {
        return $this->belongsToMany(Fan::class,'teamables','team_id','user_id');
    }

}
