<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    protected $fillable = ['product_id', 'user_id','title','note'];

    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }

    public function product()
    {
    	return $this->belongsTo('App\Models\Product');
    }

}
