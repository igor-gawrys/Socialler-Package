<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMessagesAllsTable.
 */
class CreateMessagesAllsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages_alls', function(Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->integer('user_id');
            $table->integer('grade_id');
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
		Schema::drop('messages_alls');
	}
}
