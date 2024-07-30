<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_translation', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->string('url');
            $table->text('announce')->nullable()->default(null);
            $table->longText('description')->nullable()->default(null);
            $table->boolean('visible')->default(true);
            $table->string('seo_title')->nullable()->default(null);
            $table->text('seo_description')->nullable()->default(null);
            $table->decimal('height', 10, 2)->default(0);
            $table->decimal('width', 10, 2)->default(0);
            $table->decimal('depth', 10, 2)->default(0);
            $table->decimal('weight', 10, 2)->default(0);
            $table->text('facebook_script')->nullable()->default(null);
            $table->text('google_script')->nullable()->default(null);
            $table->string('title_additional_1')->nullable()->default(null);
            $table->string('title_additional_2')->nullable()->default(null);
            $table->string('title_additional_3')->nullable()->default(null);
            $table->string('title_additional_4')->nullable()->default(null);
            $table->string('title_additional_5')->nullable()->default(null);
            $table->string('title_additional_6')->nullable()->default(null);
            $table->text('text_additional_1')->nullable()->default(null);
            $table->text('text_additional_2')->nullable()->default(null);
            $table->text('text_additional_3')->nullable()->default(null);
            $table->text('text_additional_4')->nullable()->default(null);
            $table->text('text_additional_5')->nullable()->default(null);
            $table->text('text_additional_6')->nullable()->default(null);
            $table->timestamps();

            $table->unique(['product_id', 'locale']);
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
        Schema::dropIfExists('product_translation');
    }
}
