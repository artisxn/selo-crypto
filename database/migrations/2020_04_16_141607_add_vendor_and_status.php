<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorAndStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_user_conversations', function (Blueprint $table) {
            $table->integer('vendor_id')->nullable();
            $table->enum('status', ['0', '1'])->default('0')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_user_conversations', function (Blueprint $table) {
            $table->dropColumn('vendor_id');
            $table->dropColumn('status');
        });
    }
}
