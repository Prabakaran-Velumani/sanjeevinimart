<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogCommentReplaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_comment_replays', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('blog_post_id');
            $table->bigInteger('user_id');
            $table->bigInteger('blog_comment_id');
            $table->bigInteger('replay_id')->default(0);
            $table->text('replay');
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
        Schema::dropIfExists('blog_comment_replays');
    }
}
