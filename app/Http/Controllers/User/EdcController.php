<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\Edccash;
use App\Models\User;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\URL;
use Session;

class EdcController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edccash()
    {
        $user = Auth::guard('web')->user();
        $check_account_edc = $user->edccash_id;
        if($check_account_edc <> ''){
            //ambil data edc cash user dari api edc
            return view('user.edccash.verified',compact('user'));   
        }else{
            return view('user.edccash.index',compact('user'));   
        }
    }

    public function post_number(Request $request)
    {
        $this->validate($request, [
            "email" => "required",
        ]);

        $url = "https://edcstoreapi.edccash.com/api/login/";
        $url .= Auth::user()->email;
        $url .= "/";

        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
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


        if ($responseCode !== 200) {
            Session::flash('msg', 'Email anda tidak terdaftar di EDC Cash');
            return redirect('user/edc-cash');
        }

        if (json_decode($response)->result === "Not Success") {
            Session::flash('msg', 'Email anda tidak terdaftar di EDC Cash');
            return redirect('user/edc-cash');            
        }

        $user = Auth::user();
        Session::flash('msg', 'Kami telah mengirimkan Kode OTP ke email anda');
        
        return view('user.edccash.verfikasi',compact('user'));

        // $check_account_edc = Edccash::where('phone_number', $request->phone)->where('status', 'verified')->first();
        // if($check_account_edc){
        //     Session::flash('msg', 'Nomor telephone telah digunakan');
        //     return redirect('user/edc-cash');
        // }else{
        //     $user = Auth::guard('web')->user();
        //     $update_edc_user = Edccash::where('user_id', $user->id)->first();
        //     if($update_edc_user){
        //         $update_edc_user->phone_number = $request->phone;
        //         $update_edc_user->code_verification = "1234567"; //auto generate
        //         $update_edc_user->save();
        //         Session::flash('msg', 'Verifikasi nomer telepon anda');
        //         return view('user.edccash.verfikasi',compact('user'));
        //     }else{
        //         //*1 cek nomer ke edc cash
        //         //*2 jika ada tambahkan akun baru, jika tidak redirect
        //         $edc = new Edccash();
        //         $edc->phone_number = $request->phone;
        //         $edc->user_id = $user->id;
        //         $edc->code_verification = "123456"; //auto generate
        //         $edc->save();
        //         Session::flash('msg', 'Verifikasi nomer telepon anda');
        //         return view('user.edccash.verfikasi',compact('user'));
        //     }
        // }
    }

    public function verifikasi(Request $request)
    {
        $user = Auth::guard('web')->user();

        $url = "https://edcstoreapi.edccash.com/api/login/";

        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
        );

        $data = array(
            'email' => $user->email,
            'kodeotp' => $request->code_verification
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

        if ($responseCode !== 200) {
            Session::flash('msg', 'Kode OTP Salah');
            return redirect('user/edc-cash');
        }
        
        if (json_decode($response)->result !== "Success") {
            Session::flash('msg', 'Kode OTP Salah');
            return redirect('user/edc-cash');            
        }

        $url = "https://edcstoreapi.edccash.com/api/login/";
        $url .= Auth::user()->email;
        $url .= "/";

        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
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


        if ($responseCode !== 200) {
            Session::flash('msg', 'Email anda tidak terdaftar di EDC Cash');
            return redirect('user/edc-cash');
        }

        if (json_decode($response)->result === "Not Success") {
            Session::flash('msg', 'Email anda tidak terdaftar di EDC Cash');
            return redirect('user/edc-cash');            
        }

        $user->edccash_id = json_decode($response)->result;
        $user->save();

        return view('user.edccash.verified',compact('user'));  
    }
}
