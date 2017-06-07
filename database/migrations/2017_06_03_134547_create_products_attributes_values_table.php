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
            $table->unsignedInteger('option_id')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('product_id');
            $table->index('attribute_id');
            $table->index('option_id');

            // Foreign Keys
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('products_attributes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('products_attributes_options')->onUpdate('cascade')->onDelete('cascade');
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
