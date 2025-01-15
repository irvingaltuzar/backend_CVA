<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkPermitFileDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_permit_file_documents', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('file', 50);
            $table->unsignedBigInteger('cat_documents_work_permit_id'); // Cambiar a unsignedBigInteger
            $table->foreign('cat_documents_work_permit_id', 'fk_cat_documents_work_permit1')
                  ->references('id')->on('cat_documents_work_permit');
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
        Schema::dropIfExists('work_permit_file_documents');
    }
}
