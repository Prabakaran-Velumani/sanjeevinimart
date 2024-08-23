<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->bigInteger('seller_id');
            $table->bigInteger('product_id');
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('package_id')->nullable();
            $table->string('type')->default('product');
            $table->text('review')->nullable();
            $table->integer('rating');
            $table->boolean('is_anonymous')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
            
            $table->foreign('customer_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('seller_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('product_reviews');
    }
}
