<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPackageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_package_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->bigInteger('seller_id');
            $table->string('package_code')->nullable();
            $table->integer('number_of_product');
            $table->decimal('shipping_cost', 16, 2)->nullable();
            $table->string('shipping_date')->nullable();
            $table->bigInteger('shipping_method')->nullable();
            $table->integer('is_cancelled')->default(0);
            $table->integer('cancel_reason_id')->nullable();
            $table->integer('is_reviewed')->default(0);
            $table->bigInteger('delivery_status')->default(1);
            $table->bigInteger('last_updated_by')->nullable();
            $table->boolean('gst_claimed')->default(0);
            $table->decimal('tax_amount', 16, 2)->default(0)->nullable();
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
        Schema::dropIfExists('order_package_details');
    }
}
