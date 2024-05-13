<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('parent_id')->default(0);
            $table->integer('to');
            $table->timestamp('date')->useCurrent()->useCurrentOnUpdate();
            $table->string('title', 45);
            $table->string('message', 200);
            $table->timestamp('viewed_at')->nullable();
            $table->boolean('by_mail')->default(0);
            $table->boolean('is_massive')->default(0);
            $table->integer('sended_by_id');
            $table->boolean('deleted')->default(0);

            $table->foreign('sended_by_id', 'fk_messages_users1')->references('id')->on('users');
            $table->foreign('to', 'fk_messages_to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
