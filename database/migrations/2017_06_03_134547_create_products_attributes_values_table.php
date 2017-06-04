<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsAttributesValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_attributes_values', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('attribute_id');
            $table->string('locale', 5);
            $table->unsignedInteger('option_value')->nullable();
            $table->longText('raw_value')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('product_id');
            $table->index('attribute_id');
            $table->index('locale');
            $table->index('option_value');

            // Foreign Keys
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('products_attributes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('option_value')->references('id')->on('products_attributes_options')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_attributes_values');
    }
}
