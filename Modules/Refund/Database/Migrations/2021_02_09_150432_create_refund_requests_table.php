<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->bigInteger('order_id');
            $table->string('refund_method')->nullable();
            $table->string('shipping_method')->nullable();
            $table->bigInteger('shipping_method_id')->nullable();
            $table->bigInteger('pick_up_address_id')->nullable();
            $table->string('drop_off_address')->nullable();
            $table->string('additional_info')->nullable();
            $table->decimal('total_return_amount', 28,2)->default(0);
            $table->Integer('refund_state')->default(1);
            $table->boolean('is_confirmed')->default(0);
            $table->boolean('is_refunded')->default(0);
            $table->boolean('is_completed')->default(0);
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
        Schema::dropIfExists('refund_requests');
    }
}
