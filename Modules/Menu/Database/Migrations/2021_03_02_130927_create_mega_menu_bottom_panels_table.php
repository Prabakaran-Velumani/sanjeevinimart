<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMegaMenuBottomPanelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mega_menu_bottom_panels', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->bigInteger('menu_id');
            $table->bigInteger('brand_id');
            $table->integer('position')->default(978437);
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
        Schema::dropIfExists('mega_menu_bottom_panels');
    }
}
