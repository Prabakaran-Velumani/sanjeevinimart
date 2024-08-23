<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralUsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_uses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('referral_code');
            $table->decimal('discount_amount', 16, 2);
            $table->boolean('is_use')->default(0);
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
        Schema::dropIfExists('referral_uses');
    }
}
