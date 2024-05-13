<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBucketAdminRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bucket_admin_roles', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
			$table->integer('SEG_USUARIOS_usuarioId');
            $table->integer('environment_id');
			$table->boolean('deleted')->default(0);

			$table->foreign('SEG_USUARIOS_usuarioId', 'fk_users_SEG_USUARIOS3')->references('usuarioId')->on('seg_usuarios');
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
        Schema::dropIfExists('bucket_admin_roles');
    }
}
