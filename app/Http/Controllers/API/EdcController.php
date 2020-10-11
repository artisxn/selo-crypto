<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use DB;

class EdcController extends Controller
{
    public function __construct(Request $request)
    {       
		$this->allowed_ips = array(
            '202.53.227.187',
            '::1',
        );
        $this->secret_key                = "123456";
        $this->biller_name 				 = "MAJAPAHIT";
        $this->ipaddress 				 = $request->ip();
    }


    public function callbackPaymentVendor(Request $request)
    {
        if (!in_array($this->ipaddress, $this->allowed_ips)) {
            $response = array(
                'error' => 'Unauthorized'
            );

            return response()->json($response, 401);
        }        

        if ($request->isMethod('post')) {
            $input = $request->all();

            if (!in_array(null, $input)) {
                $invNumber = $request->invoiceNo;
                $orderNumber = $request->orderNumber;
                $reference = $request->trxId;
                $admin = $request->userAdmin;
                $desc = $request->desc;

                $order = Order::where('order_number', $orderNumber)
                        ->where('is_dropship', '0')
                        ->where('vendor_payment', '=', '0')
                        ->where('status', '=', 'completed')
                        ->firstOrFail();

                if ($order->invoice->invoice_number == $invNumber) {
                    DB::beginTransaction();

                    try {
                        $order->vendor_payment = '1';
                        $order->payment_vendor_reference = $reference;
                        $order->payment_vendor_admin = $admin;
                        $order->payment_vendor_date = date('Y-m-d H:i:s');

                        $notif = new Notification;
                        $notif->order_id = $order->id;
                        $notif->user_id = $order->vendororders[0]->user_id;
                        $notif->pembayaran_vendor = '1';

                        $order->save();
                        $notif->save();

                        DB::commit();
                        return response()->json(['success' => 'Successfully sent notification to vendor'], 200);
                    } catch (\Throwable $th) {
                        DB::rollback();
                        return response()->json(['error' => 'Something Wrong! Please try it again.'], 500);
                    }
                }else{
                    return response()->json(['error'=>'Order Not Found'], 200);     
                }
                
            }else{
                return response()->json(['error'=>'Bad Request'], 400); 
            }
        }

        return response()->json(['error'=>'Method Not Allowed'], 405);
    }

    public function callbackPaymentDropshipper(Request $request)
    {
        if (!in_array($this->ipaddress, $this->allowed_ips)) {
            $response = array(
                'error' => 'Unauthorized'
            );

            return response()->json($response, 401);
        }        

        if ($request->isMethod('post')) {
            $input = $request->all();

            if (!in_array(null, $input)) {
                $invNumber = $request->invoiceNo;
                $orderNumber = $request->orderNumber;
                $reference = $request->trxId;
                $admin = $request->userAdmin;
                $desc = $request->desc;

                $order = Order::where('order_number', $orderNumber)
                        ->where('is_dropship', '1')
                        ->where('dropshipper_payment', '=', '0')
                        ->where('status', '=', 'completed')
                        ->firstOrFail();

                if ($order->invoice->invoice_number == $invNumber) {
                    DB::beginTransaction();

                    try {
                        $order->dropshipper_payment = '1';
                        $order->payment_dropshipper_reference = $reference;
                        $order->payment_dropshipper_admin = $admin;
                        $order->payment_dropshipper_date = date('Y-m-d H:i:s');

                        $notif = new Notification;
                        $notif->order_id = $order->id;
                        $notif->user_id = $order->vendororders[0]->user_id;
                        $notif->pembayaran_vendor_dropshipper = '1';

                        $order->save();
                        $notif->save();

                        DB::commit();
                        return response()->json(['success' => 'Successfully sent notification to vendor'], 200);
                    } catch (\Throwable $th) {
                        DB::rollback();
                        return response()->json(['error' => 'Something Wrong! Please try it again.'], 500);
                    }
                }else{
                    return response()->json(['error'=>'Order Not Found'], 200);     
                }
                
            }else{
                return response()->json(['error'=>'Bad Request'], 400); 
            }
        }

        return response()->json(['error'=>'Method Not Allowed'], 405);
    }

    public function callbackPaymentEdcCash(Request $request)
    {
        // if (!in_array($this->ipaddress, $this->allowed_ips)) {
        //     $response = array(
        //         'error' => 'Unauthorized'
        //     );

        //     return response()->json($response, 401);
        // }
        if ($request->isMethod('post')) {
            $input = $request->all();

            if (in_array(null, $input) || in_array('', $input)) {
                return response()->json(['error'=>'Bad Request'], 400); 
            }


            try {
                $invoiceNumber = $request->idtransaksi;
            } catch (Exception $e) {
                return response()->json(['error'=> 'Message: ' .$e->getMessage()], 400);
            }
            
            // $invoice = Invoice::where('invoice_number', $invoiceNumber)->where('status', 'belum dibayar')->first();
            $order = Order::where('order_number', $invoiceNumber)->where('payment_status', 'Pending')->first();
            if (($order <> '') && $request->status == 'SUCCESS' && $request->merchant_id === '8632') {
                // $invoice->status = 'sudah dibayar';

                DB::beginTransaction();

                try {
                    Order::where('order_number', $invoiceNumber)->update(['payment_status' => 'Paid', 'pay_id' => $request->reference]);
                    // $invoice->save();
                    $notif = new Notification;
                    $notif->user_id = $order->user_id;
                    $notif->header = 'Pembayaran Sukses';
                    $notif->messages = 'Pembayaran Anda Telah Kami Terima untuk Nomor Order#'.$invoiceNumber;
                    $notif->is_customer = '1';
                    // $notif->invoice_id = $invoice->id;

                    $notif->save();

                    $orders = Order::where('order_number', $order->id)->where('payment_status', 'Paid')->get();

                    foreach ($orders as $order) {
                        $notifVendor = new Notification;
                        $notifVendor->user_id = $order->vendororders[0]->user_id;
                        $notifVendor->paid_order = '1';
                        $notifVendor->order_id = $order->id;
                        $notifVendor->save();

                        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));

                        foreach ($cart->items as $prod) {
                            $x = (string) $prod['size_qty'];
                            if (!empty($x)) {
                                $product = Product::findOrFail($prod['item']['id']);
                                $x = (int) $x;
                                $x = $x - $prod['qty'];
                                $temp = $product->size_qty;
                                $temp[$prod['size_key']] = $x;
                                $temp1 = implode(',', $temp);
                                $product->size_qty = $temp1;
                                $product->update();
                            }
                        }
                
                        foreach ($cart->items as $prod) {
                            $x = (string) $prod['stock'];
                            if ($x != null) {
                
                                $product = Product::findOrFail($prod['item']['id']);
                                $product->stock = $prod['stock'];
                                $product->update();
                                if ($product->stock <= 5) {
                                    $notification = new Notification;
                                    $notification->product_id = $product->id;
                                    $notification->save();
                                }
                            }
                        }
                    }

                    
                    DB::commit();
                    return response()->json(['success'=>'Success'], 200);
                } catch (Exception $e) {
                    DB::rollback();

                    return response()->json(['error'=>'Bad Request'], 400);
                }
            }else{
                return response()->json(['error'=>'No data found'], 200);     
            }
            
        }else{
            return response()->json(['error'=>'Method Not Allowed'], 405); 
        }        
    }
}
