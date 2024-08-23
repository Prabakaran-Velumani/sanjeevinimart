<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInhouseOrderCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inhouse_order_carts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('seller_id');
            $table->bigInteger('product_id');
            $table->integer('qty')->default(1);
            $table->decimal('price', 16, 2)->default(0);
            $table->decimal('total_price', 16, 2)->default(0);
            $table->string('sku')->nullable();
            $table->boolean('is_select')->default(1);
            $table->bigInteger('shipping_method_id')->nullable();
            $table->timestamps();
            $table->foreign('seller_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')->on('seller_product_s_k_us')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inhouse_order_carts');
    }
}
