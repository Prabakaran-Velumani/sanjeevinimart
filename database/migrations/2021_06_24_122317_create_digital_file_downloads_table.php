<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalFileDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_file_downloads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('seller_id')->default(0);
            $table->bigInteger('order_id')->default(0);
            $table->bigInteger('package_id')->default(0);
            $table->bigInteger('seller_product_sku_id')->default(0);
            $table->bigInteger('product_sku_id')->default(0);
            $table->bigInteger('download_limit')->default(0);
            $table->bigInteger('downloaded_count')->default(0);
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
        Schema::dropIfExists('digital_file_downloads');
    }
}
