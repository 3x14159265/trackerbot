<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('events', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('app_id')->unsigned();
             $table->string('type');
             $table->string('event');
             $table->text('data')->nullable();
             $table->timestamps();

             $table->foreign('app_id')
                ->references('id')->on('apps')
                ->onDelete('cascade')->onUpdate('cascade');
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::drop('events');
     }
}
