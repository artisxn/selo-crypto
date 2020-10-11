<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edccash extends Model
{
    protected $table = "edc_cash";

    public function user()
    {
        return $this->belongsTo('App\Models\User');   
    }
}
