<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;
use Config;
use DB;
use App\Models\Generalsetting;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;

class GvController extends Controller
{    

    public function store(Request $request)
    {
        $this->validate($request, [
            'shop_name'   => 'unique:users',
        ],[ 
            'shop_name.unique' => 'This shop name has already been taken.'
        ]);        

        $input = $request->all();
        $user = Auth::user();
        $subs = Subscription::findOrFail($request->subs_id);
        $settings = Generalsetting::findOrFail(1);

        $address = [$user->city_id, $user->districts, $user->provinces];
        if (in_array(null, $address)) {
            return redirect()->route('user-profile');
        }
   
        $checkPlanBefore = UserSubscription::where('user_id', $user->id)->where('status', '=', 0)->first();
        
        if ($checkPlanBefore <> '') {
            $userSubs = $checkPlanBefore;
        }else{
            $userSubs = new UserSubscription;
        }

        $userSubs->user_id = $user->id;
        $userSubs->subscription_id = $subs->id;
        $userSubs->title = $subs->title;
        $userSubs->currency = 'Rp';
        $userSubs->currency_code = 'IDR';
        $userSubs->price = $subs->price;
        $userSubs->days = $subs->days;
        $userSubs->allowed_products = $subs->allowed_products;
        $userSubs->details = $subs->details;
        $userSubs->method = 'GudangVoucher';   
        $userSubs->status = 0;
        if ($subs->is_dropship === '1') {
            $userSubs->is_dropship = '1';
        }

        $today = Carbon::now()->format('Y-m-d');

        if(!empty($subs))
        {
            $newday = strtotime($today);
            $lastday = strtotime($user->date);

            if (empty($user->date) || $newday > $lastday) {
                $userDate = date('Y-m-d', strtotime("+".(string)$subs->days." days"));
            }else{
                $secs = $lastday-$newday;
                $days = $secs / 86400;
                $total = $days+$subs->days;
                $userDate = date('Y-m-d', strtotime($today.' + '.$total.' days'));
            }
        }
        else
        {   
            $userDate = date('Y-m-d', strtotime($today.' + '.$subs->days.' days'));
        } 

        $user->date = $userDate;
        // $user->shop_name = $request->shop_name;
        // $user->slug_shop_name = str_slug($user->shop_name, '');
        $user->owner_name = $request->has('owner_name') ? $request->owner_name : $user->owner_name;
        $user->shop_number = $request->has('shop_number') ? $request->shop_number : $user->shop_number;
        $user->shop_address = $request->has('shop_address') ? $request->shop_address : $user->shop_address;
        $user->reg_number = $request->has('reg_number') ? $request->reg_number : $user->reg_number;
        $user->shop_message = $request->has('shop_message') ? $request->shop_message : $user->shop_message;
        $user->rekening_name = $request->has('rekening_name') ? $request->rekening_name : $user->rekening_name;
        $user->rekening_no = $request->has('rekening_no') ? $request->rekening_no : $user->rekening_no;
        $user->domain_name = $request->has('domain_name') ? $request->domain_name : $user->domain_name;  
        $user->shop_name = $request->has('shop_name') ? $request->shop_name : $user->shopname;
        $user->slug_shop_name = $request->has('shop_name') ? Str::slug($request->shop_name, '-') : $user->slug_shop_name;    


        
        $invoiceNumber = mt_rand(5, 1000).time();
        $invoice = new Invoice();
        $invoice->total_tagihan = $subs->price;
        $invoice->invoice_number = $invoiceNumber;
        $invoice->user_id = $user->id;        
        $invoice->is_subscription = '1';

        DB::beginTransaction();
        
        try {
            $invoice->save();
            $userSubs->txnid = $invoiceNumber;
            $userSubs->save();
            $user->save();

            DB::commit();

            $success_url = action('User\GvController@paySuccess', [base64_encode($invoiceNumber)]);

            return redirect($success_url);
            
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('unsuccess', 'Something Wrong! Please try it again.');
        }

        

    }

    public function paySuccess($tn)
    {
        $gs = Generalsetting::findOrFail(1);
        $user = Auth::user();
        $invoice = Invoice::where('invoice_number', base64_decode($tn))->where('user_id', $user->id)->where('is_subscription', '1')->firstOrFail();
        $userSubs = UserSubscription::where('txnid', base64_decode($tn))->where('user_id', $user->id)->firstOrFail();

        $urlGV = "https://www.gudangvoucher.com/pg/v3/payment-sandbox.php";
        $urlGV .= "?merchantid=".urlencode($gs->gv_merchantid)."&";
        $urlGV .= "amount=".urlencode($invoice->total_tagihan)."&";
        $urlGV .= "product=".urlencode('Subscription '.$userSubs->title)."&";
        $urlGV .= "custom=".urlencode(base64_decode($tn))."&";
        $urlGV .= "email=".urlencode(Auth::user()->email);

        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $urlGV);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);

        return view('front.subs-gv-success', compact('invoice', 'userSubs', 'urlGV'));
    }
}
