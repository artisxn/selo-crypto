<?php

namespace App\Http\Controllers\Admin;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\User;
use App\Models\VendorOrder;
use App\Models\Product;
use Datatables;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->gs = DB::table('generalsettings')->find(1);

        // dd( $this->feeVendor(20000000) + ($this->feeCompany(20000000) - $this->feeDropshipper(20000000)) + $this->feeDropshipper(20000000) );
    }

    //*** JSON Request
    public function datatables($status)
    {        
        if($status == 'pending'){
            $datas = Order::where('status','=','pending')->orderBy('created_at','desc')->get();
        }
        elseif($status == 'processing') {
            $datas = Order::where('status','=','processing')
            ->orWhere('status','=','on delivery')
            ->orderBy('created_at','desc')
            ->get();
        }
        elseif($status == 'completed') {
            $datas = Order::where('status','=','completed')->orderBy('created_at','desc')->get();
        }
        elseif($status == 'declined') {
            $datas = Order::where('status','=','declined')->orderBy('created_at','desc')->get();
        }
        elseif($status == 'paid') {
            $datas = Order::where('payment_status','=','Paid')->orderBy('created_at','desc')->get();
        }elseif($status == 'vendor-payment') {
            $datas = Order::where('payment_status','=','Paid')->where('status','=','completed')->where('vendor_payment','=','0')->orderBy('created_at','desc')->get();
        }elseif($status == 'dropshipper-payment') {
            $datas = Order::where('payment_status','=','Paid')->where('status','=','completed')->where('dropshipper_payment', '0')->orderBy('created_at','desc')->get();
        }        
        else{
          $datas = Order::orderBy('created_at','desc')->get();  
        }


        $datas = new Collection($datas);


         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('id', function(Order $data) {
                                $id = '<a href="'.route('admin-order-invoice',$data->id).'">'.$data->order_number.'</a>';
                                return $id;
                            })


                            ->editColumn('tanggal_proses', function(Order $data) {
                                @$proccessing_date = DB::table('notifications')
                                ->where('order_id',$data->id)
                                ->where('proccess_order',1)
                                ->first();
                                // $id = '<a href="'.route('admin-order-invoice',$data->id).'">'.$data->order_number.'</a>';
                                return @$proccessing_date->created_at;
                            })

                            ->editColumn('tanggal_kirim', function(Order $data) {
                                @$delivery_order_date = DB::table('notifications')
                                ->where('order_id',$data->id)
                                ->where('delivery_order',1)
                                ->first();
                                // $id = '<a href="'.route('admin-order-invoice',$data->id).'">'.$data->order_number.'</a>';
                                return @$delivery_order_date->created_at;
                            })

                            ->editColumn('tanggal_terima', function(Order $data) {
                                @$tanggal_terima = DB::table('notifications')
                                ->where('order_id',$data->id)
                                ->where('complete_order',"1")
                                ->first();
                                // $id = '<a href="'.route('admin-order-invoice',$data->id).'">'.$data->order_number.'</a>';
                                return @$tanggal_terima->created_at;
                            })


                            ->editColumn('tanggal_tolak', function(Order $data) {
                                @$tanggal_tolak = DB::table('notifications')
                                ->where('order_id',$data->id)
                                // ->where('proccess_order',1)
                                // ->orWhere('proccess_order',NULL)

                                ->orderBy('created_at', 'desc')
                                ->first();
                                // $id = '<a href="'.route('admin-order-invoice',$data->id).'">'.$data->order_number.'</a>';
                                return @$tanggal_tolak->created_at;
                            })





                            ->editColumn('pay_amount', function(Order $data) {
                                return $data->currency_sign . $this->rp(round($data->pay_amount * $data->currency_value , 2));
                            })
                            ->addColumn('action', function(Order $data) {
                                $orders = '<a href="javascript:;" data-href="'. route('admin-order-edit',$data->id) .'" class="delivery" data-toggle="modal" data-target="#modal1"><i class="fas fa-dollar-sign"></i> Delivery Status</a>';
                                return '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button><div class="action-list"><a href="' . route('admin-order-show',$data->id) . '" > <i class="fas fa-eye"></i> Details</a><a href="javascript:;" class="send" data-email="'. $data->customer_email .'" data-toggle="modal" data-target="#vendorform"><i class="fas fa-envelope"></i> Send</a></div></div>';
                            })
                            ->addColumn('fee_company', function(Order $data){
                                $feeSeller = 0;
                                $feeCompany = 0;
                                $feeDropshipper = 0;
                                $cart = unserialize(bzdecompress(utf8_decode($data->cart)));



                                foreach($cart->items as $product){
                                    // $item = Product::where('id','=', $product['item']['id'])->firstOrFail();
                                    
                                    


                                    if ($product['is_dropship'] === true) {
                                        $feeDropshipper += $this->feeDropshipper($product['price']);                                     
                                        $feeCompany += ($this->feeCompany($product['price']) - $this->feeDropshipper($product['price']) );
                                    }else{
                                        $feeCompany += $this->feeCompany($product['price']);
                                    }
                                    // $feeDropshipper += $feeCompany * ($this->gs->rate_dropship / 100);
                                }

                                // $feeSeller = $data->pay_amount - $feeCompany;

                                $feeCompany = $feeCompany;
                                

                                return $data->currency_sign . $this->rp($feeCompany);
                            })
                            ->addColumn('fee_seller', function(Order $data) use($status){
                                // dd($data);
                                $feeSeller = 0;
                                $feeCompany = 0;
                                $cart = unserialize(bzdecompress(utf8_decode($data->cart)));

                                foreach($cart->items as $product){
                                    // $item = Product::where('id','=', $product['item']['id'])->firstOrFail();
                                    // $feeCompany += ($data->method === 'EDCCASH' ? (isset($product['price_edc']) ? $product['price_edc'] : $product['price']) : $product['price']) * ($this->gs->rate_company / 100);
                                    
                                    //$feeCompany += $product['price'] * ($this->gs->rate_company / 100);
                                    $feeSeller += $this->feeVendor($product['price']);
                                }

                                // $feeSeller = $data->pay_amount - $feeCompany;

                                if ($status == 'vendor-payment') {
                                    $feeSeller += $data->shipping_cost;
                                }
                                return $data->currency_sign . $this->rp($feeSeller);                                
                            })
                            ->addColumn('fee_vendor', function(Order $data) {
                                $feeSeller = 0;
                                $feeCompany = 0;
                                $cart = unserialize(bzdecompress(utf8_decode($data->cart)));

                                foreach($cart->items as $product){
                                    // $item = Product::where('id','=', $product['item']['id'])->firstOrFail();
                                    // $feeCompany += ($data->method === 'EDCCASH' ? (isset($product['price_edc']) ? $product['price_edc'] : $product['price']) : $product['price']) * ($this->gs->rate_company / 100);
                                    
                                    //$feeCompany += $product['price'] * ($this->gs->rate_company / 100);
                                    $feeSeller += $this->feeVendor($product['price']);
                                }

                                // $feeSeller = $data->pay_amount - $feeCompany;

                                // if ($status == 'vendor-payment') {
                                //     $feeSeller += $data->shipping_cost;
                                // }
                                return $data->currency_sign . $this->rp($feeSeller);  
                            })
                            ->addColumn('tax', function(Order $data){
                                $cart = unserialize(bzdecompress(utf8_decode($data->cart)));
                                $belanja = 0;

                                $tax = (int) $data->tax / 100;

                                

                                foreach($cart->items as $product){
                                    // $item = Product::where('id','=', $product['item']['id'])->firstOrFail();
                                    // $feeCompany += ($data->method === 'EDCCASH' ? (isset($product['price_edc']) ? $product['price_edc'] : $product['price']) : $product['price']) * ($this->gs->rate_company / 100);
                                    
                                    //$feeCompany += $product['price'] * ($this->gs->rate_company / 100);
                                    $belanja += $product['price'];
                                
                                }

                                $totalTax = $belanja * $tax;

                                return  $data->currency_sign . $this->rp($totalTax);
                            })
                            ->addColumn('toko_name', function(Order $data){
                                $cart = unserialize(bzdecompress(utf8_decode($data->cart)));

                                $tokoName = isset($cart->items[0]['toko_name']) ? $cart->items[0]['toko_name'] : '-';

                                return $tokoName;
                            })
                            ->addColumn('pay_vendor', function(Order $data){
                                if ($data->vendor_payment <> '1') {
                                    $button = '<button type="button" class="btn btn-info btn-payment" data-toggle="modal" data-target="#notf" data-order="'.base64_encode($data->order_number).'">Pay To Vendor</button>';
                                }else{
                                    $button = '<div><i class="far fa-check-circle fa-2x"></i></div>';
                                }
                                

                                return $button;
                            })
                            ->addColumn('pay_dropship', function(Order $data){
                                if ($data->dropshipper_payment === '1') {
                                    $button = '<div><i class="far fa-check-circle"></i></div>';
                                }else{
                                    $button = '<div><i class="far fa-times-circle"></i></div>';
                                }
                                

                                return $button;
                            })                            
                            ->addColumn('dropship', function(Order $data){
                                if ($data->is_dropship === '1') {
                                    $button = '<div><i class="far fa-check-circle"></i></div>';
                                }else{
                                    $button = '<div><i class="far fa-times-circle"></i></div>';
                                }

                                return $button;
                            })
                            ->addColumn('shipping_cost', function(Order $data){
                                return $data->currency_sign . $this->rp($data->shipping_cost);
                            })
                            ->addColumn('fee_dropshipper', function(Order $data){
                                $feeSeller = 0;
                                $feeCompany = 0;
                                $feeDropshipper = 0;
                                $cart = unserialize(bzdecompress(utf8_decode($data->cart)));

                                // dd($cart);

                                foreach($cart->items as $product){
                                    // $item = Product::where('id','=', $product['item']['id'])->firstOrFail();
                                    
                                    // $feeCompany += $product['price'] * ($this->gs->rate_company / 100);


                                    if ($product['is_dropship'] === true) {
                                        $feeDropshipper += $this->feeDropshipper($product['price']); 
                                    }

                                    // $feeDropshipper += $feeCompany * ($this->gs->rate_dropship / 100);
                                }

                                // $feeSeller = $data->pay_amount - $feeCompany;

                                return $data->currency_sign . $this->rp($feeDropshipper);
                                
                            })
                            ->addColumn('pay_dropshipper', function(Order $data){
                                // $feeSeller = 0;
                                $feeCompany = 0;
                                $feeDropshipper = 0;
                                $cart = unserialize(bzdecompress(utf8_decode($data->cart)));

                                foreach($cart->items as $product){
                                    // $item = Product::where('id','=', $product['item']['id'])->firstOrFail();
                                    
                                    $feeCompany += $product['price'] * ($this->gs->rate_company / 100);


                                    if ($product['is_dropship'] === true) {
                                        $feeDropshipper += $this->feeDropshipper($product['price']); 
                                    }

                                    // $feeDropshipper += $feeCompany * ($this->gs->rate_dropship / 100);
                                }

                                // $feeSeller = $data->pay_amount - $feeCompany;

                                if ($feeDropshipper > 0 && $data->dropshipper_payment <> '1') {
                                    $button = '<button type="button" class="btn btn-info btn-dropshipper" data-toggle="modal" data-target="#notf-dropshipper" data-order="'.base64_encode($data->order_number).'">Pay To Dropshipper</button>';

                                    return $button;
                                }
                                // else{
                                //     $button = '<div><i class="far fa-times-circle"></i></div>';
                                // }
                                

                                
                            })->addColumn('rek_vendor', function(Order $data){
                                $cart = unserialize(bzdecompress(utf8_decode($data->cart)));
                                
                                // $vendor = User::where('slug_shop_name', $cart->items[0]['slug_toko'])->where('is_vendor', 2)->first();
                                $vendor = $data->vendororders[0]->user;

                                if ($vendor <> '') {
                                    return $vendor->rekening_no.'/'.$vendor->rekening_name.' - '.$vendor->bank.'/'.$vendor->cabang;
                                }

                                return '-';
                            })->addColumn('rek_dropship', function(Order $data){
                                $cart = unserialize(bzdecompress(utf8_decode($data->cart)));
                                
                                if ($cart->items[0]['is_dropship'] === true) {
                                    $vendor = User::where('slug_shop_name', $cart->items[0]['vendor_slug_shop_name'])->where('is_vendor', 2)->first();
                                
                                    if ($vendor <> '') {
                                    return $vendor->rekening_no.'/'.$vendor->rekening_name.' - '.$vendor->bank.'/'.$vendor->cabang;
                                }


                                }
                                
                                return '-';

                                
                            })  
                            ->rawColumns(['id','action', 'pay_vendor', 'dropship', 'pay_dropshipper', 'pay_dropship'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }
    public function index()
    {
        return view('admin.order.index');
    }

    public function edit($id)
    {
        $data = Order::find($id);
        return view('admin.order.delivery',compact('data'));
    }


    //*** POST Request
    public function update(Request $request, $id)
    {

        //--- Logic Section
        $data = Order::findOrFail($id);

        $input = $request->all();
        if ($data->status == "completed"){

        // Then Save Without Changing it.
            $input['status'] = "completed";
            $data->update($input);
            //--- Logic Section Ends
    

        //--- Redirect Section          
        $msg = 'Status Updated Successfully.';
        return response()->json($msg);    
        //--- Redirect Section Ends     

    
            }else{
            if ($input['status'] == "completed"){
    
                foreach($data->vendororders as $vorder)
                {
                    $uprice = User::findOrFail($vorder->user_id);
                    $uprice->current_balance = $uprice->current_balance + $vorder->price;
                    $uprice->update();
                }
    
                $gs = Generalsetting::findOrFail(1);
                if($gs->is_smtp == 1)
                {
                    $maildata = [
                        'to' => $data->customer_email,
                        'subject' => 'Your order '.$data->order_number.' is Confirmed!',
                        'body' => "Hello ".$data->customer_name.","."\n Thank you for shopping with us. We are looking forward to your next visit.",
                    ];
    
                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($maildata);                
                }
                else
                {
                   $to = $data->customer_email;
                   $subject = 'Your order '.$data->order_number.' is Confirmed!';
                   $msg = "Hello ".$data->customer_name.","."\n Thank you for shopping with us. We are looking forward to your next visit.";
                $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                   mail($to,$subject,$msg,$headers);                
                }
            }
            if ($input['status'] == "declined"){
                $gs = Generalsetting::findOrFail(1);
                if($gs->is_smtp == 1)
                {
                    $maildata = [
                        'to' => $data->customer_email,
                        'subject' => 'Your order '.$data->order_number.' is Declined!',
                        'body' => "Hello ".$data->customer_name.","."\n We are sorry for the inconvenience caused. We are looking forward to your next visit.",
                    ];
                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($maildata);
                }
                else
                {
                   $to = $data->customer_email;
                   $subject = 'Your order '.$data->order_number.' is Declined!';
                   $msg = "Hello ".$data->customer_name.","."\n We are sorry for the inconvenience caused. We are looking forward to your next visit.";
                   $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                   mail($to,$subject,$msg,$headers);
                }
    
            }

            $data->update($input);

            if($request->track_text)
            {
                    $title = ucwords($request->status);
                    $ck = OrderTrack::where('order_id','=',$id)->where('title','=',$title)->first();
                    if($ck){
                        $ck->order_id = $id;
                        $ck->title = $title;
                        $ck->text = $request->track_text;
                        $ck->update();  
                    }
                    else {
                        $data = new OrderTrack;
                        $data->order_id = $id;
                        $data->title = $title;
                        $data->text = $request->track_text;
                        $data->save();            
                    }
    
    
            } 


        $order = VendorOrder::where('order_id','=',$id)->update(['status' => $input['status']]);

         //--- Redirect Section          
         $msg = 'Status Updated Successfully.';
         return response()->json($msg);    
         //--- Redirect Section Ends    
    
            }



        //--- Redirect Section          
        $msg = 'Status Updated Successfully.';
        return response()->json($msg);    
        //--- Redirect Section Ends  


    }



    public function pending()
    {
        return view('admin.order.pending');
    }
    public function processing()
    {
        return view('admin.order.processing');
    }
    public function completed()
    {
        return view('admin.order.completed');
    }
    public function paid()
    {
        return view('admin.order.paid');
    }    
    public function declined()
    {
        return view('admin.order.declined');
    }

    public function vendorPayment()
    {
        return view('admin.order.vendor-payment');
    }

    public function dropshipPayment()
    {
        $orders = Order::where('status', '=', 'completed')->orderBy('payment_dropshipper_date', 'desc')->get();
        return view('admin.order.dropship-payment', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::find($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));

        $slugDropshipper = [];
        $dropshippers = [];

        // dd($cart->items);
        foreach ($cart->items as  $product) {
            if ($product['is_dropship'] !== FALSE) {
                $slugDropshipper[] = $product['item']['dropshipper_id'];
            }
        }

        foreach ($slugDropshipper as $key => $toko) {
            $user = User::where('id', $toko)
                            ->where('is_vendor', 2)
                            ->firstOrFail();
            if ($user->subscribes->is_dropship === '1') {
                $dropshippers[] = $user;    
            }
        }

        return view('admin.order.details',compact('order','cart','dropshippers'));
    }
    public function invoice($id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('admin.order.invoice',compact('order','cart'));
    }
    public function emailsub(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);
        if($gs->is_smtp == 1)
        {
            $data = 0;
            $datas = [
                    'to' => $request->to,
                    'subject' => $request->subject,
                    'body' => $request->message,
            ];

            $mailer = new GeniusMailer();
            $mail = $mailer->sendCustomMail($datas);
            if($mail) {
                $data = 1;
            }
        }
        else
        {
            $data = 0;
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            $mail = mail($request->to,$request->subject,$request->message,$headers);
            if($mail) {
                $data = 1;
            }
        }

        return response()->json($data);
    }

    public function printpage($id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('admin.order.print',compact('order','cart'));
    }

    public function license(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        $cart->items[$request->license_key]['license'] = $request->license;
        $order->cart = utf8_encode(bzcompress(serialize($cart), 9));
        $order->update();       
        $msg = 'Successfully Changed The License Key.';
        return response()->json($msg);
    }

    public function status($id,$status)
    {
        $mainorder = Order::findOrFail($id);

    }
}