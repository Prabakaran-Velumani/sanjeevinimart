<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("product_id");
            $table->foreign("product_id")->on("products")->references("id")->onDelete("cascade");
            $table->bigInteger("product_sku_id")->nullable();
            $table->bigInteger("attribute_id")->nullable();
            $table->bigInteger("attribute_value_id")->nullable();
            $table->bigInteger("created_by")->nullable();
            $table->foreign("created_by")->on("users")->references("id")->onDelete("cascade");
            $table->bigInteger("updated_by")->nullable();
            $table->foreign("updated_by")->on("users")->references("id")->onDelete("cascade");
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
        Schema::dropIfExists('product_variations');
    }
}
