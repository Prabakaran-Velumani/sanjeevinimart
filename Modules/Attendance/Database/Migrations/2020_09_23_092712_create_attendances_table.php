<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('attendance', 50)->default('P');
            $table->date('date')->nullable();
            $table->string('day',30)->nullable();
            $table->string('month',30)->nullable();
            $table->Integer('year')->nullable();
            $table->string('note', 255)->nullable();
            $table->Integer('user_id')->default(1);
            $table->Integer('role_id')->default(1);
            $table->bigInteger("created_by")->nullable();
            $table->foreign("created_by")->on("users")->references("id");
            $table->bigInteger("updated_by")->nullable();
            $table->foreign("updated_by")->on("users")->references("id");
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
        Schema::dropIfExists('attendances');
    }
}
