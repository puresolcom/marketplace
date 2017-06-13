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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('upc', 12);
            $table->string('sku', 12);
            $table->unsignedInteger('stock');
            $table->decimal('price', 8, 2);
            $table->decimal('discount_price', 8, 2);
            $table->unsignedSmallInteger('currency_id');
            $table->unsignedInteger('store_id');
            $table->boolean('active')->nullable();
            $table->boolean('approved')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('stock');
            $table->index('upc');
            $table->index('sku');
            $table->index('price');
            $table->index('discount_price');
            $table->index('active');
            $table->index('approved');
            $table->index('approved_by');

            // Foreign Keys
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('store_id')->references('id')->on('stores')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('approved_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
