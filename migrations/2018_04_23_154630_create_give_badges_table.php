<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateGiveBadgesTable.
 */
class CreateGiveBadgesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('give_badges', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('badge_id');
            $table->integer('user_id');
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
		Schema::drop('give_badges');
	}
}
