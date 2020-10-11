<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = "address";

    public function province()
    {
        return $this->belongsTo('App\Models\Provinces');
    }

    public function regency()
    {
        return $this->belongsTo('App\Models\Regencies', 'city_id');
    }

    public function disctrict()
    {
        return $this->belongsTo('App\Models\Districts');
    }
}
