<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegAuditoriadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_auditoriad', function (Blueprint $table) {
            $table->integer('auditoriaDId')->autoIncrement();
            $table->integer('auditoriaId');
            $table->string('comentarios', 100);

            $table->foreign('auditoriaId', 'fk_SEG_AUDITORIAD_SEG_AUDITORIA1')->references('auditoriaId')->on('seg_auditoria');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seg_auditoriad');
    }
}
