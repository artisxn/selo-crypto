<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = Auth::user()->id;
        $invoices = Invoice::where('user_id', $user_id)->where('is_subscription', '0')->get();
        return view('user.invoice.index',compact('invoices'));
    }

    public function detail($id)
    {
        $user = Auth::guard('web')->user();
        $invoice = Invoice::where('user_id', $user->id)->where('id', $id)->orWhere('invoice_number', base64_decode($id))->firstOrFail();
        return view('user.invoice.detail',compact('invoice'));
    }

    public function print($id)
    {
        $user = Auth::guard('web')->user();
        $invoice = Invoice::where('user_id', $user->id)->where('id', $id)->orWhere('invoice_number', base64_decode($id))->firstOrFail();
        $order = Order::where('invoice_id', $invoice->id);


        return view('user.invoice.print',compact('invoice', 'order'));
    }
}
