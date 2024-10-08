<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Modules\Appearance\Entities\Theme;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('title');
            $table->string('image');
            $table->string('version');
            $table->string('folder_path')->default('default');
            $table->string('live_link')->default('#');
            $table->text('description');
            $table->boolean('is_active');
            $table->boolean('status');
            $table->text('tags')->nullable();
            $table->timestamps();
        });


        Theme::create(['name' => 'Default', 'title' => 'Default Theme', 'version' => '1.00','live_link' => 'https://amaz.rishfa.com','folder_path' => 'default',
         'image' => 'frontend/default/img/amazcart.jpg', 'description' => 'initial description', 'is_active' => 0, 'status'=> 1]);

        $amazy_theme = Theme::where('name', 'Amazy')->first();
        if(!$amazy_theme){
            Theme::create([
                'name' => 'Amazy',
                'image' => '/frontend/amazy/img/amazy.jpg',
                'version' => '1.0.0',
                'folder_path' => 'amazy',
                'live_link' => 'http://amazy.rishfa.com/',
                'description' => 'Amazy theme description',
                'is_active' => 1,
                'status' => 1,
                'tags' => 'amazy',
                'title'=> 'Amazy',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('themes');
    }
}
