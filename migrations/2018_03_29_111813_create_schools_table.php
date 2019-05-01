<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSchoolsTable.
 */
class CreateSchoolsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('schools', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
                        $table->string('short_name')->unique();
                        $table->string('address')->unique();
                         $table->string('place');
                          $table->string('voivodeship');
            $table->text('description');
            $table->string('website');
            $table->string('avatar');
            $table->integer('user_id')->nullable()->unique();
                    $table->string('stripe_id')->nullable();
                     $table->string('card_brand')->nullable();
                     $table->string('card_last_four')->nullable();
            $table->text('verify_key')->nullable();
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
    Schema::drop('schools');
  }
}
