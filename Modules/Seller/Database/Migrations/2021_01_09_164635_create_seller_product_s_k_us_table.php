<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellerProductSKUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_product_s_k_us', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('product_id');
            $table->string('product_sku_id')->nullable();
            $table->decimal('product_stock', 16, 2)->default(0);
            $table->decimal('purchase_price', 16, 2)->default(0)->nullable();
            $table->decimal('selling_price', 16, 2)->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')->on('seller_products')
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
        Schema::dropIfExists('seller_product_s_k_us');
    }
}
