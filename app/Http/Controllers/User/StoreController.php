<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\GeniusMailer;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Generalsetting;
use App\Models\DropshipProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Session;
use App\Models\Currency;
use App\Models\ProductClick;
use Carbon\Carbon;

class StoreController extends Controller
{

    public function index(Request $request,$slug)
    {
        // dd('hello');
        $this->code_image();
       //$sort = "";
        $minprice = $request->min;
        $maxprice = $request->max;
        $sort = $request->sort;
        // $string = str_replace('-',' ', $slug);
        $string = 'irfaneli';
        // dd($string);
        $vendor = User::where('shop_name','=',$string)->orWhere('slug_shop_name', '=', $string)->firstOrFail();
        $data['vendor'] = $vendor;
        $data['services'] = DB::table('services')->where('user_id','=',$vendor->id)->get();
        // $oldcats = $vendor->products()->where('status','=',1)->orderBy('id','desc')->get();
        // $vprods = (new Collection(Product::filterProducts($oldcats)))->paginate(9);

        // Search By Price
        // $prods = Product::orderBy('is_highlight', 'DESC')->when($minprice, function($query, $minprice) {
        //                               return $query->where('price', '>=', $minprice);
        //                             })
        //                             ->when($maxprice, function($query, $maxprice) {
        //                               return $query->where('price', '<=', $maxprice);
        //                             })
        //                              ->when($sort, function ($query, $sort) {
        //                                 if ($sort=='date_desc') {
        //                                   return $query->orderBy('id', 'DESC');
        //                                 }
        //                                 elseif($sort=='date_asc') {
        //                                   return $query->orderBy('id', 'ASC');
        //                                 }
        //                                 elseif($sort=='price_desc') {
        //                                   return $query->orderBy('price', 'DESC');
        //                                 }
        //                                 elseif($sort=='price_asc') {
        //                                   return $query->orderBy('price', 'ASC');
        //                                 }
        //                              })
        //                             ->when(empty($sort), function ($query, $sort) {
        //                                 return $query->orderBy('id', 'DESC');
        //                             })->where('status', 1)->where('status_verified', '1')->where('user_id', $vendor->id)->get();

        // $vprods = (new Collection(Product::filterProducts($prods)));
        // $dropships = (new Collection(DropshipProduct::where('shop_name','=',$string)->orWhere('slug_shop_name', '=', $string)->get()));
        // $vprods = $vprods->merge($dropships)->paginate(15);
        // $data['vprods'] = $vprods;
        $prods = Product::orderBy('is_highlight', 'DESC')->when($minprice, function($query, $minprice) {
          return $query->where('price', '>=', $minprice);
                })
                ->when($maxprice, function($query, $maxprice) {
                  return $query->where('price', '<=', $maxprice);
                })
                ->when($sort, function ($query, $sort) {
                    if ($sort=='date_desc') {
                      return $query->orderBy('id', 'DESC');
                    }
                    elseif($sort=='date_asc') {
                      return $query->orderBy('id', 'ASC');
                    }
                    elseif($sort=='price_desc') {
                      return $query->orderBy('price', 'DESC');
                    }
                    elseif($sort=='price_asc') {
                      return $query->orderBy('price', 'ASC');
                    }
                })
                ->when(empty($sort), function ($query, $sort) {
                    return $query->orderBy('id', 'DESC');
                })->where('status', 1)->where('status_verified', '1')->where('user_id', $vendor->id)->get();

        $vprods = (new Collection(Product::filterProducts($prods)));
        $dropships = (new Collection(DropshipProduct::where('shop_name','=',$string)->orWhere('slug_shop_name', '=', $string)->get()));
        
        if ($sort=='date_desc') {
            $vprods = $vprods->merge($dropships)->sortByDesc('id')->paginate(15);
        }
        elseif($sort=='date_asc') {
            $vprods = $vprods->merge($dropships)->sortBy('id')->paginate(15);
        }
        elseif($sort=='price_desc') {
            $vprods = $vprods->merge($dropships)->sortByDesc('price')->paginate(15);
        }
        elseif($sort=='price_asc') {
            $vprods = $vprods->merge($dropships)->sortBy('price')->paginate(15);
        }else{
            $vprods = $vprods->merge($dropships)->paginate(15);
        }

        
        $data['vprods'] = $vprods;
        return view('user.store.index', $data);
    }

