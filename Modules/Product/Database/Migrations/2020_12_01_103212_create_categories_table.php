<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->bigInteger('parent_id');
            $table->integer('depth_level')->default(1);
            $table->string('icon')->nullable();
            $table->boolean('searchable')->default(1);
            $table->boolean('status')->default(1);
            $table->bigInteger('total_sale')->default(0);
            $table->decimal('avg_rating', 8,2)->default(0);
            $table->decimal('commission_rate', 16,2)->nullable();
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
        Schema::dropIfExists('categories');
    }
}
