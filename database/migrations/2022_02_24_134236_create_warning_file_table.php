<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarningFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warning_file', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('warning_id');
            $table->string('file', 45);
            $table->boolean('deleted')->default(0);

            $table->foreign('warning_id', 'fk_warning_file_warning1')->references('id')->on('warning');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warning_file');
    }
}
