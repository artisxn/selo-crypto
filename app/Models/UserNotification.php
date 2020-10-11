<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{

    public static function countOrder($id)
    {
        return UserNotification::where('user_id','=',$id)->where('is_read','=',0)->get()->count();
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_number', 'order_number');
    }

}
