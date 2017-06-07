<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsAttributesTaxonomiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_attributes_taxonomies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attribute_id');
            $table->unsignedInteger('taxonomy_id');

            // Foreign Keys
            $table->foreign('attribute_id')->references('id')->on('products_attributes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('taxonomy_id')->references('id')->on('taxonomies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_attributes_taxonomies');
    }
}
