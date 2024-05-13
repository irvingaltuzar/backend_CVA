<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegAuditoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_auditoria', function (Blueprint $table) {
            $table->integer('auditoriaId')->autoIncrement();
            $table->string('usuario', 25);
            $table->integer('subsecId');
            $table->timestamp('fechaHora')->useCurrent()->useCurrentOnUpdate();
            $table->string('ip', 15);
            $table->string('evento', 100);
            $table->tinyInteger('error');

            $table->foreign('subsecId', 'fk_SEG_AUDITORIA_SEG_SUBSECCION1')->references('subsecId')->on('seg_subseccion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seg_auditoria');
    }
}
