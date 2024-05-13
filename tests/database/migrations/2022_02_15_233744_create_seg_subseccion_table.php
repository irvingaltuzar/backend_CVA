<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegSubseccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_subseccion', function (Blueprint $table) {
            $table->integer('subsecId')->primary();
            $table->integer('secId');
            $table->tinyInteger('subsecOrden');
            $table->string('subsecDesc', 250);
            $table->string('subsecUrl', 100);
            $table->string('subsecDenegado', 100);
            $table->string('tablaDatos', 50);
            $table->boolean('mostrar')->default(0);
            
            $table->foreign('secId', 'fk_SEG_SUBSECCION_SEG_SECCION1')->references('secId')->on('seg_seccion')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seg_subseccion');
    }
}
