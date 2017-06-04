<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsAttributesOptionsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_attributes_options_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('translatable_id');
            $table->string('name', 255);
            $table->string('locale', 5);

            // Indexes
            $table->index('name');
            $table->index('locale');

            // Foreign Keys
            $table->foreign('translatable_id')->references('id')->on('products_attributes_options')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_attributes_options_translations');
    }
}
