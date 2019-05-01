<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateGradesTable.
 */
class CreateGradesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('grades', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('description');
            $table->string('avatar');
            $table->integer('school_id');
            $table->integer('user_id');
            $table->integer('type')->nullable();
            $table->string('color')->nullable();
            $table->string('join_key')->unique();
            $table->timestamp('verifed_at')->nullable();
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
    Schema::drop('grades');
  }
}
