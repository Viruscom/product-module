<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasureUnitsTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measure_units_translation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('measure_unit_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->timestamps();

            $table->unique(['measure_unit_id', 'locale']);
            $table->foreign('measure_unit_id')->references('id')->on('measure_units')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measure_units_translation');
    }
}
