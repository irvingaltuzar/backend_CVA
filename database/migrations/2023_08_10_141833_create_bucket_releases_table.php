<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBucketReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bucket_releases', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
			$table->integer('release');
            $table->integer('environment_id');
			$table->timestamps();
			$table->softDeletes();

            $table->foreign('release')->references('id')->on('releases');
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
        Schema::dropIfExists('bucket_releases');
    }
}
