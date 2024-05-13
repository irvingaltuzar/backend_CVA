<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBucketRuleEnvironmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bucket_rule_environments', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
			$table->integer('rule_id');
            $table->integer('environment_id');
			$table->boolean('deleted')->default(0);

            $table->foreign('rule_id')->references('id')->on('rules');
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
        Schema::dropIfExists('bucket_rule_environments');
    }
}
