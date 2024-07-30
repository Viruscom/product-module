<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributeValueTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attribute_value_translation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pattrv_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->timestamps();

            $table->unique(['pattrv_id', 'locale']);
            $table->foreign('pattrv_id')->references('id')->on('product_attribute_values')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attribute_value_translation');
    }
}
