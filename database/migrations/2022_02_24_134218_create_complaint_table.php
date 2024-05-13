<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->integer('parent_id')->default(0);
            $table->string('description', 250);
            $table->integer('asigned_to_id');
            $table->boolean('status');
			$table->string('phone_contact', 10);
			$table->string('email_contact', 45)->nullable();
            $table->boolean('deleted')->default(0);

            $table->foreign('asigned_to_id', 'fk_complaint_users1')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaint');
    }
}
