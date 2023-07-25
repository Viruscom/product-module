<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameMeasureUnitColumnInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('measure_unit');
            $table->unsignedBigInteger('measure_unit_id')->nullable();
            $table->foreign('measure_unit_id')->references('id')->on('measure_units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('measure_unit', 191)->nullable();
            $table->dropForeign(['measure_unit_id']);
            $table->dropColumn('measure_unit_id');
        });
    }
}
