<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackendmenuUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backendmenu_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('backendmenu_id');
            $table->bigInteger('user_id');
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('position')->default(0);
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('backendmenu_users');
    }
}
