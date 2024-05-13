<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatBrandDetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_brand_dets', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
			$table->integer('cat_brand_id');
            $table->integer('cat_user_type_id');
			$table->boolean('deleted')->default(0);

            $table->foreign('cat_brand_id')->references('id')->on('cat_brand');
            $table->foreign('cat_user_type_id')->references('id')->on('cat_user_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_brand_dets');
    }
}
