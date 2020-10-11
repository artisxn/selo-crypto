<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorecolorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storecolors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('header_color', 15)->nullable();
            $table->string('primary_color', 15)->nullable();
            $table->string('footer_color', 15)->nullable();
            $table->string('copyright_color', 15)->nullable();
            $table->string('menu_color', 15)->nullable();
            $table->string('menuhover_color', 15)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storecolors');
    }
}
