<?php

namespace App\Http\Controllers\Front;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\PaymentGateway;
use App\Models\Pickup;
use App\Models\Product;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\VendorOrder;
use App\Models\Regencies;
use App\Models\Districts;
use App\Models\Provinces;
use App\Models\Address;
use Auth;
use DB;
use Illuminate\Http\Request;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use Session;
use Validator;
use Illuminate\Support\Facades\Hash;

class CheckoutController extends Controller
{
    public function test_alamat()
    {
        if (Session::has('cart')) {
            dd(Session::get('cart'));
        } else {
            echo "kosong";
        }
    }
    public function loadpayment($slug1, $slug2)
    {
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        $payment = $slug1;
        $pay_id = $slug2;
        $gateway = '';
        if ($pay_id != 0) {
            $gateway = PaymentGateway::findOrFail($pay_id);
        }
        return view('load.payment', compact('payment', 'pay_id', 'gateway', 'curr'));
    }

    public function checkkurir(Request $request)
    {
        $kurir = $request->id;
        $berat = $request->berat;
        $toko = $request->toko;
        $origin = User::where('slug_shop_name', $toko)->first();


        $kota_pengiriman = $this->cari_kota( trim( str_replace(['KABUPATEN', 'KOTA'], '', $request->kota) ) );
        $kota_vendor = $this->cari_kota( trim( str_replace(['KABUPATEN', 'KOTA'], '', $origin->regency->name) ) );

        if ($kota_pengiriman == 0) {
            return "gagal";
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "originType=city&destinationType=city&origin=" . $kota_vendor . "&destination=" . $kota_pengiriman . "&weight=" . $berat . "&courier=" . $kurir,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: a46c2673d8d088eb2aca104481c62806",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    private function cari_kota($kota)
    {
        $destination['city_id'] = 0;
        $substrkota = substr($kota, 0, 3);
        $lists = RajaOngkir::kota()->search($substrkota)->get();
        if ($lists) {
            foreach ($lists as $key => $value) {
                if (strtolower($value['city_name']) == strtolower($kota)) {
                    $destination = $value;
                }
            }
        }
        return $destination['city_id'];
    }

    public function checkout(Request $request)
    {
        $this->code_image();
        $provinces = DB::table('provinces')->orderBy('name', 'asc')->get();
        $regencies = DB::table('regencies')->orderBy('name', 'asc')->get();
        $districts = DB::table('districts')->orderBy('name', 'asc')->get();
        $queryString = $request->query('id') != '' ? $request->query('id') : null;

        $user = '';
        $user = Auth::user();
        
        $alamat = "";
        $alamatLainnya = '';
        $alamats = [];
        $kecamatan = "";
        $kota = "";
        if ($user) {
            $alamat .= $user->address;
            $kecamatan = ($user->district !== null ? $user->district->name : '');
            $kota = ($user->regency <> '' ? $user->regency->name : '');
            $alamat .= ", ".$kecamatan;
            $alamat .= ", ".$kota;
            $alamat .= " ".$user->zip;

            $alamatLainnya = Auth::user()->addresses;

            if ($alamatLainnya <> '') {
                foreach ($alamatLainnya as $key =>  $value) {
                    // if ($value->id <> 0) {
                        $alamats[$value->id]['alamat'] = $value->address_name;
                        // $alamats[$value->id]['alamat'] .= " - ".$value->receiver;
                        $alamats[$value->id]['alamat'] .= " - ".$value->address;
                        $alamats[$value->id]['alamat'] .= ", ".$value->regency->name;
                        $alamats[$value->id]['alamat'] .= ", ".$value->zip;
                        $alamats[$value->id]['kota'] = $value->regency->name;
                        $alamats[$value->id]['nama'] = $value->receiver;
                        $alamats[$value->id]['phone'] = $value->phone;
                    // }
                }
            }
        }



        if ($queryString !== null) {
            $invoice = Invoice::where('invoice_number', base64_decode($queryString))->first();
            
            if(Session::has('tempcart')){
                $oldCart = Session::get('tempcart');
                $tempcart = new Cart($oldCart);
                $order = Session::get('temporder');
                $checkoutPaymentMethod = Session::get('checkout_payment_method');
            }else{
                $checkoutPaymentMethod = '';
            }

            // $order = Order::where('invoice_id', $invoice->id)->get();
            // $totalOngkir = $order->sum('shipping_cost');
            // $totalOrder = $order->sum('pay_amount');
            // $totalBayar = (int) $totalOngkir + (int) $totalOrder;
            // $totalProduct = $order->pluck('order_number');

            // $total = [
            //     'totalOngkir' => $totalOngkir,
            //     'totalOrder' => $totalOrder - $totalOngkir,
            //     'totalBayar' => $totalOrder
            // ];


            // if($checkoutPaymentMethod === 'Bank Transfer'){
            //     $urlGV = "https://www.gudangvoucher.com/pg/v3/payment-sandbox.php";
            //     $urlGV .= "?merchantid=".urlencode(777)."&";
            //     $urlGV .= "amount=".urlencode($totalOrder)."&";
            //     $urlGV .= "product=".urlencode(implode(',', $totalProduct->toArray()))."&";
            //     $urlGV .= "custom=".urlencode(base64_decode($request->query('id')))."&";
            //     $urlGV .= "email=".urlencode(Auth::user()->email);
        
            //     $ch = curl_init();
            
            //     curl_setopt($ch, CURLOPT_URL, $urlGV);
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //     curl_setopt($ch, CURLOPT_HEADER, FALSE);
            //     $response = curl_exec($ch);
            //     curl_close($ch);
            // }
        }else{
            $tab_active = "disabled";
            $invoice = false;
            $total = false;
        }


        if (!Session::has('cart')) {
            if ($queryString !== '') {
                if ($invoice <> '') {
                    $curr = Currency::where('is_default', '=', 1)->first();

                    return view('front.checkout', ['alamat' => $alamat, 'alamatLainnya' => $alamats, 'order' => $order, 'tempcart' => $tempcart, 'query' => $queryString, 'invoice' => $invoice, 'tab_active' => $invoice <> '' ? 'active' : 'disabled', 'digital' => 0, 'pickups' => [], 'products' => [], 'gateways' => [], 'totalQty' => 0, 'vendor_shipping_id' => 0, 'vendor_packing_id' => 0, 'curr' => $curr, 'shipping_data' => [], 'package_data' => [], 'provinces' => $provinces]);                
                }else{
                    return abort(404);
                }                
            }else{
                return redirect()->route('front.cart')->with('success', "You don't have any product to checkout.");
            }
        }else{
            if ($invoice <> '') {
                $curr = Currency::where('is_default', '=', 1)->first();
                return view('front.checkout', ['alamat' => $alamat, 'alamatLainnya' => $alamats, 'order' => $order, 'tempcart' => $tempcart, 'query' => $queryString, 'invoice' => $invoice, 'tab_active' => $invoice <> '' ? 'active' : 'disabled', 'digital' => 0, 'pickups' => [], 'products' => [], 'gateways' => [], 'totalQty' => 0, 'vendor_shipping_id' => 0, 'vendor_packing_id' => 0, 'curr' => $curr, 'shipping_data' => [], 'package_data' => [], 'provinces' => $provinces]);                
            } 
        }


        $gs = Generalsetting::findOrFail(1);
        $dp = 1;
        $vendor_shipping_id = 0;
        $vendor_packing_id = 0;
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        // If a user is Authenticated then there is no problm user can go for checkout

        if (Auth::guard('web')->check()) {
            

            $gateways = PaymentGateway::where('status', '=', 1)->get();
            $pickups = Pickup::all();
            $oldCart = Session::get('cart');
            $cart = new Cart($oldCart);
            $products = $cart->items;
            
            // dd($products);
            // Shipping Method

            if ($gs->multiple_shipping == 1) {
                $user = null;
                foreach ($cart->items as $prod) {
                    $user[] = $prod['item']['user_id'];
                }
                $users = array_unique($user);
                if (count($users) == 1) {

                    $shipping_data = DB::table('shippings')->where('user_id', '=', $users[0])->get();
                    if (count($shipping_data) == 0) {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                    } else {
                        $vendor_shipping_id = $users[0];
                    }
                } else {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                }

            } else {
                $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
            }

            // Packaging

            if ($gs->multiple_packaging == 1) {
                $user = null;
                foreach ($cart->items as $prod) {
                    $user[] = $prod['item']['user_id'];
                }
                $users = array_unique($user);
                if (count($users) == 1) {
                    $package_data = DB::table('packages')->where('user_id', '=', $users[0])->get();
                    if (count($package_data) == 0) {
                        $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                    } else {
                        $vendor_packing_id = $users[0];
                    }
                } else {
                    $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                }

            } else {
                $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
            }

            foreach ($products as $prod) {
                if ($prod['item']['type'] == 'Physical') {
                    $dp = 0;
                    break;
                }
            }
            if ($dp == 1) {
                $ship = 0;
            }
            $total = $cart->totalPrice;
            $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
            if ($gs->tax != 0) {
                $tax = ($total / 100) * $gs->tax;
                $total = $total + $tax;
            }
            if (!Session::has('coupon_total')) {
                $total = $total - $coupon;
                $total = $total + 0;
            } else {
                $total = Session::get('coupon_total');
                $total = $total + round(0 * $curr->value, 2);
            }
            // $pertoko = array();
            // foreach ($cart->items as $element) {
            //     $pertoko[$element['toko_id']][] = $element;

            // }
            // $result = array();
            // foreach ($pertoko as $i => $value) {
            //     foreach($value as $single_product){
            //         //dd($single_product);
            //         //$result[$i] = $single_product;
            //         echo $single_product['toko_name'];
            //     }
            // }
            //dd($result);
            // $queryString = $request->query('id') != '' ? $request->query('id') : null;

            
            // if ($queryString != false) {
            //     $invoice = Invoice::where('invoice_number', $queryString)->first();

            //     $order = Order::where('invoice_id', $invoice->id)->get();
            //     $totalOngkir = $order->sum('shipping_cost');
            //     $totalOrder = $order->sum('pay_amount');
            //     $totalBayar = (int) $totalOngkir + (int) $totalOrder;

            //     $total = [
            //         'totalOngkir' => $totalOngkir,
            //         'totalOrder' => $totalOrder,
            //         'totalBayar' => $totalBayar
            //     ];

            // }else{
            //     $invoice = false;
            //     $total = false;
            // }

            return view('front.checkout', ['kota' => $kota, 'kecamatan' => $kecamatan, 'alamat' => $alamat, 'alamatLainnya' => $alamats, 'products' => $cart->items, 'query' => $queryString, 'total' => $total, 'invoice' => $invoice, 'tab_active' => $invoice <> '' ? 'active' : 'disabled',  'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'shipping_cost' => 0, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id, 'provinces' => $provinces, 'regencies' => $regencies, 'districts' => $districts]);
        } else {
                // If guest checkout is activated then user can go for checkout
            if ($gs->guest_checkout == 1) {
                $gateways = PaymentGateway::where('status', '=', 1)->get();
                $pickups = Pickup::all();
                $oldCart = Session::get('cart');
                $cart = new Cart($oldCart);
                $products = $cart->items;

                // Shipping Method

                if ($gs->multiple_shipping == 1) {
                    $user = null;
                    foreach ($cart->items as $prod) {
                        $user[] = $prod['item']['user_id'];
                    }
                    $users = array_unique($user);
                    if (count($users) == 1) {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', $users[0])->get();

                        if (count($shipping_data) == 0) {
                            $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                        } else {
                            $vendor_shipping_id = $users[0];
                        }
                    } else {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                    }

                } else {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                }

                // Packaging

                if ($gs->multiple_packaging == 1) {
                    $user = null;
                    foreach ($cart->items as $prod) {
                        $user[] = $prod['item']['user_id'];
                    }
                    $users = array_unique($user);
                    if (count($users) == 1) {
                        $package_data = DB::table('packages')->where('user_id', '=', $users[0])->get();

                        if (count($package_data) == 0) {
                            $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                        } else {
                            $vendor_packing_id = $users[0];
                        }
                    } else {
                        $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                    }

                } else {
                    $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                }

                foreach ($products as $prod) {
                    if ($prod['item']['type'] == 'Physical') {
                        $dp = 0;
                        break;
                    }
                }
                if ($dp == 1) {
                    $ship = 0;
                }
                $total = $cart->totalPrice;
                $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
                if ($gs->tax != 0) {
                    $tax = ($total / 100) * $gs->tax;
                    $total = $total + $tax;
                }
                if (!Session::has('coupon_total')) {
                    $total = $total - $coupon;
                    $total = $total + 0;
                } else {
                    $total = Session::get('coupon_total');
                    $total = str_replace($curr->sign, '', $total) + round(0 * $curr->value, 2);
                }
                foreach ($products as $prod) {
                    if ($prod['item']['type'] != 'Physical') {
                        if (!Auth::guard('web')->check()) {
                            $ck = 1;
                            return view('front.checkout', ['products' => $cart->items, 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'shipping_cost' => 0, 'checked' => $ck, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id]);
                        }
                    }
                }
                return view('front.checkout', ['kota' => $kota, 'kecamatan' => $kecamatan, 'alamat' => $alamat, 'alamatLainnya' => $alamats, 'products' => $cart->items, 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'shipping_cost' => 0, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id, 'tab_active' => $tab_active]);
            }

                // If guest checkout is Deactivated then display pop up form with proper error message

            else {
                $gateways = PaymentGateway::where('status', '=', 1)->get();
                $pickups = Pickup::all();
                $oldCart = Session::get('cart');
                $cart = new Cart($oldCart);
                $products = $cart->items;

                // Shipping Method

                if ($gs->multiple_shipping == 1) {
                    $user = null;
                    foreach ($cart->items as $prod) {
                        $user[] = $prod['item']['user_id'];
                    }
                    $users = array_unique($user);
                    if (count($users) == 1) {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', $users[0])->get();

                        if (count($shipping_data) == 0) {
                            $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                        } else {
                            $vendor_shipping_id = $users[0];
                        }
                    } else {
                        $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                    }

                } else {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                }

                // Packaging

                if ($gs->multiple_packaging == 1) {
                    $user = null;
                    foreach ($cart->items as $prod) {
                        $user[] = $prod['item']['user_id'];
                    }
                    $users = array_unique($user);
                    if (count($users) == 1) {
                        $package_data = DB::table('packages')->where('user_id', '=', $users[0])->get();

                        if (count($package_data) == 0) {
                            $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                        } else {
                            $vendor_packing_id = $users[0];
                        }
                    } else {
                        $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                    }

                } else {
                    $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                }

                $total = $cart->totalPrice;
                $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
                if ($gs->tax != 0) {
                    $tax = ($total / 100) * $gs->tax;
                    $total = $total + $tax;
                }
                if (!Session::has('coupon_total')) {
                    $total = $total - $coupon;
                    $total = $total + 0;
                } else {
                    $total = Session::get('coupon_total');
                    $total = $total + round(0 * $curr->value, 2);
                }
                $ck = 1;
                return view('front.checkout', ['kota' => $kota, 'kecamatan' => $kecamatan, 'alamat' => $alamat, 'alamatLainnya' => $alamats, 'products' => $cart->items, 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'shipping_cost' => 0, 'checked' => $ck, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id, 'tab_active' => $tab_active, 'provinces' => $provinces, 'regencies' => $regencies, 'districts' => $districts]);
            }
        }

    }

    public function cashondelivery(Request $request)
    {
        if ($request->pass_check) {
            $users = User::where('email', '=', $request->personal_email)->get();
            if (count($users) == 0) {
                if ($request->personal_pass == $request->personal_confirm) {
                    $user = new User;
                    $user->name = $request->personal_name;
                    $user->email = $request->personal_email;
                    $user->password = bcrypt($request->personal_pass);
                    $token = md5(time() . $request->personal_name . $request->personal_email);
                    $user->verification_link = $token;
                    $user->affilate_code = md5($request->name . $request->email);
                    $user->emai_verified = 'Yes';
                    $user->save();
                    Auth::guard('web')->login($user);
                } else {
                    return redirect()->back()->with('unsuccess', "Confirm Password Doesn't Match.");
                }
            } else {
                return redirect()->back()->with('unsuccess', "This Email Already Exist.");
            }
        }

        if (!Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', "You don't have any product to checkout.");
        }
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        $gs = Generalsetting::findOrFail(1);
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        foreach ($cart->items as $key => $prod) {
            if (!empty($prod['item']['license']) && !empty($prod['item']['license_qty'])) {
                foreach ($prod['item']['license_qty'] as $ttl => $dtl) {
                    if ($dtl != 0) {
                        $dtl--;
                        $produc = Product::findOrFail($prod['item']['id']);
                        $temp = $produc->license_qty;
                        $temp[$ttl] = $dtl;
                        $final = implode(',', $temp);
                        $produc->license_qty = $final;
                        $produc->update();
                        $temp = $produc->license;
                        $license = $temp[$ttl];
                        $oldCart = Session::has('cart') ? Session::get('cart') : null;
                        $cart = new Cart($oldCart);
                        $cart->updateLicense($prod['item']['id'], $license);
                        Session::put('cart', $cart);
                        break;
                    }
                }
            }
        }
        $order = new Order;
        $success_url = action('Front\PaymentController@payreturn');
        $item_name = $gs->title . " Order";
        $item_number = str_random(4) . time();
        $order['user_id'] = $request->user_id;
        $order['cart'] = utf8_encode(bzcompress(serialize($cart), 9));
        $order['totalQty'] = $request->totalQty;
        $order['pay_amount'] = round($request->total / $curr->value, 2);
        $order['method'] = $request->method;
        $order['shipping'] = $request->shipping;
        $order['pickup_location'] = $request->pickup_location;
        $order['customer_email'] = $request->email;
        $order['customer_name'] = $request->name;
        $order['shipping_cost'] = "$request->shipping_cost";
        $order['packing_cost'] = $request->packing_cost;
        $order['tax'] = $request->tax;
        $order['customer_phone'] = $request->phone;
        $order['order_number'] = str_random(4) . time();
        $order['customer_address'] = $request->address;
        $order['customer_country'] = $request->customer_country;
        $order['customer_city'] = $request->city;
        $order['customer_zip'] = $request->zip;
        $order['shipping_email'] = $request->shipping_email;
        $order['shipping_name'] = $request->shipping_name;
        $order['shipping_phone'] = $request->shipping_phone;
        $order['shipping_address'] = $request->shipping_address;
        $order['shipping_country'] = $request->shipping_country;
        $order['shipping_city'] = $request->shipping_city;
        $order['shipping_zip'] = $request->shipping_zip;
        $order['order_note'] = $request->order_notes;
        $order['coupon_code'] = $request->coupon_code;
        $order['coupon_discount'] = $request->coupon_discount;
        $order['dp'] = $request->dp;
        $order['payment_status'] = "Pending";
        $order['currency_sign'] = $curr->sign;
        $order['currency_value'] = $curr->value;
        $order['vendor_shipping_id'] = $request->vendor_shipping_id;
        $order['vendor_packing_id'] = $request->vendor_packing_id;
        $order['is_vendor'] = '1';

        if (Session::has('affilate')) {
            $val = $request->total / 100;
            $sub = $val * $gs->affilate_charge;
            $user = User::findOrFail(Session::get('affilate'));
            $user->affilate_income += $sub;
            $user->update();
            $order['affilate_user'] = $user->name;
            $order['affilate_charge'] = $sub;
        }
        $order->save();

        $track = new OrderTrack;
        $track->title = 'Pending';
        $track->text = 'You have successfully placed your order.';
        $track->order_id = $order->id;
        $track->save();

        $notification = new Notification;
        $notification->order_id = $order->id;
        $notification->save();
        if ($request->coupon_id != "") {
            $coupon = Coupon::findOrFail($request->coupon_id);
            $coupon->used++;
            if ($coupon->times != null) {
                $i = (int) $coupon->times;
                $i--;
                $coupon->times = (string) $i;
            }
            $coupon->update();

        }

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

        $notf = null;

        foreach ($cart->items as $prod) {
            if ($prod['item']['user_id'] != 0) {
                $vorder = new VendorOrder;
                $vorder->order_id = $order->id;
                $vorder->user_id = $prod['item']['user_id'];
                $notf[] = $prod['item']['user_id'];
                $vorder->qty = $prod['qty'];
                $vorder->price = $prod['price'];
                $vorder->order_number = $order->order_number;
                $vorder->save();
            }

        }

        if (!empty($notf)) {
            $users = array_unique($notf);
            foreach ($users as $user) {
                $notification = new UserNotification;
                $notification->user_id = $user;
                $notification->order_number = $order->order_number;
                $notification->save();
            }
        }

        Session::put('temporder', $order);
        Session::put('tempcart', $cart);

        Session::forget('cart');

        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');

        //Sending Email To Buyer

        if ($gs->is_smtp == 1) {
            $data = [
                'to' => $request->email,
                'type' => "new_order",
                'cname' => $request->name,
                'oamount' => "",
                'aname' => "",
                'aemail' => "",
                'wtitle' => "",
                'onumber' => $order->order_number,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendAutoOrderMail($data, $order->id);
        } else {
            $to = $request->email;
            $subject = "Your Order Placed!!";
            $msg = "Hello " . $request->name . "!\nYou have placed a new order.\nYour order number is " . $order->order_number . ".Please wait for your delivery. \nThank you.";
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }
        //Sending Email To Admin
        if ($gs->is_smtp == 1) {
            $data = [
                'to' => $gs->email,
                'subject' => "New Order Recieved!!",
                'body' => "Hello Admin!<br>Your store has received a new order.<br>Order Number is " . $order->order_number . ".Please login to your panel to check. <br>Thank you.",
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } else {
            $to = $gs->email;
            $subject = "New Order Recieved!!";
            $msg = "Hello Admin!\nYour store has recieved a new order.\nOrder Number is " . $order->order_number . ".Please login to your panel to check. \nThank you.";
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }

        return redirect($success_url);
    }

    public function gateway(Request $request)
    {

        $input = $request->all();

        // if ($request->method !== 'EDCCASH' && $request->method !== 'Bank Transfer') {
        //     Session::flash('unsuccess', 'Metode Pembayaran Tidak Ditemukan');
        //     return redirect()->back()->withInput();
        // }

        // if ($request->method === 'EDCCASH') {
        //     Session::flash('unsuccess', 'Metode Pembayaran Tidak Ditemukan');
        //     return redirect()->back()->withInput();
        // }

        $rules = [
                'txn_id4' => 'required',
        ];

        $messages = [
            'txn_id4.required' => 'The Transaction ID field is required.',
            'regencies.required' => 'Kota Tidak Boleh Kosong'
        ];

        $user = Auth::user();

        switch ($request->shipping) {
            case 'shipto':
                $regency = DB::table('regencies')->where('id', $user->city_id)->get()->first();
                break;
            case 'another':
                $regency = DB::table('regencies')->where('id', $request->shipping_city)->get()->first();
                break;
            default:
                $alamat = Address::where('user_id', Auth::user()->id)->where('id', $request->shipping)->first();
                if($alamat){
                    $regency = DB::table('regencies')->where('id', $alamat->city_id)->get()->first();
                }else{
                    $regency = false;
                }
                break;
        }

        if (!$regency) {
            $rules['regencies'] = 'required';
        }

        // dd($input);

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            Session::flash('unsuccess', $validator->messages()->first());
            return redirect()->back()->withInput();
        }

        if ($request->method === 'EDCCASH' && (empty($request->edccash_number) || $request->edccash_number == '' ) ) {
            Session::flash('unsuccess', 'User ID EDCCASH Tidak Ditemukan');
            return redirect()->back()->withInput();
        }

        if ($request->method === 'EDCCASH') {
            $invoiceNumber = mt_rand(5, 1000).time();
        }else{
            $invoiceNumber = base64_decode($request->gudang_voucher_reference);
        }

        if ($request->pass_check) {
            $users = User::where('email', '=', $request->personal_email)->get();
            if (count($users) == 0) {
                if ($request->personal_pass == $request->personal_confirm) {
                    $user = new User;
                    $user->name = $request->personal_name;
                    $user->email = $request->personal_email;
                    $user->password = bcrypt($request->personal_pass);
                    $token = md5(time() . $request->personal_name . $request->personal_email);
                    $user->verification_link = $token;
                    $user->affilate_code = md5($request->name . $request->email);
                    $user->email_verified = 'Yes';
                    $user->save();
                    Auth::guard('web')->login($user);
                } else {
                    return redirect()->back()->with('unsuccess', "Confirm Password Doesn't Match.");
                }
            } else {
                return redirect()->back()->with('unsuccess', "This Email Already Exist.");
            }
        }

        $gs = Generalsetting::findOrFail(1);
        if (!Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', "You don't have any product to checkout.");
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

        // dd($input);

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }

        try {
            DB::beginTransaction();
            foreach ($cart->items as $key => $prod) {
                if (!empty($prod['item']['license']) && !empty($prod['item']['license_qty'])) {
                    foreach ($prod['item']['license_qty'] as $ttl => $dtl) {
                        if ($dtl != 0) {
                            $dtl--;
                            $produc = Product::findOrFail($prod['item']['id']);
                            $temp = $produc->license_qty;
                            $temp[$ttl] = $dtl;
                            $final = implode(',', $temp);
                            $produc->license_qty = $final;
                            $produc->update();
                            $temp = $produc->license;
                            $license = $temp[$ttl];
                            $oldCart = Session::has('cart') ? Session::get('cart') : null;
                            $cart = new Cart($oldCart);
                            $cart->updateLicense($prod['item']['id'], $license);
                            Session::put('cart', $cart);
                            break;
                        }
                    }
                }
            }
            // dd();//all prduct
            //add order to database different store
    
            $total_tagihan = 0;
            $invoice_id = [];
    
            $z = 0;
            
            $grandTotal = 0;
            foreach ($input['toko_vendor'] as $toko_vendor) {
                $items = [];
                $cart_per_toko = new Cart(false);
                foreach ($cart->items as $item) {
                    //dd($single_cart['toko_name']);
                    if ($toko_vendor == ($item['is_dropship'] !== FALSE ? $item['vendor_slug_shop_name'] : $item['slug_toko'])) {
                        $items[] = $item;
                    }
                }
                $cart_per_toko->items = $items;
                $hargaTotal = 0;
                $beratTotal = 0;
                $qtyTotal = 0;
                foreach ($items as $item) {
                    $hargaTotal += $item['price'];
                    $beratTotal += $item['weight'] * $item['qty'];
                    $qtyTotal += $item['qty'];
                }
   
                // dd($input['ongkir'] );
                $ongkir = 0;
                $kurir = '';
                $paket_kurir = '';
                foreach ($input['ongkir'] as $ongkir_toko) {
                    $temp_ongkir = explode(',', $ongkir_toko);
                    // dd($temp_ongkir);
                    if ($toko_vendor == $temp_ongkir[0]) {
                        $kurir = $temp_ongkir[1];
                        $paket_kurir = $temp_ongkir[2];
                        $ongkir = $temp_ongkir[3];
                    }
                }
                
                $total_tax = 0;
                $settings = Generalsetting::findOrFail(1);

                $total_tax = (int) $hargaTotal * ( (int) $settings->tax / 100);

                // dd($total_tax);
                $total_tagihan += $hargaTotal + $ongkir + $total_tax;
                //ORDER !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                
                $order = new Order;
                $success_url = action('Front\CheckoutController@checkout');
                $item_name = $settings->title . " Order";
                $item_number = str_random(4) . time();

                $grandTotal += $hargaTotal + $ongkir + (int) $total_tax;
    
    
                $order['user_id'] = $request->user_id;
                $order['cart'] = utf8_encode(bzcompress(serialize($cart_per_toko), 9)); //
                $order['totalQty'] = $qtyTotal; //
                $order['pay_amount'] = $hargaTotal + $ongkir + (int) $total_tax; //
                $order['method'] = $request->method;
                $order['shipping'] = $request->shipping;
                $order['pickup_location'] = $request->pickup_location;
                $order['customer_email'] = $user->email;
                $order['customer_name'] = $user->name;
                $order['shipping_cost'] = $ongkir;
                $order['packing_cost'] = $request->packing_cost;
                $order['kurir'] = $kurir;
                $order['paket_kurir'] = $paket_kurir;
                $order['tax'] = $request->tax;
                $order['customer_phone'] = $user->phone;
                $order['order_number'] = str_random(4) . time();
                $order['customer_address'] = $user->address;
                $order['customer_country'] = 'Indonesia';
                $order['customer_city'] = $user->regency->name;
                $order['customer_zip'] = $user->zip;
                
                // if ($request->has('dropship')) {
                //     $shippingCity = Regencies::find($request->shipping_city);
                //     $order['shipping_email'] = null;
                //     $order['shipping_name'] = $request->shipping_name;
                //     $order['shipping_phone'] = $request->shipping_phone;
                //     $order['shipping_address'] = $request->shipping_address;
                //     $order['shipping_country'] = 'Indonesia';
                //     $order['shipping_city'] = $shippingCity->count() > 0 ? $shippingCity->name : $request->shipping_address;
                //     $order['shipping_zip'] = $request->shipping_zip;                
                // }else{
                //     $order['shipping_email'] = $user->shipping_email;
                //     $order['shipping_name'] = $user->shipping_name;
                //     $order['shipping_phone'] = $user->shipping_phone;
                //     $order['shipping_address'] = $user->shipping_address;
                //     $order['shipping_country'] = 'Indonesia';
                //     $order['shipping_city'] = $regency->name;
                //     $order['shipping_zip'] = $user->zip;
                // }

                switch ($request->shipping) {
                    case 'shipto':
                        $order['shipping_email'] = $user->email;
                        $order['shipping_name'] = $user->name;
                        $order['shipping_phone'] = $user->phone;
                        $order['shipping_address'] = $user->address;
                        $order['shipping_country'] = 'Indonesia';
                        $order['shipping_city'] = $regency->name;
                        $order['shipping_zip'] = $user->zip;                     
                        break;
                    case 'another':
                        $order['shipping_email'] = $request->shipping_email;
                        $order['shipping_name'] = $request->shipping_name;
                        $order['shipping_phone'] = $request->shipping_phone;
                        $order['shipping_address'] = $request->shipping_address;
                        $order['shipping_country'] = 'Indonesia';
                        $order['shipping_city'] = $regency->name;
                        $order['shipping_zip'] = $request->shipping_zip;                     
                        break;
                    default:
                        $order['shipping_email'] = $user->email;
                        $order['shipping_name'] = $alamat->receiver;
                        $order['shipping_phone'] = $alamat->phone;
                        $order['shipping_address'] = $alamat->address;
                        $order['shipping_country'] = 'Indonesia';
                        $order['shipping_city'] = $alamat->regency->name;
                        $order['shipping_zip'] = $alamat->zip;                     
                        break;
                }                
    
                $order['order_note'] = $request->order_notes;
                $order['txnid'] = $request->txn_id4;
                $order['coupon_code'] = $request->coupon_code;
                $order['coupon_discount'] = $request->coupon_discount;
                $order['dp'] = $request->dp;
                $order['payment_status'] = "Pending";
                $order['currency_sign'] = $curr->sign;
                $order['currency_value'] = $curr->value;
                $order['vendor_shipping_id'] = $request->vendor_shipping_id;
                $order['vendor_packing_id'] = $request->vendor_packing_id;
                if ($request->has('dropship')) {
                    $order['is_dropship'] = '1';
                }
                // $order['is_dropship']
                // if (Session::has('affilate')) {
                //     $val = $request->total / 100;
                //     $sub = $val * $gs->affilate_charge;
                //     $user = User::findOrFail(Session::get('affilate'));
                //     $user->affilate_income += $sub;
                //     $user->update();
                //     $order['affilate_user'] = $user->name;
                //     $order['affilate_charge'] = $sub;
                // }
                $order->save();
    
                $invoice_id[$z] = $order->id;
                $orderNumber[] = $order->order_number;
                $payAmount[] = $order->pay_amount;
    
                $track = new OrderTrack;
                $track->title = 'Pending';
                $track->text = 'You have successfully placed your order.';
                $track->order_id = $order->id;
                $track->save();
    
                $notification = new Notification;
                $notification->order_id = $order->id;
                $notification->save();
    
                $z++;
                
            }
    
            //Order update invoice id


            
            $user = Auth::guard('web')->user();
            $invoice = new Invoice();
            $invoice->total_tagihan = $total_tagihan;
            $invoice->invoice_number = $invoiceNumber;
            $invoice->user_id = $user->id;
            $invoice->save();
    
            $y = 0;
            foreach ($invoice_id as $d) {
                $order = Order::find($d);
                $order->invoice_id = $invoice->id;
                $order->save();
    
                $vendor = User::where('slug_shop_name', $input['toko_vendor'][$y])->where('is_vendor', 2)->firstOrFail();

                if ($request->method === 'EDCCASH' && $request->edccash_number <> '') {
                    $url = "https://edcstoreapi.edccash.com/api/payment/";

                    $header = array(
                        "Content-Type: application/x-www-form-urlencoded",
                    );
            
                    $data = array(
                        'idtransaksi' => $order->order_number,
                        'tgltransaksi' => date('Y-m-d'),
                        'desctransaksi' => 'Pembelian Via EDCSTORE',
                        'idbuyer' => $request->edccash_number,
                        'idseller' => $vendor->edccash_id,
                        'nilairupiah' => $order->pay_amount,
                        'nilaiedccash' => round($order->pay_amount / $gs->edccash_currency, 3)
                    );
            
                    $ch = curl_init();
                    
                    if ($data !== FALSE) {
                        curl_setopt($ch, CURLOPT_POST, TRUE);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    }
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            
                    $response = curl_exec($ch);
            
                    if (!curl_errno($ch)) {
                        switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                          case 200:  # OK
                          $responseCode = 200;
                            break;
                          default:
                            $responseCode = $http_code;
                        }
                    }
            
                    curl_close($ch);

                    if (json_decode($response)->result !== "Success") {

                        DB::rollBack();
                        Session::flash('unsuccess', 'User ID EDCCASH Salah');
                        return redirect()->back()->withInput();
                    }
                }
    
                if ($order->user_id != 0) {
                    $vorder = new VendorOrder;
                    $vorder->order_id = $order->id;
                    $vorder->user_id = $vendor->id;
    
                    $notf[] = $vendor->id;
                    
                    $vorder->qty = $order->totalQty;
                    $vorder->price = $order->pay_amount; 
                    $vorder->order_number = $order->order_number;
                    $vorder->save();
    
                    $userNotification = new UserNotification;
                    $userNotification->user_id = $vendor->id;
                    $userNotification->order_number = $order->order_number;
                    $userNotification->save();
                }
    
                $y++;
            }
            //Order update invoice id
    
            //add order to database different store
    
            //dd($cart->items);
    
            //ORDER !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            // $settings = Generalsetting::findOrFail(1);
            // $order = new Order;
            // $success_url = action('Front\PaymentController@payreturn');
            // $item_name = $settings->title." Order";
            // $item_number = str_random(4).time();
    
            // $order['user_id'] = $request->user_id;
            // $order['cart'] = utf8_encode(bzcompress(serialize($cart), 9));//
            // $order['totalQty'] = $request->totalQty;//
            // $order['pay_amount'] = round($request->total / $curr->value, 2);//
            // $order['method'] = $request->method;
            // $order['shipping'] = $request->shipping;
            // $order['pickup_location'] = $request->pickup_location;
            // $order['customer_email'] = $request->email;
            // $order['customer_name'] = $request->name;
            // $order['shipping_cost'] = $request->shipping_cost;
            // $order['packing_cost'] = $request->packing_cost;
            // $order['kurir'] = $request->kurir;
            // $order['paket_kurir'] = $request->paket_kurir;
            // $order['tax'] = $request->tax;
            // $order['customer_phone'] = $request->phone;
            // $order['order_number'] = str_random(4).time();
            // $order['customer_address'] = $request->address;
            // $order['customer_country'] = $request->customer_country;
            // $order['customer_city'] = $request->city;
            // $order['customer_zip'] = $request->zip;
            // $order['shipping_email'] = $request->shipping_email;
            // $order['shipping_name'] = $request->shipping_name;
            // $order['shipping_phone'] = $request->shipping_phone;
            // $order['shipping_address'] = $request->shipping_address;
            // $order['shipping_country'] = $request->shipping_country;
            // $order['shipping_city'] = $request->shipping_city;
            // $order['shipping_zip'] = $request->shipping_zip;
            // $order['order_note'] = $request->order_notes;
            // $order['txnid'] = $request->txn_id4;
            // $order['coupon_code'] = $request->coupon_code;
            // $order['coupon_discount'] = $request->coupon_discount;
            // $order['dp'] = $request->dp;
            // $order['payment_status'] = "Pending";
            // $order['currency_sign'] = $curr->sign;
            // $order['currency_value'] = $curr->value;
            // $order['vendor_shipping_id'] = $request->vendor_shipping_id;
            // $order['vendor_packing_id'] = $request->vendor_packing_id;
            //     if (Session::has('affilate'))
            //     {
            //         $val = $request->total / 100;
            //         $sub = $val * $gs->affilate_charge;
            //         $user = User::findOrFail(Session::get('affilate'));
            //         $user->affilate_income += $sub;
            //         $user->update();
            //         $order['affilate_user'] = $user->name;
            //         $order['affilate_charge'] = $sub;
            //     }
            // $order->save();
    
            // $track = new OrderTrack;
            // $track->title = 'Pending';
            // $track->text = 'You have successfully placed your order.';
            // $track->order_id = $order->id;
            // $track->save();
    
            // $notification = new Notification;
            // $notification->order_id = $order->id;
            // $notification->save();
            if ($request->coupon_id != "") {
                $coupon = Coupon::findOrFail($request->coupon_id);
                $coupon->used++;
                if ($coupon->times != null) {
                    $i = (int) $coupon->times;
                    $i--;
                    $coupon->times = (string) $i;
                }
                $coupon->update();
    
            }
    
            // foreach ($cart->items as $prod) {
            //     $x = (string) $prod['size_qty'];
            //     if (!empty($x)) {
            //         $product = Product::findOrFail($prod['item']['id']);
            //         $x = (int) $x;
            //         $x = $x - $prod['qty'];
            //         $temp = $product->size_qty;
            //         $temp[$prod['size_key']] = $x;
            //         $temp1 = implode(',', $temp);
            //         $product->size_qty = $temp1;
            //         $product->update();
            //     }
            // }
    
            // foreach ($cart->items as $prod) {
            //     $x = (string) $prod['stock'];
            //     if ($x != null) {
    
            //         $product = Product::findOrFail($prod['item']['id']);
            //         $product->stock = $prod['stock'];
            //         $product->update();
            //         if ($product->stock <= 5) {
            //             $notification = new Notification;
            //             $notification->product_id = $product->id;
            //             $notification->save();
            //         }
            //     }
            // }
    
            
            // $i = 0;
    
            // foreach ($cart->items as $prod) {
            //     if ($prod['item']['user_id'] != 0) {
            //         $vorder = new VendorOrder;
            //         $vorder->order_id = $invoice_id[$i];
            //         $vorder->user_id = $prod['item']['user_id'];
            //         $notf[] = $prod['item']['user_id'];
            //         $vorder->qty = $prod['qty'];
            //         $vorder->price = $payAmount[$i]; 
            //         $vorder->order_number = $orderNumber[$i];
            //         $vorder->save();
            //     }
    
            //     $i++;
            // }
            // dd($invoice_id);
    
    
            Session::put('temporder', $order);
            Session::put('tempcart', $cart);
            Session::put('checkout_payment_method', $request->method);
            Session::put('tempinvoice', $invoiceNumber);
            Session::forget('cart');
            Session::forget('already');
            Session::forget('coupon');
            Session::forget('coupon_total');
            Session::forget('coupon_total1');
            Session::forget('coupon_percentage');
    
            //Sending Email To Buyer
            if ($gs->is_smtp == 1) {
                $urlGV = "https://www.gudangvoucher.com/pg/v3/payment-sandbox.php";
                $urlGV .= "?merchantid=".urlencode(777)."&";
                $urlGV .= "amount=".urlencode($grandTotal)."&";
                $urlGV .= "product=".urlencode('EDCStore')."&";
                $urlGV .= "custom=".urlencode( $invoiceNumber )."&";
                $urlGV .= "email=".urlencode(Auth::user()->email);
                $urlGV .= "&bank=1";

                $data = [
                    'to' => Auth::user()->email,
                    'type' => "new_order",
                    'cname' => Auth::user()->name,
                    'oamount' => "",
                    'aname' => "",
                    'aemail' => "",
                    'wtitle' => "",
                    // 'onumber' => $order->order_number,
                ];
    
                $mailer = new GeniusMailer();
                $mailer->sendInvoiceOrder($data, ['invoice' => $invoiceNumber, 'method' => $request->method, 'gv' => $urlGV, 'name' => Auth::user()->name]);
            } 
            // else {
            //     $to = $request->email;
            //     $subject = "Your Order Placed!!";
            //     $msg = "Hello " . $request->name . "!\nYou have placed a new order.\nYour order number is " . $order->order_number . ".Please wait for your delivery. \nThank you.";
            //     $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            //     mail($to, $subject, $msg, $headers);
            // }
            //Sending Email To Admin
            // if ($gs->is_smtp == 1) {
            //     $data = [
            //         'to' => $gs->email,
            //         'subject' => "New Order Recieved!!",
            //         'body' => "Hello Admin!<br>Your store has received a new order.<br>Order Number is " . $order->order_number . ".Please login to your panel to check. <br>Thank you.",
            //     ];
    
            //     $mailer = new GeniusMailer();
            //     $mailer->sendCustomMail($data);
            // } else {
            //     $to = $gs->email;
            //     $subject = "New Order Recieved!!";
            //     $msg = "Hello Admin!\nYour store has recieved a new order.\nOrder Number is " . $order->order_number . ".Please login to your panel to check. \nThank you.";
            //     $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            //     mail($to, $subject, $msg, $headers);
            // }
            //ORDER !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            // $success_url = action('Front\CheckoutController@checkout', ['id' => base64_encode($invoiceNumber)]);

            DB::commit();
            switch ($request->method) {
                case 'EDCCASH':
                    $success_url = action('Front\PaymentController@payreturn');
                    break;
                case 'Bank Transfer':
                    $success_url = action('Front\PaymentController@payreturn');;
                    break;
                default:
                    $success_url = action('Front\CheckoutController@checkout');
                    break;
            }

        } catch (\Throwable $th) {
            dd($th);
            $success_url = action('Front\CheckoutController@checkout');

            DB::rollBack();
        }

        // $total_ongkir = "";


        $order['detail_ongkir']=$input['ongkir'];


        // dd($input);


        return redirect($success_url);
    }

    // Capcha Code Image
    private function code_image()
    {
        $actual_path = str_replace('project', '', base_path());
        $image = imagecreatetruecolor(200, 50);
        $background_color = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, 200, 50, $background_color);

        $pixel = imagecolorallocate($image, 0, 0, 255);
        for ($i = 0; $i < 500; $i++) {
            imagesetpixel($image, rand() % 200, rand() % 50, $pixel);
        }

        $font = $actual_path . 'assets/front/fonts/NotoSans-Bold.ttf';
        $allowed_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $length = strlen($allowed_letters);
        $letter = $allowed_letters[rand(0, $length - 1)];
        $word = '';
        //$text_color = imagecolorallocate($image, 8, 186, 239);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $cap_length = 6; // No. of character in image
        for ($i = 0; $i < $cap_length; $i++) {
            $letter = $allowed_letters[rand(0, $length - 1)];
            imagettftext($image, 25, 1, 35 + ($i * 25), 35, $text_color, $font, $letter);
            $word .= $letter;
        }
        $pixels = imagecolorallocate($image, 8, 186, 239);
        for ($i = 0; $i < 500; $i++) {
            imagesetpixel($image, rand() % 200, rand() % 50, $pixels);
        }
        session(['captcha_string' => $word]);
        imagepng($image, $actual_path . "assets/images/capcha_code.png");
    }

}
