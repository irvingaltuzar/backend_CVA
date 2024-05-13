<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkPermitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_permit', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('parent_id')->default(0);
			$table->string('qr_code', 45);
            $table->integer('cat_work_permit_type_id');
            $table->timestamp('start')->useCurrent();
            $table->integer('responsable_id');
            $table->string('description', 500);
            $table->boolean('authorized')->default(0);
            $table->integer('authorized_by_id');
            $table->boolean('deleted')->default(0);
            $table->timestamp('end')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('cat_work_permit_type_id', 'fk_work_permit_cat_work_permit_type1')->references('id')->on('cat_work_permit_type');
            $table->foreign('responsable_id', 'fk_work_permit_users1')->references('id')->on('users');
            $table->foreign('authorized_by_id', 'fk_work_permit_users2')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_permit');
    }
}
