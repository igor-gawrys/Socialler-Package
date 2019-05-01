<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateQuestionsTable.
 */
class CreateQuestionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('questions', function(Blueprint $table) {
            $table->increments('id');
            $table->text('image')->nullable();
            $table->text('content');
            $table->json('answers');
            $table->integer('quiz_id');
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
    Schema::drop('questions');
  }
}
