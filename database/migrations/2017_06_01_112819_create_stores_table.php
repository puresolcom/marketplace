<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128);
            $table->string('slug', 64)->unique();
            $table->string('street_address_1', 255);
            $table->string('street_address_2', 255)->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('city_id');
            $table->unsignedSmallInteger('country_id');
            $table->string('postal_code', 16);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
            $table->foreign('city_id')->references('id')->on('locations')->onDelete('restrict');

            // Indexes
            $table->index('name');
            $table->index('slug');
            $table->index('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
