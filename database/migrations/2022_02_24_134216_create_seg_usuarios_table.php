<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_usuarios', function (Blueprint $table) {
            $table->integer('usuarioId')->autoIncrement();
            $table->string('nombre', 50);
            $table->string('apepa', 50);
            $table->string('apema', 50);
            $table->string('usuario', 15);
            $table->string('pwd', 60);
            $table->boolean('isadm')->default(0);
            $table->boolean('bloqueado')->default(0);
            $table->boolean('borrado')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seg_usuarios');
    }
}
