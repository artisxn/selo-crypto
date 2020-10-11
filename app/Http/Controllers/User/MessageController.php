<?php

namespace App\Http\Controllers\User;

use App\Classes\GeniusMailer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
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

   public function messages()
    {
        $user = Auth::guard('web')->user();
        $convs = Conversation::where('sent_user','=',$user->id)->orWhere('recieved_user','=',$user->id)->get();
        return view('user.message.index',compact('user','convs'));            
    }

    public function count_message(){
        $user = Auth::guard('web')->user();
        $count_unread_messages = 0;
        $par = 0;
        $convs = Conversation::all();
        foreach($convs as $conv){
            if($conv->sent_user == $user->id){
                foreach($conv->messages as $message){
                    if($message->status == 'sent' && $message->sent_user == NULL){
                        $count_unread_messages += 1;
                    }
                }
            }
            if($conv->recieved_user == $user->id){
                foreach($conv->messages as $message){
                    if($message->status == 'sent' && $message->recieved_user == NULL){
                        $count_unread_messages += 1;
                    }
                }
            }
        }
        return response()->json($count_unread_messages);
    }

    public function message($id)
    {
        $user = Auth::guard('web')->user();
        $conv = Conversation::findOrfail($id);
        $messages = Message::where('conversation_id', $conv->id)->get();
        foreach($messages as $message){
            if($conv->sent_user == $user->id){
                $updatemessages = Message::where('conversation_id', $conv->id)->where('recieved_user', "!=", $user->id)->update(['status' => 'read']);
            }else if($conv->recieved_user == $user->id){
                $updatemessages = Message::where('conversation_id', $conv->id)->where('sent_user', "!=", $user->id)->update(['status' => 'read']);
            }
        }
        return view('user.message.create',compact('user','conv'));                 
    }

    public function messagedelete($id)
    {
            $conv = Conversation::findOrfail($id);
            if($conv->messages->count() > 0)
            {
                foreach ($conv->messages as $key) {
                    $key->delete();
                }
            }
            $conv->delete();
            return redirect()->back()->with('success','Message Deleted Successfully');                 
    }

    public function msgload($id)
    {
            $conv = Conversation::findOrfail($id);
            return view('load.usermsg',compact('conv'));                 
    }  

    //Send email to user
    public function usercontact(Request $request)
    {
        $data = 1;
        $user = User::findOrFail($request->user_id);
        $vendor = User::where('email','=',$request->email)->first();
        if(empty($vendor))
        {
            $data = 0;
            return response()->json($data);   
        }

        $subject = $request->subject;
        $to = $vendor->email;
        $name = $request->name;
        $from = $request->email;
        $msg = "Name: ".$name."\nEmail: ".$from."\nMessage: ".$request->message;
        $gs = Generalsetting::findOrfail(1);
        if($gs->is_smtp == 1)
        {
        $data = [
            'to' => $vendor->email,
            'subject' => $request->subject,
            'body' => $msg,
        ];

        $mailer = new GeniusMailer();
        $mailer->sendCustomMail($data);
        }
        else
        {
        $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
        mail($to,$subject,$msg,$headers);
        }

        $conv = Conversation::where('sent_user','=',$user->id)->where('subject','=',$subject)->first();
        if(isset($conv)){
            $msg = new Message();
            $msg->conversation_id = $conv->id;
            $msg->message = $request->message;
            $msg->sent_user = $user->id;
            $msg->save();
            return response()->json($data);   
        }
        else{
            $message = new Conversation();
            $message->subject = $subject;
            $message->sent_user= $request->user_id;
            $message->recieved_user = $vendor->id;
            $message->message = $request->message;
            $message->save();

            $msg = new Message();
            $msg->conversation_id = $message->id;
            $msg->message = $request->message;
            $msg->sent_user = $request->user_id;;
            $msg->save();
            return response()->json($data);   
        }
    }

    public function postmessage(Request $request)
    {
        $msg = new Message();
        $input = $request->all();  
        $msg->fill($input)->save();
        //--- Redirect Section     
        $msg = 'Message Sent!';
        return response()->json($msg);      
        //--- Redirect Section Ends  
    }

    public function adminmessages()
    {
            $user = Auth::guard('web')->user();
            $convs = AdminUserConversation::where('type','=','Ticket')->where('user_id','=',$user->id)->get();
            return view('user.ticket.index',compact('convs'));            
    }

    public function adminDiscordmessages()
    {
            $user = Auth::guard('web')->user();
            $convs = AdminUserConversation::where('type','=','Dispute')->where('user_id','=',$user->id)->get();
            return view('user.dispute.index',compact('convs'));            
    }

    public function messageload($id)
    {
            $conv = AdminUserConversation::findOrfail($id);
            return view('load.usermessage',compact('conv'));                 
    }   

    public function adminmessage($id)
    {
            $conv = AdminUserConversation::findOrfail($id);
            return view('user.ticket.create',compact('conv'));                 
    }   

    public function adminmessagedelete($id)
    {
            $conv = AdminUserConversation::findOrfail($id);
            if($conv->messages->count() > 0)
            {
                foreach ($conv->messages as $key) {
                    $key->delete();
                }
            }
            $conv->delete();
            return redirect()->back()->with('success','Message Deleted Successfully');                 
    }

    public function adminpostmessage(Request $request)
    {
        $msg = new AdminUserMessage();
        $input = $request->all();  
        $msg->fill($input)->save();
        $notification = new Notification;
        $notification->conversation_id = $msg->conversation->id;
        $notification->save();
        //--- Redirect Section     
        $msg = 'Message Sent!';
        return response()->json($msg);      
        //--- Redirect Section Ends  
    }

    public function adminusercontact(Request $request)
    {
        $data = '';
        $user = Auth::guard('web')->user();        
        $gs = Generalsetting::findOrFail(1);

        $subject = $request->subject;
        $to = $gs->email;
        $from = $user->email;
        $msg = "Email: ".$from."\nMessage: ".$request->message;
        // if($gs->is_smtp == 1)
        // {
        //     $data = [
        //     'to' => $to,
        //     'subject' => $subject,
        //     'body' => $msg,
        // ];

        // $mailer = new GeniusMailer();
        // $mailer->sendCustomMail($data);
        // }
        // else
        // {
        //     $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
        // mail($to,$subject,$msg,$headers);
        // }
        if($request->type == 'Ticket'){
            $conv = AdminUserConversation::where('type','=','Ticket')->where('user_id','=',$user->id)->where('subject','=',$subject)->first(); 
        }
        else{
            $conv = AdminUserConversation::where('type','=','Dispute')->where('user_id','=',$user->id)->where('order_number','=',$request->order)->first(); 
        }

        if(isset($conv)){
            $data = 1;
            $msg = new AdminUserMessage();
            $msg->conversation_id = $conv->id;
            $msg->message = $request->message;
            $msg->user_id = $user->id;
            $msg->save();
            return response()->json($data);   
        }
        else{
            if ($request->type == 'Dispute') {
                $checkOrder = Order::where('user_id', '=', $user->id)->where('order_number', $request->order)->first();
                // dd($checkOrder);
                $data = [];
                if($checkOrder === '' || $checkOrder === null){
                    $data['msg'] = 'Nomor Order Tidak Ditemukan';
                    return response()->json($data);
                }else{
                    if ($checkOrder <> '' && $checkOrder->status !== 'on delivery') {
                        $data['msg'] = 'Pesanan Harus Dalam Pengiriman';
                        return response()->json($data);                    
                    }else{
                        $checkOrder->is_comlain = '1';
                        $checkOrder->save();
                        $data = 1;
                        $message = new AdminUserConversation();
                        $message->subject = $subject;
                        $message->user_id= $user->id;
                        $message->vendor_id = $checkOrder->vendororders[0]->user->id;
                        $message->message = $request->message;
                        $message->order_number = $request->order;
                        $message->type = $request->type;
                        $message->save();
                        $notification = new Notification;
                        $notification->conversation_id = $message->id;
                        $notification->save();
                        $msg = new AdminUserMessage();
                        $msg->conversation_id = $message->id;
                        $msg->message = $request->message;
                        $msg->user_id = $user->id;
                        $msg->save();

                        if($gs->is_smtp == 1){
                            $notifVendor = [
                            'to' => $checkOrder->vendororders[0]->user->email,
                            'subject' => 'Komplain Barang - '.$subject,
                            'body' => 'Customer Komplain Atas Nomor Order #'.$checkOrder->order_number.'. Silahkan Cek Vendor Panel Anda',
                            ];

                            $mailer = new GeniusMailer();
                            $mailer->sendCustomMail($notifVendor);
                        }

                        return response()->json($data);   
                    }
                }
            }else{
                $data = 1;
                $message = new AdminUserConversation();
                $message->subject = $subject;
                $message->user_id= $user->id;
                $message->message = $request->message;
                $message->order_number = $request->order;
                $message->type = $request->type;
                $message->save();
                $notification = new Notification;
                $notification->conversation_id = $message->id;
                $notification->save();
                $msg = new AdminUserMessage();
                $msg->conversation_id = $message->id;
                $msg->message = $request->message;
                $msg->user_id = $user->id;
                $msg->save();
                return response()->json($data);   
            }
        }
}
}
