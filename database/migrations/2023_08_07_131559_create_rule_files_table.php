<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuleFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rule_files', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('rule_id');
            $table->string('file', 50);
            $table->boolean('deleted')->default(0);
            $table->foreign('rule_id', 'fk_rule_file_rule1')->references('id')->on('rules');
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
        Schema::dropIfExists('rule_files');
    }
}
