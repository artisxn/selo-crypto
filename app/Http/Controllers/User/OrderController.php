<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Order;
use App\Models\VendorOrder;
use App\Models\Product;
use App\Models\PaymentGateway;
use App\Models\Notification;
use Illuminate\Support\Facades\Crypt;
use Session;
use DB;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function orders()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::where('user_id','=',$user->id)->orderBy('created_at','desc')->get();
        
        $cart = '';
        if (Session::get('order-number')) {
            $orderNumber = Session::get('order-number');
            $order = Order::where('order_number', $orderNumber)->first();
            $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        }

        return view('user.order.index',compact('user','orders', 'cart'));
    }

    public function ordertrack()
    {
        $user = Auth::guard('web')->user();
        return view('user.order-track',compact('user'));
    }

    public function trackload($id)
    {
        $order = Order::where('order_number','=',$id)->first();
        $datas = array('Pending','Processing','On Delivery','Completed');
        return view('load.track-load',compact('order','datas'));

    }


    public function order($id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::findOrfail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('user.order.details',compact('user','order','cart'));
    }

    public function orderdownload($slug,$id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::where('order_number','=',$slug)->first();
        $prod = Product::findOrFail($id);
        if(!isset($order) || $prod->type == 'Physical' || $order->user_id != $user->id)
        {
            return redirect()->back();
        }
        return response()->download(public_path('assets/files/'.$prod->file));
    }

    public function orderprint($id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::findOrfail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('user.order.print',compact('user','order','cart'));
    }

    public function trans()
    {
        $id = $_GET['id'];
        $trans = $_GET['tin'];
        $order = Order::findOrFail($id);
        $order->txnid = $trans;
        $order->update();
        $data = $order->txnid;
        return response()->json($data);            
    }  

    public function confirmOrder(Request $request, $slug)
    {
        $user = Auth::user();
        $order = Order::where('order_number', '=', $slug)->where('user_id','=',$user->id);
        $vendorOrder = VendorOrder::where('order_number','=',$slug);

        if (count($order->get()) >= 1 && count($vendorOrder->get()) >= 1) {
            DB::beginTransaction();
            try {
                $order->update(['status' => 'completed']);
                $vendorOrder->update(['status' => 'completed']);

                $notif = new Notification;
                $notif->user_id = $user->id;
                $notif->order_id = $order->firstOrFail()->id;
                $notif->header = 'Your Order is '.ucwords( strtolower('completed') );
                $notif->messages = 'Order with #'.$slug.' is '.ucwords( strtolower('completed') );
                $notif->is_customer = '1';

                $notif->save();            
                
                $notifAdmin = new Notification;
                $notifAdmin->order_id = $order->firstOrFail()->id;
                $notifAdmin->complete_order = '1';

                $notifAdmin->save();

                $notifVendor = new Notification;
                $notifVendor->order_id = $order->firstOrFail()->id;
                $notifVendor->user_id = $vendorOrder->firstOrFail()->user_id;
                $notifVendor->complete_order = '1';

                $notifVendor->save();
                Session::flash('order-number', $slug);        
                Session::flash('konfirmasi-order-sukses', "Status berhasil di selesaikan");   
                
                
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                Session::flash('konfirmasi-order-gagal', "Gagal di selesaikan");    
            }

            return Redirect()->back();
        }else{
            Session::flash('konfirmasi-order-gagal', "Tidak dapat menemukan transaksi");
            return Redirect()->back();
        }
    }

    public function tracking(Request $request)
    {
        $resi = $request->resi;
        $kurir = $request->kurir;

        $url = 'https://pro.rajaongkir.com/api/waybill';
        $header = [
            'Content-Type: application/x-www-form-urlencoded',
            'key: a46c2673d8d088eb2aca104481c62806'
        ];

        $data = [
            'waybill' => $resi,
            'courier' => $kurir
        ];

        $ch = curl_init();
        
        if ($data !== FALSE) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $response = curl_exec($ch);
        curl_close($ch);

        // End

        $result = (array) json_decode($response);

        return view('user.order.tracking',compact('result','responseCode', 'resi'));        
    }
}
