<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultiMegaMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multi_mega_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->bigInteger('multi_mega_menu_id');
            $table->bigInteger('menu_id');
            $table->integer('position')->default(768989);
            $table->boolean('is_newtab')->default(0);
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
        Schema::dropIfExists('multi_mega_menus');
    }
}
