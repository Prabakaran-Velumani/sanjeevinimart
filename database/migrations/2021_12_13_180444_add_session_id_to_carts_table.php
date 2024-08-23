<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSessionIdToCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('user_id');
        });
        
        // DB::statement("ALTER TABLE carts CHANGE user_id user_id BIGINT(20) UNSIGNED NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE carts ALTER COLUMN user_id TYPE BIGINT USING user_id::BIGINT;");
        DB::statement("ALTER TABLE carts ALTER COLUMN user_id DROP NOT NULL;");
        DB::statement("ALTER TABLE carts ALTER COLUMN user_id SET DEFAULT NULL;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('session_id');
        });

    }
}
