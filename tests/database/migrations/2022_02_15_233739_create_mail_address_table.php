<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_address', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('users_id');
            $table->string('mail', 50)->nullable();
            $table->integer('deleted')->default(0);
            
            $table->foreign('users_id', 'fk_mail_address_users1')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_address');
    }
}
