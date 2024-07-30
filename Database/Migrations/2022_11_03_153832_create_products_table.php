<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateProductsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('products', static function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('brand_id');
                $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('brand_id')->references('id')->on('product_brands')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('position');
                $table->integer('creator_user_id');
                $table->bigInteger('units_in_stock')->default(1);
                $table->decimal('supplier_delivery_price', 10, 3)->default(0);
                $table->decimal('price', 10, 3)->nullable()->default(0);
                $table->string('barcode', 191)->nullable();
                $table->string('sku', 191)->nullable();
                $table->string('ean_code', 191)->nullable();
                $table->string('measure_unit', 191)->nullable();
                $table->boolean('active')->default(true);
                $table->boolean('is_new')->default(false);
                $table->boolean('is_promo')->default(false);
                $table->string('width', 191)->nullable();
                $table->string('height', 191)->nullable();
                $table->string('length', 191)->nullable();
                $table->string('weight', 191)->nullable();
                $table->string('filename', 191)->nullable();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('products');
        }
    }
