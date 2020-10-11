<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regencies extends Model
{
    protected $table = "regencies";

    public function user()
    {
        return $this->hasMany('App\Models\User', 'city_id');
    }
}
