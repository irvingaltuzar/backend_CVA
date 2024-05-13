<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_file', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('messages_id');
            $table->string('file', 50);
            $table->boolean('deleted')->default(0);

            $table->foreign('messages_id', 'fk_message_file_messages1')->references('id')->on('messages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_file');
    }
}
