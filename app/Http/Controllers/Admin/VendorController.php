<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Classes\GeniusMailer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Generalsetting;
use App\Models\Withdraw;
use App\Models\Currency;
use App\Models\Notification;
use App\Models\Subscriber;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Validator;
use Auth;
use DB;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

	    //*** JSON Request
	    public function datatables()
	    {
	        $datas = User::where('is_vendor','=',2)->orWhere('is_vendor','=',1)->orderBy('id','desc')->get();
	         //--- Integrating This Collection Into Datatables
	         return Datatables::of($datas)
                                ->addColumn('status', function(User $data) {
                                    $class = $data->is_vendor == 2 ? 'drop-success' : 'drop-danger';
                                    $s = $data->is_vendor == 2 ? 'selected' : '';
                                    $ns = $data->is_vendor == 1 ? 'selected' : '';
                                    return '<div class="action-list"><select class="process select vendor-droplinks '.$class.'">'.
                '<option value="'. route('admin-vendor-st',['id1' => $data->id, 'id2' => 2]).'" '.$s.'>Activated</option>'.
                '<option value="'. route('admin-vendor-st',['id1' => $data->id, 'id2' => 1]).'" '.$ns.'>Deactivated</option></select></div>';
                                }) 
	                            ->addColumn('action', function(User $data) {
	                                return '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button><div class="action-list"><a href="' . route('admin-vendor-secret',$data->id) . '" > <i class="fas fa-user"></i> Secret Login</a><a href="javascript:;" data-href="' . route('admin-vendor-verify',$data->id) . '" class="verify" data-toggle="modal" data-target="#verify-modal"> <i class="fas fa-question"></i> Ask For Verification</a><a href="' . route('admin-vendor-show',$data->id) . '" > <i class="fas fa-eye"></i> Details</a><a data-href="' . route('admin-vendor-edit',$data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i> Edit</a><a href="javascript:;" class="send" data-email="'. $data->email .'" data-toggle="modal" data-target="#vendorform"><i class="fas fa-envelope"></i> Send Email</a><a href="javascript:;" data-href="' . route('admin-vendor-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> Delete</a></div></div>';
	                            }) 
	                            ->rawColumns(['status','action'])
	                            ->toJson(); //--- Returning Json Data To Client Side
	    }

	//*** GET Request
    public function index()
    {
        return view('admin.vendor.index');
    }

    //*** GET Request
    public function color()
    {
        return view('admin.generalsetting.vendor_color');
    }


    //*** GET Request
    public function subsdatatables($status = false)
    {
         if ($status !== FALSE && $status === 'verification') {
            $datas = UserSubscription::where('status','=',0)->orderBy('id','desc')->get();
         }else{
            $datas = UserSubscription::where('status','=',1)->orderBy('id','desc')->get();
         }
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('name', function(UserSubscription $data) {
                                $name = isset($data->user->owner_name) ? $data->user->owner_name : 'Removed';
                                return  $name;
                            })

                            ->editColumn('txnid', function(UserSubscription $data) {
                                $txnid = $data->txnid == null ? 'Free' : $data->txnid;
                                return $txnid;
                            }) 
                            ->editColumn('created_at', function(UserSubscription $data) {
                                $date = $data->created_at;
                                return $date;
                            }) 
                            ->editColumn('expired', function(UserSubscription $data) {
                                $today = Carbon::now()->format('Y-m-d');
                                $lastday = $data->user->date;
                                $active = Carbon::parse($lastday)->format('Y-m-d');
                                // $newday = strtotime($today);
                                // $lastday = $data->user->date;
                                
                                // $date = Carbon::parse($lastday)->diffInDays($today);;

                                $date1=date_create($active);
                                $date2=date_create($today);
                                $diff=date_diff($date1,$date2);
                                if ($data->days == 0) {
                                    return '-';
                                }
                                return $diff->format("%a days");
                            })                             
                            ->addColumn('action', function(UserSubscription $data) {
                                // return '<div class="action-list"><a data-href="' . route('admin-vendor-sub',$data->id) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i>Details</a></div>';
                                $button = '<div class="godropdown">
                                    <button class="go-dropdown-toggle"> 
                                        Actions<i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div class="action-list">
                                        <a data-href="' . route('admin-vendor-sub',$data->id) . '" class="view" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i>Details</a>
                                    ';
                                if ($data->is_dropship === '1') {
                                    $button .= '<a data-href="' . route('admin-vendor-subs-edit',$data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i> Edit</a>';
                                }
                                $button .= '</div>
                                    </div>';

                                    return $button;
                            })
                            ->addColumn('action_details', function (UserSubscription $data){
                                $button = '<div class="godropdown">
                                    <button class="go-dropdown-toggle"> 
                                        Actions<i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div class="action-list">
                                        <a data-href="' . route('admin-vendor-sub',$data->id) . '" class="view" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i>Details</a>
                                    </div>
                                    </div>
                                    ';

                                return '<div class="action-list"><a data-href="' . route('admin-vendor-sub',$data->id) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i>Details</a></div>';
                            })
                            ->addColumn('payment_status', function(UserSubscription $data){
                                $invoice = $data->invoice;
                                $result = '';

                                if ($invoice <> '') {
                                    $result = ucwords( strtolower( $invoice->status ) );
                                }

                                return $result;
                            })
                            ->addColumn('action_verify', function(UserSubscription $data){
                                switch ($data->status) {
                                    case 1:
                                        $class = 'drop-success';
                                        break;
                                    case 2:
                                        $class = 'drop-danger';
                                        break;
                                    case 0: 
                                        $class = 'drop-warning';
                                        break;
                                    default:
                                        $class = 'drop-info';
                                        break;
                                }
                                $verify = $data->status == '1' ? 'selected' : '';
                                $decline = $data->status == '2' ? 'selected' : '';
                                $pending = $data->status == '0' ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option value="" '.$pending.'>Need Verification</option><option data-val="1" value="'. route('admin-vendor-subs-verify',['id1' => $data->id, 'id2' => 1]).'" '.$verify.'>Verify</option><<option data-val="2" value="'. route('admin-vendor-subs-verify',['id1' => $data->id, 'id2' => 2]).'" '.$decline.'>Decline</option>/select></div>';                                
                            }) 
                            ->rawColumns(['action', 'action_verify', 'action_details'])
                            ->toJson(); //--- Returning Json Data To Client Side


    }


	//*** GET Request
    public function subs()
    {

        return view('admin.vendor.subscriptions');
    }

	//*** GET Request
    public function sub($id)
    {
        $subs = UserSubscription::findOrFail($id);
        return view('admin.vendor.subscription-details',compact('subs'));
    }

    public function verifSubs()
    {
        return view('admin.vendor.subs-verif');
    }
    public function verifySubs($id,$status)
    {
        $subs = UserSubscription::findOrFail($id);
        $newPlan = '';

        if ($status === "2") {
            $subs->status = 2;
            $subs->save();
        }elseif($status === "1"){
            $user = $subs->user;
            $planBefore = UserSubscription::where('user_id', $subs->user_id)->where('status', '=', 1)->get();
            

            $subs->status = 1;
            $user->is_vendor = 2;

            $subscriber = new Subscriber;
            $subscriber->email = $user->email;

            $notif = new Notification;
            $notif->user_id = $subs->user_id;
            $notif->header = 'Upgrade Akun Vendor';
            $notif->messages = 'Upgrade Akun Vendor Anda Telah Diverifikasi';
            $notif->is_customer = '1';
            $notif->invoice_id = $subs->invoice->id;
            

            DB::beginTransaction();

            try {
                if ($planBefore->count() >= 1) {
                    foreach ($planBefore as $key => $data) {
                        $data->delete();
                    }
                }

                $user->save();
                $subscriber->save();
                $subs->save();
                $notif->save();

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
            }

        }
    }

	//*** GET Request
  	public function status($id1,$id2)
    {
        $user = User::findOrFail($id1);
        $user->is_vendor = $id2;
        $user->update();
        //--- Redirect Section        
        $msg[0] = 'Status Updated Successfully.';
        return response()->json($msg);      
        //--- Redirect Section Ends    

    }

	//*** GET Request
    public function edit($id)
    {
        $data = User::findOrFail($id);
        return view('admin.vendor.edit',compact('data'));
    }

    function editSubs($id)
    {
        $data = UserSubscription::findOrFail($id);
        return view('admin.vendor.edit-subscription',compact('data'));
    }



	//*** GET Request
    public function verify($id)
    {
        $data = User::findOrFail($id);
        return view('admin.vendor.verification',compact('data'));
    }

	//*** POST Request
    public function verifySubmit(Request $request, $id)
    {
        $settings = Generalsetting::find(1);
        $user = User::findOrFail($id);
        $user->verifies()->create(['admin_warning' => 1, 'warning_reason' => $request->details]);

                    if($settings->is_smtp == 1)
                    {
                    $data = [
                        'to' => $user->email,
                        'type' => "vendor_verification",
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
                    mail($user->email,'Request for verification.','You are requested verify your account. Please send us photo of your passport.Thank You.',$headers);
                    }

        $msg = 'Verification Request Sent Successfully.';
        return response()->json($msg);   
    }


	//*** POST Request
    public function update(Request $request, $id)
    {
	    //--- Validation Section
	        $rules = [
                'shop_name'   => 'unique:users,shop_name,'.$id,
                 ];
            $customs = [
                'shop_name.unique' => 'Shop Name "'.$request->shop_name.'" has already been taken. Please choose another name.'
            ];

         $validator = Validator::make(Input::all(), $rules,$customs);
         
         if ($validator->fails()) {
           return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
         }
         //--- Validation Section Ends

        $user = User::findOrFail($id);
        $data = $request->all();
        // unset($data["_token"]);
        $user->update( $data );
        $msg = 'Vendor Information Updated Successfully.';
        return response()->json($msg);   
    }

	//*** GET Request
    public function show($id)
    {
        $data = User::findOrFail($id);
        return view('admin.vendor.show',compact('data'));
    }
    

    //*** GET Request
    public function secret($id)
    {
        Auth::guard('web')->logout();
        $data = User::findOrFail($id);
        Auth::guard('web')->login($data); 
        return redirect()->route('vendor-dashboard');
    }
    

	//*** GET Request
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->is_vendor = 0;
            $user->is_vendor = 0;
            $user->shop_name = null;
            $user->shop_details= null;
            $user->owner_name = null;
            $user->shop_number = null;
            $user->shop_address = null;
            $user->reg_number = null;
            $user->shop_message = null;
        $user->update();
        if($user->notivications->count() > 0)
        {
            foreach ($user->notivications as $gal) {
                $gal->delete();
            }
        }
            //--- Redirect Section     
            $msg = 'Vendor Deleted Successfully.';
            return response()->json($msg);      
            //--- Redirect Section Ends    
    }

        //*** JSON Request
        public function withdrawdatatables()
        {
             $datas = Withdraw::where('type','=','vendor')->orderBy('id','desc')->get();
             //--- Integrating This Collection Into Datatables
             return Datatables::of($datas)
                                ->addColumn('name', function(Withdraw $data) {
                                    $name = $data->user->name;
                                    return '<a href="' . route('admin-vendor-show',$data->user->id) . '" target="_blank">'. $name .'</a>';
                                }) 
                                ->addColumn('email', function(Withdraw $data) {
                                    $email = $data->user->email;
                                    return $email;
                                }) 
                                ->addColumn('phone', function(Withdraw $data) {
                                    $phone = $data->user->phone;
                                    return $phone;
                                }) 
                                ->editColumn('status', function(Withdraw $data) {
                                    $status = ucfirst($data->status);
                                    return $status;
                                }) 
                                ->editColumn('amount', function(Withdraw $data) {
                                    $sign = Currency::where('is_default','=',1)->first();
                                    $amount = $sign->sign.round($data->amount * $sign->value , 2);
                                    return $amount;
                                }) 
                                ->addColumn('action', function(Withdraw $data) {
                                    $action = '<div class="action-list"><a data-href="' . route('admin-vendor-withdraw-show',$data->id) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i> Details</a>';
                                    if($data->status == "pending") {
                                    $action .= '<a data-href="' . route('admin-vendor-withdraw-accept',$data->id) . '" data-toggle="modal" data-target="#confirm-delete"> <i class="fas fa-check"></i> Accept</a><a data-href="' . route('admin-vendor-withdraw-reject',$data->id) . '" data-toggle="modal" data-target="#confirm-delete1"> <i class="fas fa-trash-alt"></i> Reject</a>';
                                    }
                                    $action .= '</div>';
                                    return $action;
                                }) 
                                ->rawColumns(['name','action'])
                                ->toJson(); //--- Returning Json Data To Client Side
        }

        //*** GET Request
        public function withdraws()
        {
            return view('admin.vendor.withdraws');
        }

        //*** GET Request       
        public function withdrawdetails($id)
        {
            $sign = Currency::where('is_default','=',1)->first();
            $withdraw = Withdraw::findOrFail($id);
            return view('admin.vendor.withdraw-details',compact('withdraw','sign'));
        }

        //*** GET Request   
        public function accept($id)
        {
            $withdraw = Withdraw::findOrFail($id);
            $data['status'] = "completed";
            $withdraw->update($data);
            //--- Redirect Section     
            $msg = 'Withdraw Accepted Successfully.';
            return response()->json($msg);      
            //--- Redirect Section Ends   
        }

        //*** GET Request   
        public function reject($id)
        {
            $withdraw = Withdraw::findOrFail($id);
            $account = User::findOrFail($withdraw->user->id);
            $account->affilate_income = $account->affilate_income + $withdraw->amount + $withdraw->fee;
            $account->update();
            $data['status'] = "rejected";
            $withdraw->update($data);
            //--- Redirect Section     
            $msg = 'Withdraw Rejected Successfully.';
            return response()->json($msg);      
            //--- Redirect Section Ends   
        }

}
