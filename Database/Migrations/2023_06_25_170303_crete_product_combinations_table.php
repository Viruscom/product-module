<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreteProductCombinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_combinations', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->float('quantity')->nullable();
            $table->decimal('price', 10, 2)->nullable()->default(0.00);
            $table->string('sku')->nullable();
            $table->text('combination');
            $table->text('filter_combo');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_combinations');
    }
}
