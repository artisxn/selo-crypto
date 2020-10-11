<?php

namespace App\Providers;

use App\Classes\GeniusMailer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set('Asia/Jakarta');
        Blade::directive('rp', function ($expression) {
            return "<?php echo number_format($expression, 0, ',', '.'); ?>";
        });

        Blade::directive('feeCompany', function ($amount = 0) {
            $feeCompany = $amount - $this->feeVendor($amount);

            return round($feeCompany, 2);
        });
        
        Blade::directive('feeVendor', function ($amount = 0) {
            $gs = DB::table('generalsettings')->find(1);
            $rumus = 0;
    
            $rumus = (100 + (int) $gs->rate_company) / 100;
    
            $amount = $amount / $rumus;
    
            return round($amount, 2);
        });
        
        Blade::directive('feeDropshipper', function ($amount = 0) {
            $gs = DB::table('generalsettings')->find(1);
            $rumus = 0;
            $feeDropshipper = 0;
            $feeCompany = $this->feeCompany($amount);
            
    
            $rumus = (int) $gs->rate_dropship / 100;
            // $feeDropshipper = $amount * $rumus;
    
            $amount = $feeCompany * $rumus;
    
            return round($amount, 2);
        });

        $admin_lang = DB::table('admin_languages')->where('is_default','=',1)->first();
        App::setlocale($admin_lang->name);
        view()->composer('*',function($settings){
            $settings->with('gs', DB::table('generalsettings')->find(1));
            $settings->with('seo', DB::table('seotools')->find(1));
            $settings->with('categories', Category::where('status','=',1)->get());   
            $settings->with('ps', DB::table('pagesettings')->find(1));
            $settings->with('services', DB::table('services')->where('user_id','=',0)->get() );

            if (config('dropship.is_dropship') !== FALSE) {
                $dataVendor = User::where('domain_name', $_SERVER['HTTP_HOST'])->where('is_vendor', 2)->first();
                $settings->with('vendor', $dataVendor);
            }

            if (Session::has('language')) 
            {
                $data = DB::table('languages')->find(Session::get('language'));
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $lang = json_decode($data_results);
                $settings->with('langg', $lang);
            }
            else
            {
                $data = DB::table('languages')->where('is_default','=',1)->first();
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $lang = json_decode($data_results);
                $settings->with('langg', $lang);
            }  

            if (!Session::has('popup')) 
            {
                $settings->with('visited', 1);
            }
            Session::put('popup' , 1);
             
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

    }
}
