<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLastView extends Model
{
    //
    protected  $fillable=[
        'product_id',
        'user_id',
        'date'
    ];
    public $timestamps = false;
}
