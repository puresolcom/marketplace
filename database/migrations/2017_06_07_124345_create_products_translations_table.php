<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('translatable_id');
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('locale', 5);

            // Indexes
            $table->index('title');
            $table->index('locale');

            // Foreign Keys
            $table->foreign('translatable_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_translations');
    }
}
