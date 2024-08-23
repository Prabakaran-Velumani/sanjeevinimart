<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('package_id');
            $table->string('type',50)->nullable();
            $table->bigInteger('product_sku_id');
            $table->integer('qty')->default(0);
            $table->decimal('price', 16, 2)->default(0);
            $table->decimal('total_price', 16, 2)->default(0);
            $table->decimal('tax_amount', 16, 2)->default(0);
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
        Schema::dropIfExists('order_product_details');
    }
}
