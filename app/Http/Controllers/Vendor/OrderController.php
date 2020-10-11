<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\VendorOrder;
use App\Models\Notification;
use Session;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $orders = VendorOrder::where('user_id','=',$user->id)->orderBy('id','desc')->get()->groupBy('order_number');

        return view('vendor.order.index',compact('user','orders'));
    }

    public function updateresi(Request $request, $id)
    {
        $order = Order::where('order_number', $id)->first();
        $order->no_resi = $request->no_resi;
        $order->save();
        
        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'msg' => 'Nomor resi order berhasil diupdate'], 200);
        }else{
            Session::flash('msg', "Nomor resi order berhasil diupdate");
            return Redirect()->back();
        }
    }

    public function show($slug)
    {
        $user = Auth::user();
        $order = Order::where('order_number','=',$slug)->first();
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));

        $slugDropshipper = [];
        $dropshippers = [];
        foreach ($cart->items as  $product) {
            if ($product['is_dropship'] !== FALSE) {
                $slugDropshipper[] = ['slug' => $product['slug_toko'], 'id' => $product['toko_id']];
            }
        }

        foreach ($slugDropshipper as $key => $toko) {
            $user = User::where('slug_shop_name', $toko['slug'])
                            ->where('is_vendor', 2)
                            ->firstOrFail();
            if ($user->subscribes->is_dropship === '1') {
                $dropshippers[] = $user;    
            }
            
        }

        return view('vendor.order.details',compact('user','order','cart', 'dropshippers'));
    }

    public function license(Request $request, $slug)
    {
        $order = Order::where('order_number','=',$slug)->first();
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        $cart->items[$request->license_key]['license'] = $request->license;
        $order->cart = utf8_encode(bzcompress(serialize($cart), 9));
        $order->update();         
        $msg = 'Successfully Changed The License Key.';
        return response()->json($msg);
    }



    public function invoice($slug)
    {
        $user = Auth::user();
        $order = Order::where('order_number','=',$slug)->first();
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('vendor.order.invoice',compact('user','order','cart'));
    }

    public function printpage($slug)
    {
        $user = Auth::user();
        $order = Order::where('order_number','=',$slug)->first();
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('vendor.order.print',compact('user','order','cart'));
    }

    public function status($slug,$status)
    {
        $mainorder = VendorOrder::where('order_number','=',$slug)->first();
        if ($mainorder->status == "completed"){
            return redirect()->back()->with('success','Pesanan ini Telah Selesai');
        }else{
            $user = Auth::user();
            $orderResi = Order::where('order_number', $slug)->get()->pluck('no_resi');
            
            if ($status !== 'on delivery' ? true : !in_array(null, $orderResi->toArray())) {
                $order = VendorOrder::where('order_number','=',$slug)->where('user_id','=',$user->id)->update(['status' => $status]);
                $order_user = VendorOrder::where('order_number','=',$slug)->where('user_id','=',$user->id)->get();
                $notif = new Notification;
                foreach($order_user as $update_order){
                    $update = Order::where('id', $update_order->order_id)->update(['status' => $status]);
                }

                $notif->user_id = Order::where('order_number', $slug)->firstOrFail()->user_id;
                $notif->order_id = VendorOrder::where('order_number','=',$slug)->where('user_id','=',$user->id)->firstOrFail()->order_id;
                $notif->is_customer = '1';
                $notif->header = 'Your Order is '.ucwords( strtolower($status) );
                $notif->messages = 'Order with #'.$slug.' is '.ucwords( strtolower($status) );

                $field = 'proccess_order';

                if ($status == 'on delivery') {
                    $field = 'delivery_order';
                }

                $notifAdmin = new Notification;
                $notifAdmin->order_id = Order::where('order_number', $slug)->firstOrFail()->id;
                $notifAdmin->$field = '1';

                $notif->save();
                $notifAdmin->save();
                return redirect()->route('vendor-order-index')->with('success','Berhasil Mengubah Status Pesanan');
            }

            return redirect()->route('vendor-order-index')->with('unsuccess','No Resi Tidak Boleh Kosong');            
        }
    }

}
