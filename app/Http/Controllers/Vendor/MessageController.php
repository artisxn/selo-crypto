<?php

namespace App\Http\Controllers\Vendor;

use App\Classes\GeniusMailer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Datatables;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\AdminUserConversation;
use App\Models\AdminUserMessage;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\User;
use App\Models\Order;


class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //*** JSON Request
    public function datatables()
    {
         $datas = AdminUserConversation::where('type','=','Dispute')->where('vendor_id', '=', Auth::user()->id)->orderBy('created_at', 'desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('created_at', function(AdminUserConversation $data) {
                                $date = $data->created_at;
                                return  $date;
                            })
                            ->addColumn('name', function(AdminUserConversation $data) {
                                $name = $data->user->name;
                                return  $name;
                            })
                            ->editColumn('status', function(AdminUserConversation $data){
                                switch ($data->status) {
                                    case '0':
                                        $st = 'Open';
                                        break;
                                    case '1':
                                        $st = 'Done';
                                        break;
                                    default:
                                        $st = '-';
                                        break;
                                }

                                return $st;
                            }) 
                            ->addColumn('action', function(AdminUserConversation $data) {
                                return '<div class="action-list"><a href="' . route('vendor-dmessage-details',$data->id) . '"> <i class="fas fa-eye"></i> Details</a></div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function adminDiscordmessages()
    {
            $user = Auth::guard('web')->user();
            $convs = AdminUserConversation::where('type','=','Dispute')->where('vendor_id','=',$user->id)->get();

            return view('vendor.dispute.index',compact('convs'));            
    }

    public function dispute($id)
    {
        $oder = '';
        $cart = '';
        $conv = AdminUserConversation::findOrfail($id);
        
        if ($conv->type === 'Dispute') {
            $order = Order::where('order_number', $conv->order_number)->firstOrfail();
            $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        }

        return view('vendor.dispute.create',compact('conv', 'order', 'cart'));                 
    }

    public function postmessage(Request $request)
    {
        $msg = new AdminUserMessage();
        $input = $request->all();  
        // $input->user_id = Auth::user()->id;
        $input['user_id'] = Auth::user()->id;

        if ($request->conversation_id <> '') {
            $conv = AdminUserConversation::findOrfail($request->conversation_id);

            if ($conv->type === 'Dispute') {
                try {
                    $notifUser = new Notification;
                    $notifUser->user_id = $conv->user_id;
                    $notifUser->conversation_id = $conv->id;
                    $notifUser->is_read = '0';
                    $notifUser->header = 'Balasan Komplain';
                    $notifUser->messages = 'Pesan Balasan dari Komplain Pesanan #'.$conv->order_number;
                    $notifUser->is_customer = '1';
    
                    $notifUser->save();
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        }


        // dd($input);
        $msg->fill($input)->save();
        //--- Redirect Section     
        $msg = 'Message Sent!';
        return response()->json($msg);      
        //--- Redirect Section Ends    
    }

    public function messageshow($id)
    {
        $conv = AdminUserConversation::findOrfail($id);
                
        return view('vendor.dispute.load',compact('conv'));                 
    }
}