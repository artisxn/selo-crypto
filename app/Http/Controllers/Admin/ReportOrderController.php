<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\OrderExport;
use App\Exports\PaymentExport;
use App\Models\Order;
use Maatwebsite\Excel\Facades\Excel;

class ReportOrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request){        
        $start = date('Y-01-01');
        $end = date('Y-12-31');


        $start = $request->start_date <> '' ? $request->start_date : $start;
        $end = $request->end_date <> '' ? $request->end_date : $end;

        $orders = Order::where('status', '=', 'completed')->orderBy('created_at', 'desc')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->get();

        return view('admin.report-order.index', compact('orders'));
    }

    public function payment(Request $request)
    {
        $start = date('Y-01-01');
        $end = date('Y-12-31');


        $start = $request->start_date <> '' ? $request->start_date : $start;
        $end = $request->end_date <> '' ? $request->end_date : $end;

        $orders = Order::where('status', '=', 'completed')->orderBy('payment_vendor_date', 'desc')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->get();

        return view('admin.report-order.payment', compact('orders'));        
    }

    public function excel(Request $request){
        if($request->type == "in"){
            return Excel::download(new OrderExport(), 'Order.xlsx');
        }     
        //  dd($request->start_date);

        if($request->type == "out"){
            return Excel::download(new PaymentExport($request->start_date, $request->end_date), 'PaymentOrder.xlsx');
        }     
    }


}
