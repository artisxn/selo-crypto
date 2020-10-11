<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    protected $table = "districts";

    public function user()
    {
        return $this->belongsTo('App\Models\User');   
    }       
}
