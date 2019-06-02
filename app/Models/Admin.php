<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;

class Admin extends Administrator
{

    public function app()
    {
        return $this->hasOne(App::class,'appid');
    }

}
