<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkPermitFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_permit_file', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('work_permit_id');
            $table->string('file', 50);
            $table->boolean('deleted')->default(0);
            $table->foreign('work_permit_id', 'fk_work_permit_file_work_permit1')->references('id')->on('work_permit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_permit_file');
    }
}
