<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('chat_events', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('chat_member_id')->unsigned();
             $table->string('event');
             $table->timestamps();

             $table->foreign('chat_member_id')
                ->references('id')->on('chat_members')
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
         Schema::drop('chat_events');
     }
}
