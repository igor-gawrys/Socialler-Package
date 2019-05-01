<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateDaysTable.
 */
class CreateDaysTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('days', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
						$table->text('description');
						$table->string('avatar');
						$table->integer('schedule_id');
						$table->integer('day_number');
							  $table->integer('order')->default(1);
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
		Schema::drop('days');
	}
}
