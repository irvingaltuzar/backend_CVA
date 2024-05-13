<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_file', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedInteger('complaint_id');
            $table->string('file', 45);
            $table->string('deleted', 45)->default('0');
            
            $table->foreign('complaint_id', 'fk_complaint_file_complaint1')->references('id')->on('complaint')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaint_file');
    }
}
