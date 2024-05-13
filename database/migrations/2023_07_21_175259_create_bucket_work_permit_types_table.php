<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBucketWorkPermitTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bucket_work_permit_types', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
			$table->integer('work_permit_type_id');
            $table->integer('environment_id');
			$table->boolean('deleted')->default(0);

            $table->foreign('work_permit_type_id')->references('id')->on('cat_work_permit_type');
            $table->foreign('environment_id')->references('id')->on('environments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bucket_work_permit_types');
    }
}
