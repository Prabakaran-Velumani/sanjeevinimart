<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellerReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_reviews', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_id');
            $table->bigInteger('order_id');
            $table->bigInteger('customer_id');
            $table->integer('rating');
            $table->text('review');
            $table->boolean('is_anonymous')->default(0);
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('seller_reviews');
    }
}
