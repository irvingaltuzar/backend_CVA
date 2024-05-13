<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkPermitBossTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_permit_boss', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('users_id');
            $table->integer('cat_work_permit_type_id');
            $table->boolean('deleted')->default(0);

            $table->foreign('cat_work_permit_type_id', 'fk_work_permit_boss_cat_work_permit_type1')->references('id')->on('cat_work_permit_type');
            $table->foreign('users_id', 'fk_work_permit_boss_users1')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_permit_boss');
    }
}
