<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_balances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('walletable_id')->nullable();
            $table->string('walletable_type', 255)->nullable();
            $table->string('user_type')->nullable();
            $table->bigInteger('user_id');
            $table->string('type')->nullable();
            $table->decimal('amount', 28,2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_details')->nullable();
            $table->string('txn_id')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('wallet_balances');
    }
}
