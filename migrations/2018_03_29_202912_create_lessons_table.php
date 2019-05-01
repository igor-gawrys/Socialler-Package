<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateLessonsTable.
 */
class CreateLessonsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('lessons', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('teacher_id');
            $table->integer('room_id');
            $table->integer('day_id');
            $table->integer('schedule_id');
            $table->integer('status')->default(1);
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
    Schema::drop('lessons');
  }
}
