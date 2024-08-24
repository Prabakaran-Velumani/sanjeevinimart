<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255);
            $table->string("display_type", 70)->nullable();
            $table->text("description")->nullable();
            $table->tinyInteger("status")->default(0);
            $table->bigInteger("created_by")->nullable();
            $table->foreign("created_by")->on("users")->references("id")->onDelete("cascade");
            $table->bigInteger("updated_by")->nullable();
            $table->foreign("updated_by")->on("users")->references("id")->onDelete("cascade");
            $table->timestamps();
        });

        DB::statement("INSERT INTO attributes (id, name, display_type, description, status, created_at, updated_at) VALUES
        (1, 'Color', 'radio_button', 'this is for color atrribute.', 1, '2018-11-05 02:12:26', '2018-11-05 02:12:26')");

        // Set the auto-increment value based on the last inserted ID
        DB::statement("SELECT setval('attributes_id_seq', (SELECT COALESCE(MAX(id) + 1, 1) FROM attributes), true);");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attributes');
    }
}
