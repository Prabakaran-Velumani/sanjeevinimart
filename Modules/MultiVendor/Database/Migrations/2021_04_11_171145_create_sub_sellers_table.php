<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('sub_sellers')){
            Schema::create('sub_sellers', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('seller_id');
                $table->bigInteger('user_id');
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('nid')->nullable();
                $table->timestamps();
            });

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_sellers');
    }
}
