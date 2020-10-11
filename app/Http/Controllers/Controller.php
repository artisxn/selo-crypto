<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DB;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function rp($angka = 0)
    {
        return number_format($angka, 0, ',', '.');
    }

    public function feeCompany($amount = 0)
    {
        $feeCompany = $amount - $this->feeVendor($amount);

        return round($feeCompany, 2);
    }

    public function feeVendor($amount = 0)
    {
        $gs = DB::table('generalsettings')->find(1);
        $rumus = 0;

        $rumus = (100 + (int) $gs->rate_company) / 100;

        $amount = $amount / $rumus;

        return round($amount, 2);
    }

    public function feeDropshipper($amount = 0)
    {
        $gs = DB::table('generalsettings')->find(1);
        $rumus = 0;
        $feeDropshipper = 0;
        $feeCompany = $this->feeCompany($amount);
        

        $rumus = (int) $gs->rate_dropship / 100;
        // $feeDropshipper = $amount * $rumus;

        $amount = $feeCompany * $rumus;

        return round($amount, 2);
    }
}
