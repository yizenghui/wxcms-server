<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cash extends Model
{
    use SoftDeletes;

    protected $table = 'cash';
}
