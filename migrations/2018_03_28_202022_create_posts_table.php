<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePostsTable.
 */
class CreatePostsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('posts', function(Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->json('images');
      $table->json('videos');
      $table->json('files');
            $table->integer('user_id');
              $table->integer('school_id')->nullable();
            $table->integer('grade_id')->nullable();
            $table->integer('order')->default(1);
            $table->softDeletes();
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
    Schema::drop('posts');
  }
}
