<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{

    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('order_payment_id')->nullable();
            $table->string('order_type')->nullable();
            $table->string('order_number')->unique();
            $table->bigInteger('payment_type');
            $table->integer('is_paid')->default(0);
            $table->integer('is_confirmed')->default(0);
            $table->integer('is_completed')->default(0);
            $table->integer('is_cancelled')->default(0);
            $table->integer('cancel_reason_id')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->bigInteger('customer_shipping_address')->nullable();
            $table->bigInteger('customer_billing_address')->nullable();
            $table->integer('number_of_package')->nullable();
            $table->decimal('grand_total', 16, 2)->nullable();
            $table->decimal('sub_total', 16, 2)->nullable();
            $table->decimal('discount_total', 16, 2)->nullable();
            $table->decimal('shipping_total', 16, 2)->nullable();
            $table->integer('number_of_item')->nullable();
            $table->integer('order_status')->nullable();
            $table->decimal('tax_amount', 16, 2)->default(0)->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