    public function vendorsubs(Request $request)
    {
        $domain = $_SERVER['SERVER_NAME'];
        
        $user = User::where('domain_name', $domain)->where('is_vendor', 2)->firstOrFail();

        // dd($user);

        if (config('dropship.is_dropship') !== TRUE) {
            return abort(404);
        }

        $this->code_image();
       //$sort = "";
        $minprice = $request->min;
        $maxprice = $request->max;
        $sort = $request->sort;
        // $string = str_replace('-',' ', $slug);
        $string = $user->slug_shop_name;
        // dd($string);
        $vendor = $user;
        
        $data['services'] = DB::table('services')->where('user_id','=',$vendor->id)->get();
        // $oldcats = $vendor->products()->where('status','=',1)->orderBy('id','desc')->get();
        // $vprods = (new Collection(Product::filterProducts($oldcats)))->paginate(9);

        // Search By Price
        // $prods = Product::orderBy('is_highlight', 'DESC')->when($minprice, function($query, $minprice) {
        //                               return $query->where('price', '>=', $minprice);
        //                             })
        //                             ->when($maxprice, function($query, $maxprice) {
        //                               return $query->where('price', '<=', $maxprice);
        //                             })
        //                              ->when($sort, function ($query, $sort) {
        //                                 if ($sort=='date_desc') {
        //                                   return $query->orderBy('id', 'DESC');
        //                                 }
        //                                 elseif($sort=='date_asc') {
        //                                   return $query->orderBy('id', 'ASC');
        //                                 }
        //                                 elseif($sort=='price_desc') {
        //                                   return $query->orderBy('price', 'DESC');
        //                                 }
        //                                 elseif($sort=='price_asc') {
        //                                   return $query->orderBy('price', 'ASC');
        //                                 }
        //                              })
        //                             ->when(empty($sort), function ($query, $sort) {
        //                                 return $query->orderBy('id', 'DESC');
        //                             })->where('status', 1)->where('status_verified', '1')->where('user_id', $vendor->id)->get();

        // $vprods = (new Collection(Product::filterProducts($prods)));
        // $dropships = (new Collection(DropshipProduct::where('shop_name','=',$string)->orWhere('slug_shop_name', '=', $string)->get()));
        // $vprods = $vprods->merge($dropships)->paginate(15);
        // $data['vprods'] = $vprods;
        $prods = Product::orderBy('is_highlight', 'DESC')->when($minprice, function($query, $minprice) {
          return $query->where('price', '>=', $minprice);
                })
                ->when($maxprice, function($query, $maxprice) {
                  return $query->where('price', '<=', $maxprice);
                })
                ->when($sort, function ($query, $sort) {
                    if ($sort=='date_desc') {
                      return $query->orderBy('id', 'DESC');
                    }
                    elseif($sort=='date_asc') {
                      return $query->orderBy('id', 'ASC');
                    }
                    elseif($sort=='price_desc') {
                      return $query->orderBy('price', 'DESC');
                    }
                    elseif($sort=='price_asc') {
                      return $query->orderBy('price', 'ASC');
                    }
                })
                ->when(empty($sort), function ($query, $sort) {
                    return $query->orderBy('id', 'DESC');
                })->where('status', 1)->where('status_verified', '1')->where('user_id', $vendor->id)->get();

        $vprods = (new Collection(Product::filterProducts($prods)));
        $dropships = (new Collection(DropshipProduct::where('dropshipper_id',$vendor->id)->get()));
        
        if ($sort=='date_desc') {
            $vprods = $vprods->merge($dropships)->sortByDesc('id')->paginate(15);
        }
        elseif($sort=='date_asc') {
            $vprods = $vprods->merge($dropships)->sortBy('id')->paginate(15);
        }
        elseif($sort=='price_desc') {
            $vprods = $vprods->merge($dropships)->sortByDesc('price')->paginate(15);
        }
        elseif($sort=='price_asc') {
            $vprods = $vprods->merge($dropships)->sortBy('price')->paginate(15);
        }else{
            $vprods = $vprods->merge($dropships)->paginate(15);
        }

        $last_view = [];
        $data['last_view'] = $last_view;
        $data['vprods'] = $vprods;
        $data['vendor'] = $user;


        // dd($data);
        return view('user.store.index', $data);
    }    
    
    public function product($subdomain, $domain, $slug)
    {
        $domain = $_SERVER['HTTP_HOST'];
        $user = User::where('domain_name', $domain)->where('is_vendor', 2)->firstOrFail();

        if ($user->subscribes->is_dropship !== '1') {
            return abort(404);
        }


        $data['vendor'] = $user;
        $data['services'] = DB::table('services')->where('user_id','=',$user->id)->get();
        $data['subdomain'] = $user->slug_shop_name;
        $this->code_image();
        $productt = Product::where('slug', '=', $slug)->firstOrFail();
        $productt->views += 1;
        $productt->update();
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }
        $product_click = new ProductClick;
        $product_click->product_id = $productt->id;
        $product_click->date = Carbon::now()->format('Y-m-d');
        $product_click->save();

        if ($productt->user_id != 0) {
            $vendors = Product::where('status', '=', 1)->where('user_id', '=', $user->id)->take(8)->get();
        } else {
            $vendors = Product::where('status', '=', 1)->where('user_id', '=', 0)->take(8)->get();
        }

        $kota = DB::table('users')
        ->where('users.id', '=', $productt->user_id)
        ->leftJoin('regencies', 'users.city_id', '=', 'regencies.id')
        ->first();
        

        return view('user.store.product', compact('productt', 'curr', 'vendors', 'user', 'kota'), $data);

    }

    private function  code_image()
    {
        $actual_path = str_replace('project','',base_path());
        $image = imagecreatetruecolor(200, 50);
        $background_color = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image,0,0,200,50,$background_color);

        $pixel = imagecolorallocate($image, 0,0,255);
        for($i=0;$i<500;$i++)
        {
            imagesetpixel($image,rand()%200,rand()%50,$pixel);
        }

        $font = $actual_path.'assets/front/fonts/NotoSans-Bold.ttf';
        $allowed_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $length = strlen($allowed_letters);
        $letter = $allowed_letters[rand(0, $length-1)];
        $word='';
        //$text_color = imagecolorallocate($image, 8, 186, 239);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $cap_length=6;// No. of character in image
        for ($i = 0; $i< $cap_length;$i++)
        {
            $letter = $allowed_letters[rand(0, $length-1)];
            imagettftext($image, 25, 1, 35+($i*25), 35, $text_color, $font, $letter);
            $word.=$letter;
        }
        $pixels = imagecolorallocate($image, 8, 186, 239);
        for($i=0;$i<500;$i++)
        {
            imagesetpixel($image,rand()%200,rand()%50,$pixels);
        }
        session(['captcha_string' => $word]);
        imagepng($image, $actual_path."assets/images/capcha_code.png");
    }

}