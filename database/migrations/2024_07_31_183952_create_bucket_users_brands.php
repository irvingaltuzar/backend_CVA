<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBucketUsersBrands extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bucket_users_brands', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('users_id');
			$table->integer('cat_brand_id');

            $table->foreign('cat_brand_id')->references('id')->on('cat_brand');
            $table->foreign('users_id')->references('id')->on('users');
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
        Schema::dropIfExists('bucket_users_brands');
    }
}
