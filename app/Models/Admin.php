<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;

class Admin extends \Encore\Admin\Registration\User
{

    public function app()
    {
        return $this->hasOne(App::class,'appid');
    }

}
