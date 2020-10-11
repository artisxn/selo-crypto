<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use Validator;
use DB;
use Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function user_notf_count()
    {
        $data = Notification::where('user_id','!=',null)
                ->where('order_id', '=', null)
                ->where('proccess_order', '=', null)
                ->where('delivery_order', '=', null)
                ->where('complete_order', '=', '0')                
                ->where('is_read','=',0)
                ->get()
                ->count();
        return response()->json($data);            
    } 

    public function user_notf_clear()
    {
        $data = Notification::where('user_id','!=',null);
        $data->delete();        
    } 

    public function user_notf_show()
    {
        $datas = Notification::where('user_id','!=',null)
                ->where('order_id', '=', null)
                ->where('proccess_order', '=', null)
                ->where('delivery_order', '=', null)
                ->where('complete_order', '=', '0')
                ->where('is_customer', '=', '0')
                ->get()->take(10);
        if($datas->count() > 0){
          foreach($datas as $data){
            $data->is_read = 1;
            $data->update();
          }
        }       
        return view('admin.notification.register',compact('datas'));           
    } 


    public function order_notf_count()
    {
        $data = Notification::where('order_id','!=',null)
                ->where('user_id', '=', null)
                ->where('proccess_order', '=', null)
                ->where('delivery_order', '=', null)
                ->where('complete_order', '=', '0')
        ->where('is_read','=',0)->get()->count();
        return response()->json($data);            
    } 

    public function order_notf_clear()
    {
        $data = Notification::where('order_id','!=',null);
        $data->delete();        
    } 

    public function order_notf_show()
    {
        $datas = Notification::where('order_id','!=',null)
                ->where('user_id', '=', null)
                ->where('proccess_order', '=', null)
                ->where('delivery_order', '=', null)
                ->where('complete_order', '=', '0')
                ->orderBy('created_at', 'desc')->get()->take(10);
        $complete = Notification::where('order_id','!=',null)->where('complete_order','=','1')->orderBy('created_at', 'desc')->get();
        if($datas->count() > 0){
          foreach($datas as $data){
            $data->is_read = 1;
            $data->update();
          }
        }       

        if($complete->count() > 0){
            foreach($complete as $data){
              $data->is_read = 1;
              $data->update();
            }
          }       

        return view('admin.notification.order',compact('datas','complete'));           
    } 


    public function product_notf_count()
    {
        $data = Notification::where('product_id','!=',null)->where('is_read','=',0)->get()->count();
        return response()->json($data);            
    } 

    public function product_notf_clear()
    {
        $data = Notification::where('product_id','!=',null);
        $data->delete();        
    } 

    public function product_notf_show()
    {
        $datas = Notification::where('product_id','!=',null)->orderBy('created_at', 'desc')->get()->take(5);
        $pendings = Notification::where('product_id', '!=',null)->where('pending_product','1')->orderBy('created_at', 'desc')->get()->take(5);

        if($datas->count() > 0){
          foreach($datas as $data){
            if ($data->product <> '') {
                $data->is_read = 1;
                $data->update();
            }
          }
        }       

        if ($pendings->count() > 0) {
            foreach($pendings as $pending){
                if ($pending->product <> '') {
                    $pending->is_read = 1;
                    $pending->update();
                }
            }            
        }

        return view('admin.notification.product',compact('datas', 'pendings'));           
    } 


    public function conv_notf_count()
    {
        $data = Notification::where('conversation_id','!=',null)->where('is_read','=',0)->get()->count();
        return response()->json($data);            
    } 

    public function conv_notf_clear()
    {
        $data = Notification::where('conversation_id','!=',null);
        $data->delete();        
    } 

    public function conv_notf_show()
    {
        $datas = Notification::where('conversation_id','!=',null)->orderBy('created_at', 'desc')->get();
        if($datas->count() > 0){
          foreach($datas as $data){
            $data->is_read = 1;
            $data->update();
          }
        }       
        return view('admin.notification.message',compact('datas'));           
    } 

    public function trans_notf_count()
    {
        $data = Notification::where('order_id','!=','')
                ->where('is_read','=',0)
                ->where(function($q) {
                    $q->OrWhere('proccess_order', '=', '1')
                    ->OrWhere('delivery_order', '=', '1')
                    ->OrWhere('complete_order', '=', '1');
                })
                ->get()
                ->count();
        return response()->json($data);            
    } 

    public function trans_notf_clear()
    {
        $data = Notification::where('order_id','!=','')
                ->where('proccess_order', '=', '1')
                ->where('delivery_order', '=', '1')
                ->where('complete_order', '=', '1');
        $data->delete();        
    } 

    public function trans_notf_show()
    {
        $datas = Notification::where('user_id', '=', null)
                ->where('order_id','!=','')
                ->Where(function($q) {
                    $q->OrWhere('proccess_order', '=', '1')
                    ->OrWhere('delivery_order', '=', '1')
                    ->OrWhere('complete_order', '=', '1');
                })
                ->orderBy('created_at', 'desc')
                ->get()->take(12);


        // dd( $datas );
                
        if($datas->count() > 0){
          foreach($datas as $data){
            $data->is_read = 1;
            $data->update();
          }
        }  

        return view('admin.notification.status_order',compact('datas'));           
    }

    public function send_notif(Request $request)
    {
        $rules = [
            'reference' => 'required|numeric|min:6',
            'order' => 'required'
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }        

        $order = Order::where('order_number', $request->order)
                ->where('status', '=', 'completed')
                ->where('vendor_payment', '=', '0')
                ->firstOrFail();

        if ($order <> '') {
            DB::beginTransaction();
            try {
                $notif = new Notification;
                $notif->order_id = $order->id;
                $notif->user_id = $order->vendororders[0]->user_id;
                $notif->pembayaran_vendor = '1';
    
                $notif->save();

                $order->vendor_payment = '1';
                $order->payment_vendor_date = date('Y-m-d H:i:s');
                $order->payment_vendor_admin = Auth::user()->email;
                $order->payment_vendor_reference = $request->reference;
                $order->save();
    
                DB::commit();
                return response()->json(['Successfully sent notification to vendor'], 200);
            } catch (Exception $e) {
                DB::rollback();

                return response()->json(['errors' => 'Ooops, something wrong. Try again later.'], 200);
            }
        }else{
            return response()->json(['errors' => 'Order Number Not Found'], 404);
        }
    }

    public function notif_ds(Request $request)
    {
        $rules = [
            'reference' => 'required|numeric',
            'order' => 'required'
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }        

        $order = Order::where('order_number', $request->order)->where('status', '=', 'completed')->firstOrFail();

        if ($order <> '') {
            DB::beginTransaction();
            try {
                $notif = new Notification;
                $notif->order_id = $order->id;
                $notif->user_id = $order->user_id;
                $notif->pembayaran_vendor_dropshipper = '1';
    
                $notif->save();

                $order->dropshipper_payment = '1';
                $order->payment_dropshipper_date = date('Y-m-d H:i:s');
                $order->payment_dropshipper_admin = Auth::user()->email;
                $order->payment_dropshipper_reference = $request->reference;
                $order->save();
    
                DB::commit();
                return response()->json(['Successfully sent notification to dropshipper'], 200);
            } catch (Exception $e) {
                DB::rollback();

                return response()->json(['errors' => 'Ooops, something wrong. Try again later.'], 200);
            }
        }else{
            return response()->json(['errors' => 'Order Number Not Found'], 404);
        }
    }

}
