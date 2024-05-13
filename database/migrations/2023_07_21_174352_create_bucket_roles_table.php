<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBucketRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bucket_roles', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
			$table->integer('cat_brand_id');
            $table->integer('environment_id');
			$table->boolean('deleted')->default(0);

            $table->foreign('cat_brand_id')->references('id')->on('cat_brand');
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
        Schema::dropIfExists('bucket_roles');
    }
}
