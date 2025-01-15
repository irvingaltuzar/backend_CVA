<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsWorkPermit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_permit_comments', function (Blueprint $table) {
            $table->id();
            $table->string('message', 250);
            $table->string('user_id', 250);
            $table->integer('work_permit_id');
            $table->foreign('work_permit_id')->references('id')->on('work_permit');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments_work_permit');
    }
}
