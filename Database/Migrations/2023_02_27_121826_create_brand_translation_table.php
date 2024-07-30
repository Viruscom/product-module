<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_translation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->string('url');
            $table->text('announce')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->string('seo_title')->nullable()->default(null);
            $table->text('seo_description')->nullable()->default(null);
            $table->text('facebook_script')->nullable()->default(null);
            $table->text('google_script')->nullable()->default(null);
            $table->boolean('visible')->default(true);
            $table->timestamps();

            $table->unique(['brand_id', 'locale']);
            $table->foreign('brand_id')->references('id')->on('product_brands')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brand_translation');
    }
}
