<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributionListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution_list', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name', 45);
            $table->integer('cat_distribution_list_type_id');
            $table->boolean('deleted')->default(0);

            $table->foreign('cat_distribution_list_type_id', 'fk_distribution_list_cat_distribution_list_type1')->references('id')->on('cat_distribution_list_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distribution_list');
    }
}
