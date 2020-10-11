<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Dropship;
use App\Models\DropshipProduct;
use Auth;
use Carbon\Carbon;
use Datatables;
use DB;
use Illuminate\Support\Facades\Input;
use Image;
use Session;
use Validator;


class DropshipController extends Controller
{
    public $global_language;

  
    public function __construct()
    {
        $this->middleware('auth');

            if (Session::has('language')) 
            {
                $data = DB::table('languages')->find(Session::get('language'));
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $this->vendor_language = json_decode($data_results);
            }
            else
            {
                $data = DB::table('languages')->where('is_default','=',1)->first();
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $this->vendor_language = json_decode($data_results);
            } 

    }


    public function createDropship(){

        $is_dropship = DB::table("user_subscriptions")
                    ->select("subscriptions.is_dropship")
                    ->leftJoin('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
                    ->where('user_subscriptions.user_id', Auth::id())
                    ->get()
                    ->toArray();
        


        if($is_dropship[0]->is_dropship==1){
            $data = Product::where('product_type','=','normal')->orderBy('id','desc')->get();
            return view('vendor.dropship.create',['products' => $data]);
        } else {
            return view('vendor.dropship.block');

        }

        
    }


    public function showProduct(Request $request){
        $data=[];
        $userId = Auth::id();


        if($request->has('q')){
            $search = $request->q;
            $data = DB::table("products")
                    ->select("id","name")
                    ->where('product_type','=','normal')
                    ->where('user_id','!=',$userId)
            		->where('name','LIKE',"%$search%")
            		->get();
        }

        return response()->json($data);

    }

    public function storeDropship($item){
        $user = Auth::user();
        // $item = Input::get('itemName');

        // $is_dropship = DB::table("user_subscriptions")
        // ->select("subscriptions.is_dropship")
        // ->leftJoin('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
        // ->where('user_subscriptions.user_id', Auth::id())
        // ->get()
        // ->toArray();


        if(Auth::user()->subscribes->is_dropship === '1'){
            $product = Product::where('user_id', '!=', $user->id)->where('id', $item)->first();
            
            if ($product <> '') {
                $exists = Dropship::where('product_id', $product->id)->where('user_id', $user->id)->first();

                if ($exists <> '') {
                    return response()->json(['0','Gagal, Produk Dropship Telah Tersedia'], 200);
                }else{
                    $dropship = new Dropship;
                    $dropship->user_id = $user->id;
                    $dropship->product_id = $product->id;
                    $dropship->vendor_id = $product->user_id;

                    $dropship->save();

                    return response()->json(['1','Berhasil Menambahkan Ke Daftar Produk Dropship'], 200);
                }                
            }else{
                return response()->json(['0','Gagal, Tidak Boleh Produk Anda'], 200);
            } 
        }

        return abort(404);

      
    }


    public function indexDropship(){
        $user = Auth::user();

        $is_dropship = DB::table("user_subscriptions")
        ->select("subscriptions.is_dropship")
        ->leftJoin('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
        ->where('user_subscriptions.user_id', Auth::user()->id)
        ->get()
        ->toArray();


        if(count($is_dropship) !==0){
            if($is_dropship[0]->is_dropship==1){
                return view('vendor.dropship.index');
            }else {
                return view('vendor.dropship.block');
    
            }
        } else {
            return view('vendor.dropship.block');
        }
        
        
    }


     //*** JSON Request
        public function datatables(){
        $userId = Auth::id();  
        $datas = DropshipProduct::where('dropshipper_id', $userId)->get();


            // dd($datas);


        return Datatables::of($datas)
        
        ->editColumn('name', function ($datas) {
            $name = mb_strlen(strip_tags($datas->name),'utf-8') > 50 ? mb_substr(strip_tags($datas->name),0,50,'utf-8').'...' : strip_tags($datas->name);
                                $id = '<small>Product ID: <a href="'.route('front.product', $datas->slug).'" target="_blank">'.sprintf("%'.08d",$datas->id).'</a></small>';
                                return  $name.'<br>'.$id;
         })

         ->editColumn('category', function ($datas) {
            $cat = "";
            $cat .= $datas->category->name;
            $cat .= $datas->subcategory <> '' ? " > ".$datas->subcategory->name : null;
            $cat .= $datas->childcategory <> '' ? $datas->childcategory->name : null;

            return $cat;
         })

         ->editColumn('price', function($datas) {
            $sign = Currency::where('is_default','=',1)->first();
            $price = round($datas->publish_price <> '' ? $datas->publish_price : $datas->price  * $sign->value , 2);
            $price = $sign->sign.$price ;
            return  $price;
        })


        ->addColumn('action', function($datas) {
            return '<div class="action-list"><a href="javascript:;" data-href="' . route('vendor-dropship-delete',$datas->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
        })

        ->rawColumns(['name', 'status', 'action'])
        ->make(true);
     }



     public function productDropship(){
        $userId = Auth::id();  
        $datas = DB::table('products')
            ->select("products.*")
            ->where('user_id','!=',$userId)
            ->where('product_type','normal')
            ->orderBy('id','desc')
            ->get();
            // dd($datas);


        return Datatables::of($datas)
        
        ->editColumn('name', function ($datas) {
            $name = mb_strlen(strip_tags($datas->name),'utf-8') > 50 ? mb_substr(strip_tags($datas->name),0,50,'utf-8').'...' : strip_tags($datas->name);
                                $id = '<small>Product ID: <a href="'.route('front.product', $datas->slug).'" target="_blank">'.sprintf("%'.08d",$datas->id).'</a></small>';
                                return  $name.'<br>'.$id;
         })

         ->editColumn('price', function($datas) {
            $sign = Currency::where('is_default','=',1)->first();
            $price = round($datas->publish_price <> '' ? $datas->publish_price : $datas->price  * $sign->value , 2);
            $price = $sign->sign.$price ;
            return  $price;
        })


        ->addColumn('action', function($datas) {
            return '<div class="action-list"><a href="javascript:;" data-href="' . route('vendor-dropship-store',$datas->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-plus"></i></a></div>';
        })

        ->rawColumns(['name', 'status', 'action'])
        ->make(true);
     }
     
     public function destroyDropship($id){
        $userId = Auth::id();
        $delete = DB::table('dropships')
        ->where('product_id', $id)
        ->where('user_id',$userId)
        ->delete();

        if($delete){
            echo "Data Has Been Deleted!";
        }
     }
}
