<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedInteger('complaint_id');
            $table->integer('parent_id');
            $table->string('description', 250);
            $table->boolean('status')->default(0);
            $table->timestamp('end_date')->nullable();
            $table->boolean('deleted')->default(0);
            
            $table->foreign('complaint_id', 'fk_tasks_complaint1')->references('id')->on('complaint')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task');
    }
}
