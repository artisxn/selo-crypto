<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Models\Notification;
use Auth;

class NotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function order_notf_count($id)
    {
        $data = UserNotification::where('user_id','=',$id)->where('is_read','=',0)->get()->count();
        $data += Notification::where('user_id', '=', $id)->where('pembayaran_vendor', '!=', '')->where('pembayaran_vendor_dropshipper', '!=', '')->where('is_read', '=',0)->get()->count();
        return response()->json($data);            
    } 

    public function order_notf_clear($id)
    {
        $data = UserNotification::where('user_id','=',$id);
        $data->delete();        
    } 

    public function order_notf_show($id)
    {
        $datas = UserNotification::where('user_id','=',$id)->where('is_dropship', '0')->orderBy('created_at', 'desc')->get()->take(4);
        $dropships = Notification::where('user_id','=',$id)->where('pembayaran_vendor_dropshipper', '1')->where('order_id', '!=', null)->get();
        $completes = Notification::where('user_id','=',$id)
                    ->where('complete_order', '=', '1')
                    ->orderBy('created_at', 'desc')
                    ->get()->take(4);
                    
        
        $paidOrder = Notification::where('user_id','=',$id)
                    ->where('paid_order', '=', '1')
                    ->orderBy('created_at', 'desc')
                    ->get()->take(4);

        $payment = Notification::where('user_id', '=', $id)
                    ->where('pembayaran_vendor', '1')
                    ->where('order_id', '!=', '')
                    ->orderBy('created_at', 'desc')
                    ->take(4)
                    ->get();

        if($datas->count() > 0){
          foreach($datas as $data){
            $data->is_read = 1;
            $data->update();
          }
        }

        if($completes->count() > 0){
            foreach($completes as $data){
              $data->is_read = 1;
              $data->update();
            }
        }

        if($paidOrder->count() > 0){
            foreach($paidOrder as $data){
              $data->is_read = 1;
              $data->update();
            }
        }

        if($payment->count() > 0){
            foreach($payment as $data){
              $data->is_read = 1;
              $data->update();
            }
        }        

        return view('vendor.notification.order',compact('datas', 'completes', 'paidOrder', 'payment', 'dropships'));           
    }
    
    public function vendorPayment()
    {
        $id = Auth::user()->id;
        $payments = Notification::where('user_id', '=', $id)
                ->where('pembayaran_vendor', '1')
                ->where('order_id', '!=', '')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

        if($payments->count() > 0){
            foreach($payments as $data){
              $data->is_read = 1;
              $data->update();
            }
        }
        
        
        return view('vendor.notification.payment', compact('payments'));
    }


    public function conv_notf_count()
    {
        $data = Notification::where('conversation_id','!=',null)->where('user_id', '=', Auth::user()->id)->where('is_read','=',0)->get()->count();
        return response()->json($data);            
    } 

    public function conv_notf_clear()
    {
        $data = Notification::where('conversation_id','!=',null)->where('user_id', '=', Auth::user()->id);
        $data->delete();        
    } 

    public function conv_notf_show()
    {
        $datas = Notification::where('conversation_id','!=',null)->where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        if($datas->count() > 0){
          foreach($datas as $data){
            $data->is_read = 1;
            $data->update();
          }
        }       
        return view('vendor.notification.message',compact('datas'));           
    } 
}
