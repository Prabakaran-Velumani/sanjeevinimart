<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStatesTable extends Migration
{
    
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('country_id');
            $table->boolean('status')->default(1);
            $table->timestamps();

            
        });

        $sql_path = base_path('static_sql/states.sql');
        DB::unprepared(file_get_contents($sql_path));
        DB::statement("SELECT setval(pg_get_serial_sequence('states', 'id'), (SELECT MAX(id) FROM states) + 1);");
    }

    
    public function down()
    {
        Schema::dropIfExists('states');
    }
}
