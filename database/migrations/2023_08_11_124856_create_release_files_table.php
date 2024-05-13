<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleaseFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('release_files', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('release_id');
            $table->string('file', 50);
            $table->boolean('deleted')->default(0);
            $table->foreign('release_id', 'fk_release_file_release1')->references('id')->on('releases');
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
        Schema::dropIfExists('release_files');
    }
}
