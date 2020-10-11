<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Product;
use App\Models\UserSubscription;
use App\Models\Subscriber;
use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\Generalsetting;
use App\Classes\GeniusMailer;
use Illuminate\Support\Facades\Log;
use DB;

class GvController extends Controller
{
    
    public function __construct(Request $request)
    {       
		$this->allowed_ips = array(
            '202.53.227.187',
        );
        $this->secret_key                = "123456";
        $this->biller_name 				 = "MAJAPAHIT";
        $this->ipaddress 				 = $request->ip();
    }
    
    public function callback(Request $request)
    {
        // if (!in_array($this->ipaddress, $this->allowed_ips)) {
        //     $response = array(
        //         'error' => 'Unauthorized'
        //     );

        //     return response()->json($response, 401);
        // }
        if ($request->isMethod('post')) {
            $input = $request->all();

            if ($input == null || $input == "" || !isset($input['data'])) {
                return response()->json(['error'=>'Bad Request'], 400); 
            }


            try {
                $xml = simplexml_load_string($input['data']);           
                $invoiceNumber = $xml->custom;                
            } catch (Exception $e) {
                return response()->json(['error'=> 'Message: ' .$e->getMessage()], 400);
            }
            
            $invoice = Invoice::where('invoice_number', $invoiceNumber)->where('status', 'belum dibayar')->first();
            if (($invoice <> '' && $invoice->is_subscription == '0') && $xml->status == 'SUCCESS' && $xml->merchant_id == '777') {
                $invoice->status = 'sudah dibayar';

                DB::beginTransaction();

                try {
                    Order::where('invoice_id', $invoice->id)->where('method', 'Bank Transfer')->update(['payment_status' => 'Paid', 'pay_id' => $xml->reference]);
                    $invoice->save();
                    $notif = new Notification;
                    $notif->user_id = $invoice->user_id;
                    $notif->header = 'Pembayaran Sukses';
                    $notif->messages = 'Pembayaran Anda Telah Kami Terima #'.$invoiceNumber;
                    $notif->is_customer = '1';
                    $notif->invoice_id = $invoice->id;

                    $notif->save();

                    $orders = Order::where('invoice_id', $invoice->id)->where('payment_status', 'Paid')->get();

                    foreach ($orders as $k => $order) {
                        $notifVendor = new Notification;
                        $notifVendor->user_id = $order->vendororders[0]->user_id;
                        $notifVendor->paid_order = '1';
                        $notifVendor->order_id = $order->id;
                        $notifVendor->save();

                        $cart[$k] = unserialize(bzdecompress(utf8_decode($order->cart)));

                        foreach ($cart[$k]->items as $prod) {
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
                
                        foreach ($cart[$k]->items as $prod) {
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

                        $userNotification = UserNotification::where('order_number', $order->order_number)->first();

                        if ($userNotification <> '') {
                            $userNotification->user_id = $order->vendororders[0]->user_id;
                            $userNotification->is_read = 0;
                            $userNotification->save();
                        }
                        
                    }

                    
                    DB::commit();

                    $gs = Generalsetting::find(1);
                    if ($gs->is_smtp == 1) {
                        $mailData = [
                            'to' => $invoice->user->email,
                            'cname' => $invoice->user->name,
                        ];
            
                        // dd($mailData);
                        $mailer = new GeniusMailer();
                        $mailer->sendSuccessPayment($mailData, ['invoice' => $invoice->invoice_number, 'name' => $invoice->user->name]);                        
                    }

                    return response()->json(['success'=>'Success'], 200);
                } catch (Exception $e) {
                    DB::rollback();

                    return response()->json(['error'=>'Bad Request'], 400);
                }
            }elseif(($invoice <> '' && $invoice->is_subscription == '1') && $xml->status == 'SUCCESS' && $xml->merchant_id == '777'){
                $user = User::find($invoice->user_id);
                $userSubs = UserSubscription::where('txnid', $invoiceNumber)->where('method', 'GudangVoucher')->where('user_id', $user->id)->firstOrFail();

                $invoice->status = 'sudah dibayar';
                // $userSubs->status = 1;
                $userSubs->payment_number = $xml->reference;
                // $user->is_vendor = 2;

                // $subscriber = new Subscriber;
                // $subscriber->email = $user->email;

                DB::beginTransaction();

                try {
                    $invoice->save();
                    $userSubs->save();
                    // $user->save();
                    // $subscriber->save();
                    
                    $notif = new Notification;
                    $notif->user_id = $invoice->user_id;
                    $notif->header = 'Pembayaran Sukses';
                    $notif->messages = 'Pembayaran Anda Telah Kami Terima, Silahkan Tunggu Verifikasi dari Admin.';
                    $notif->is_customer = '1';
                    $notif->invoice_id = $invoice->id;
                    $notif->save();

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