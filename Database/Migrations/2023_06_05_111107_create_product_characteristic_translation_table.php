<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCharacteristicTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_char_translation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_characteristic_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->timestamps();

            $table->unique(['product_characteristic_id', 'locale']);
            $table->foreign('product_characteristic_id')->references('id')->on('product_characteristics')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_characteristic_translation');
    }
}
