<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $dates = [ 'created_at', 'updated_at' ];
}
