<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_id')->unsigned();
            $table->string('name');
            $table->string('identifier');
            $table->string('platform');
            $table->text('data');
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
        Schema::drop('chats');
    }
}
