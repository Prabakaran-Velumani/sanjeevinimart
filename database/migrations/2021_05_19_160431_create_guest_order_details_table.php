<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_order_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->string('guest_id', 255)->nullable();
            $table->string('billing_name', 255)->nullable();
            $table->string('billing_email', 255)->nullable();
            $table->string('billing_phone', 255)->nullable();
            $table->string('billing_address', 255)->nullable();
            $table->bigInteger('billing_city_id')->nullable();
            $table->bigInteger('billing_state_id')->nullable();
            $table->bigInteger('billing_country_id')->nullable();
            $table->bigInteger('billing_post_code')->nullable();
            $table->string('shipping_name', 255)->nullable();
            $table->string('shipping_email', 255)->nullable();
            $table->string('shipping_phone', 255)->nullable();
            $table->string('shipping_address', 255)->nullable();
            $table->bigInteger('shipping_city_id')->nullable();
            $table->bigInteger('shipping_state_id')->nullable();
            $table->bigInteger('shipping_country_id')->nullable();
            $table->bigInteger('shipping_post_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_order_details');
    }
}
