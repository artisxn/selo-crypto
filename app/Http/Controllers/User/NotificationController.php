<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');   
    }
    
    public function index()
    {
        $user = Auth::user();
        $notif = Notification::where('user_id', $user->id)
                ->where('is_customer', '1')
                ->orderBy('created_at', 'desc')
                ->get();

        if ($notif->count() > 0) {
            Notification::where('user_id', $user->id)
                ->where('is_customer', '1')->update(['is_read' => 1]);
        }

        return view('user.notification.index', compact('notif'));
    }

    public function notifCount()
    {
        $user = Auth::user();
        $notif = new Notification;
        $notif = $notif->countCustomer($user->id);
        
        return response()->json($notif);
    }

    public function clearNotif()
    {
        $user = Auth::user();
        $notif = Notification::where('user_id', $user->id)->where('is_customer', '=', '1')->where('is_read', '=', 1);
        $notif->delete();

        return redirect()->back();
    }
}
