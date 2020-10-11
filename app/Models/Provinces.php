<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    protected $table = "provinces";

    public function user()
    {
        return $this->hasMany('App\Models\User', 'provinces');   
    }       
}
