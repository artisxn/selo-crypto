<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGvToGeneralsettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->integer('is_gv')->default(1)->nullable();
            $table->integer('gv_merchantid')->nullable();
            $table->string('gv_merchantkey', 25)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn('is_gv');
            $table->dropColumn('gv_merchantid');
            $table->dropColumn('gv_merchantkey');
        });
    }
}
