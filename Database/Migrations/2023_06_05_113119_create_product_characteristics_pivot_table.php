<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCharacteristicsPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_characteristics_pivot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_characteristic_id');
            $table->unsignedBigInteger('product_category_id');
            $table->timestamps();

            $table->foreign('product_characteristic_id')->references('id')->on('product_characteristics')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_characteristics_pivot');
    }
}
