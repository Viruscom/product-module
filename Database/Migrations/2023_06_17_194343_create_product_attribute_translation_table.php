<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributeTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attribute_translation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pattr_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->timestamps();

            $table->unique(['pattr_id', 'locale']);
            $table->foreign('pattr_id')->references('id')->on('product_attributes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attribute_translation');
    }
}
