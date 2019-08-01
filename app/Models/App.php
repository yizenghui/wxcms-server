<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;

class App extends Model
{

    public function admin()
    {
        return $this->belongsTo(config('admin.database.users_model'));
    }
    
    public function getIsvipAttribute()
    {
        $now = Carbon::now();
        if($this->vip_status && $now->lt( Carbon::parse($this->vip_deadline) )){
            return true;
        }
        return false;
    }

    // 加密的apps表的id 
    public function getTidAttribute()
    {
        return Hashids::encode( $this->id,date('Ymd'));
    }
}
