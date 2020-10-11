<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public function order()
    {
    	return $this->belongsTo('App\Models\Order');
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\User','vendor_id');
    }

    public function product()
    {
    	return $this->belongsTo('App\Models\Product');
    }

    public function conversation()
    {
        return $this->belongsTo('App\Models\Conversation');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice', 'invoice_id');
    }

    public static function countRegistration()
    {
        return Notification::where('user_id','!=',null)
                            ->where('order_id', '=', null)
                            ->where('is_read','=',0)
                            ->orderBy('id','desc')
                            ->get()
                            ->count();
    }

    public static function countOrder()
    {
        return Notification::where('order_id','!=',null)
                            ->where('user_id', '=', null)
                            ->where('proccess_order', '=', null)
                            ->where('delivery_order', '=', null)
                            ->where('complete_order', '=', '0')        
                            ->where('is_read','=',0)
                            ->orderBy('id','desc')
                            ->get()
                            ->count();
    }

    public static function countProduct()
    {
        return Notification::where('product_id','!=',null)->where('is_read','=',0)->orderBy('id','desc')->get()->count();
    }

    public static function countConversation()
    {
        return Notification::where('conversation_id','!=',null)->where('user_id', '=', null)->where('is_read','=',0)->orderBy('id','desc')->get()->count();
    }

    public static function countCustomer($id)
    {
        return Notification::where('user_id','=',$id)
                            ->where('is_read','=',0)
                            ->where('is_customer', '1')
                            ->get()
                            ->count();        
    }

    public static function countTransOrder()
    {
        $data = Notification::where('order_id','!=',null)
                ->where('is_read','=',0)
                ->where(function($q) {
                    $q->OrWhere('proccess_order', '=', '1')
                    ->OrWhere('delivery_order', '=', '1')
                    ->OrWhere('complete_order', '=', '1');
                })
                ->get()
                ->count();
        
        return $data;
    }   
    
    public static function countVendorConversation($userId = null)
    {
        return Notification::where('conversation_id','!=',null)->where('user_id', '=', $userId)->where('is_read','=',0)->orderBy('id','desc')->get()->count();
    }

}
