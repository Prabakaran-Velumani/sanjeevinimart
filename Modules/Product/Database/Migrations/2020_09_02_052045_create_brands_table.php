<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255);
            $table->string("logo", 255)->nullable();
            $table->text("description")->nullable();
            $table->string("link", 255)->nullable();
            $table->boolean("status")->default(1);
            $table->boolean("featured")->default(0);
            $table->text("meta_title")->nullable();
            $table->text("meta_description")->nullable();
            $table->bigInteger("sort_id")->nullable();
            $table->bigInteger('total_sale')->default(0);
            $table->decimal('avg_rating', 8,2)->default(0);
            $table->string("slug", 255)->nullable();
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
        Schema::dropIfExists('brands');
    }
}
