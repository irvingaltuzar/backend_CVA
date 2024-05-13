<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_file', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('task_id');
            $table->string('file', 45);
            $table->boolean('deleted')->default(0);

            $table->foreign('task_id', 'fk_task_file_task1')->references('id')->on('task');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_file');
    }
}
