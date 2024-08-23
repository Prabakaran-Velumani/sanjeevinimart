<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->string('subject');
            $table->text('description');
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('priority_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('refer_id')->nullable();
            $table->bigInteger('status_id')->default(1);
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
        Schema::dropIfExists('support_tickets');
    }
}
