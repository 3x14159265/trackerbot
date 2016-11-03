<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('chat_members', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('chat_id')->unsigned();
             $table->integer('identifier');
             $table->string('name');
             $table->string('username')->nullable();
             $table->timestamps();

             $table->foreign('chat_id')
                ->references('id')->on('chats')
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
         Schema::drop('chat_members');
     }
}
