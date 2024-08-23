<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuColumnsTable extends Migration
{
    
    public function up()
    {
        Schema::create('menu_columns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('menu_id');
            $table->string('column');
            $table->string('size');
            $table->bigInteger('parent_id')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('menu_columns');
    }
}
