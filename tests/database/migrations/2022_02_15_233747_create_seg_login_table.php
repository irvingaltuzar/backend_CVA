<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_login', function (Blueprint $table) {
            $table->integer('loginId')->primary();
            $table->integer('usuarioId');
            $table->integer('subsecId');
            $table->string('loginUsr', 15);
            $table->string('loginCrud', 15)->nullable();
            
            $table->foreign('subsecId', 'fk_SEG_LOGIN_SEG_SUBSECCION1')->references('subsecId')->on('seg_subseccion')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('usuarioId', 'fk_SEG_LOGIN_SEG_USUARIOS1')->references('usuarioId')->on('seg_usuarios')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seg_login');
    }
}
