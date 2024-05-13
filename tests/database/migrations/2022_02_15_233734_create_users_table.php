<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('cat_brand_id');
            $table->integer('cat_user_type_id');
            $table->integer('SEG_USUARIOS_usuarioId');
            $table->boolean('deleted')->default(0);
            
            $table->foreign('SEG_USUARIOS_usuarioId', 'fk_users_SEG_USUARIOS1')->references('usuarioId')->on('seg_usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('cat_brand_id', 'fk_users_cat_brand1')->references('id')->on('cat_brand')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('cat_user_type_id', 'fk_users_cat_user_type1')->references('id')->on('cat_user_type')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
