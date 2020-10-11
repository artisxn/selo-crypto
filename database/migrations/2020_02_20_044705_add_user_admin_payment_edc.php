<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserAdminPaymentEdc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_vendor_admin')->nullable();
            $table->string('payment_dropshipper_admin')->nullable();
            $table->string('payment_vendor_date')->nullable();
            $table->string('payment_dropshipper_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('payment_vendor_admin');
        $table->dropColumn('payment_dropshipper_admin');
        $table->dropColumn('payment_vendor_date');
        $table->dropColumn('payment_dropshipper_date');
    }
}
