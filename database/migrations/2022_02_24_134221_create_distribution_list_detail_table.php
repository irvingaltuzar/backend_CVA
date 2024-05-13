<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributionListDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution_list_detail', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('distribution_list_id');
            $table->integer('cat_brand_id');
            $table->boolean('deleted')->default(0);

            $table->foreign('distribution_list_id', 'fk_distribution_list_detail_distribution_list1')->references('id')->on('distribution_list');
            $table->foreign('cat_brand_id', 'fk_distribution_list_detail_brands1')->references('id')->on('cat_brand');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distribution_list_detail');
    }
}
