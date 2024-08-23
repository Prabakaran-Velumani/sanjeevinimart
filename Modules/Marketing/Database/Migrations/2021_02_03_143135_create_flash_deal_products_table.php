<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlashDealProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flash_deal_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('flash_deal_id');
            $table->bigInteger('seller_product_id');
            $table->decimal('discount', 16, 2);
            $table->unsignedTinyInteger('discount_type');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->foreign('flash_deal_id')->references('id')->on('flash_deals')->onDelete('cascade');
            $table->foreign('seller_product_id')->references('id')->on('seller_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flash_deal_products');
    }
}
