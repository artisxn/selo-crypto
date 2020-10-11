<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $gs = DB::table('generalsettings')->where('id', 1);
        $product = DB::table('products')->where('status_verified', '0');
        $updateGs = [
            'guest_checkout' => 0,
            'cod_check' => 0,
            'fixed_commission' => 0,
            'percentage_commission' => 0,
        ];
        $updateProduct = [
            'status_verified' => '1',
            'verified_by' => 'admin@gmail.com',
            'verified_date' => date('Y-m-d'),
        ];

        if ($gs->get() <> '' && $product->get() <> '') {
            $gs->update($updateGs);
            $product->update($updateProduct);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
               
    }
}
