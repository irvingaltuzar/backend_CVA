<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegSeccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_seccion', function (Blueprint $table) {
            $table->integer('secId')->autoIncrement();
            $table->string('secDesc', 250);
            $table->tinyInteger('secOrden');
            $table->tinyInteger('secPocision');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seg_seccion');
    }
}
