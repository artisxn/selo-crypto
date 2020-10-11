<?php

namespace App\Http\Controllers\User;

use App\Classes\GeniusMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use Illuminate\Support\Facades\Input;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\Subscription;
use App\Models\Generalsetting;
use App\Models\UserSubscription;
use App\Models\FavoriteSeller;
use App\Models\Edccash;
use DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();  
        $gs = Generalsetting::find(1)->firstOrFail();

        return view('user.dashboard',compact('user', 'gs'));
    }

    public function profile()
    {
        $user = Auth::user();
        $provinces = DB::table('provinces')->orderBy('name', 'asc')->get();
        $regencies = DB::table('regencies')->where('province_id', @$user->provinces)->orderBy('name', 'asc')->get();
        $districts = DB::table('districts')->where('regency_id', @$user->city_id)->orderBy('name', 'asc')->get();

        
        $zip = $user->zip;
        
        // $zip = DB::table('zip')->where('province_code',@$user->provinces)->orderBy('postal_code', 'asc')->distinct()->get();

        return view('user.profile',compact('user', 'provinces', 'regencies', 'districts','zip'));

    }

    public function profileupdate(Request $request)
    {
        //--- Validation Section

        $rules =
        [
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'email' => 'unique:users,email,'.Auth::user()->id,
            'city' => 'required',
            'zip' => 'required'
        ];


        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
        $input = $request->all();  
        $input['city_id'] = $input['city'];
        unset($input['city']);
        $data = Auth::user();        
            if ($file = $request->file('photo')) 
            {              
                $name = time().$file->getClientOriginalName();
                $file->move('assets/images/users/',$name);
                if($data->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/users/'.$data->photo)) {
                        unlink(public_path().'/assets/images/users/'.$data->photo);
                    }
                }            
            $input['photo'] = $name;
            } 

        $data->update($input);
        $msg = 'Successfully updated your profile';
        return response()->json($msg); 
    }

    public function resetform()
    {
        return view('user.reset');
    }

    public function reset(Request $request)
    {
        $user = Auth::user();
        
        if ($user->password){
            if (Hash::check($request->cpass, $user->password)){
                if ($request->newpass == $request->renewpass){
                    $input['password'] = Hash::make($request->newpass);
                }else{
                    return response()->json(array('errors' => [ 0 => 'Confirm password does not match.' ]));     
                }
            }else{
                return response()->json(array('errors' => [ 0 => 'Current password Does not match.' ]));   
            }
        }else{
            if ($request->newpass == $request->renewpass){
                $input['password'] = Hash::make($request->newpass);
            }else{
                return response()->json(array('errors' => [ 0 => 'Confirm password does not match.' ]));     
            }
        }
        //jika user login menggunakan akun fb/google. pass belum di setting
        $user->update($input);
        $msg = 'Successfully change your password';
        return response()->json($msg);
    }


    public function package()
    {
        $user = Auth::user();
        $cek_account_edc = $user->edccash_id;
        if(!$cek_account_edc){
            Session::flash('msg', 'Anda harus mempunyai akun EDC-Cash untuk mulai berjualan');
            return view('user.edccash.index',compact('user'));   
        }else{
            $subs = Subscription::all();
            $package = $user->subscribes()->where('status',1)->orderBy('id','desc')->first();
            return view('user.package.index',compact('user','subs','package'));
        }
    }


    public function vendorrequest($id)
    {
        $subs = Subscription::findOrFail($id);
        $gs = Generalsetting::findOrfail(1);
        $user = Auth::user();
        $package = $user->subscribes()->where('status',1)->orderBy('id','desc')->first();
        if($gs->reg_vendor != 1)
        {
            return redirect()->back();
        }

        $address = [$user->city_id, $user->districts, $user->provinces];

        if (in_array(null, $address)) {
            \Session::flash('unsuccess', 'Lengkapi Profil Anda');
        }
        return view('user.package.details',compact('user','subs','package','address'));
    }

    public function vendorrequestsub(Request $request)
    {
        $this->validate($request, [
            'shop_name'   => 'unique:users',
           ],[ 
               'shop_name.unique' => 'This shop name has already been taken.'
            ]);
        $user = Auth::user();
        $package = $user->subscribes()->where('status',1)->orderBy('id','desc')->first();
        $subs = Subscription::findOrFail($request->subs_id);
        
        $settings = Generalsetting::findOrFail(1);
                    $today = Carbon::now()->format('Y-m-d');
                    // $input = $request->all();  
                    // $user = User::find($user->id);
                    $input['is_vendor'] = 2;
                    $input['date'] = date('Y-m-d', strtotime($today.' + '.$subs->days.' days'));
                    $input['mail_sent'] = 1;
                    $input['rekening_name'] = $request->has('rekening_name') ? $request->rekening_name : $user->rekening_name;
                    $input['rekening_no'] = $request->has('rekening_no') ? $request->rekening_no : $user->rekening_no;
                    $input['domain_name'] = $request->has('domain_name') ? $request->domain_name : $user->domain_name;  
                    $input['shop_name'] = $request->has('shop_name') ? $request->shop_name : $user->shopname;
                    $input['owner_name'] = $request->has('owner_name') ? $request->owner_name : $user->owner_name;  
                    $input['shop_number'] = $request->has('shop_number') ? $request->shop_number : $user->shop_number;  
                    $input['shop_address'] = $request->has('shop_address') ? $request->shop_address : $user->shop_address;  
                    $input['reg_number'] = $request->has('reg_number') ? $request->reg_number : $user->reg_number;  
                    $input['shop_message'] = $request->has('shop_message') ? $request->shop_message : $user->shop_message;  
                    $input['slug_shop_name']  = $request->has('shop_name') ? Str::slug($request->shop_name, '-') : $user->slug_shop_name;    

                    // dd($input);
                    $user->update($input);
                    $sub = new UserSubscription;
                    $sub->user_id = $user->id;
                    $sub->subscription_id = $subs->id;
                    $sub->title = $subs->title;
                    $sub->currency = $subs->currency;
                    $sub->currency_code = $subs->currency_code;
                    $sub->price = $subs->price;
                    $sub->days = $subs->days;
                    $sub->allowed_products = $subs->allowed_products;
                    $sub->details = $subs->details;
                    $sub->method = 'Free';
                    $sub->status = 1;
                    

                    $sub->save();
                    if($settings->is_smtp == 1)
                    {
                    $data = [
                        'to' => $user->email,
                        'type' => "vendor_accept",
                        'cname' => $user->name,
                        'oamount' => "",
                        'aname' => "",
                        'aemail' => "",
                        'onumber' => "",
                    ];    
                    $mailer = new GeniusMailer();
                    $mailer->sendAutoMail($data);        
                    }
                    else
                    {
                    $headers = "From: ".$settings->from_name."<".$settings->from_email.">";
                    mail($user->email,'Your Vendor Account Activated','Your Vendor Account Activated Successfully. Please Login to your account and build your own shop.',$headers);
                    }

                    return redirect()->route('user-dashboard')->with('success','Vendor Account Activated Successfully');

    }


    public function favorite($id1,$id2)
    {
        $fav = new FavoriteSeller();
        $fav->user_id = $id1;
        $fav->vendor_id = $id2;
        $fav->save();
    }

    public function favorites()
    {
        $user = Auth::guard('web')->user();
        $favorites = FavoriteSeller::where('user_id','=',$user->id)->get();
        return view('user.favorite',compact('user','favorites'));
    }


    public function favdelete($id)
    {
        $wish = FavoriteSeller::findOrFail($id);
        $wish->delete();
        return redirect()->route('user-favorites')->with('success','Successfully Removed The Seller.');
    }


}
