<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 32);
            $table->string('key', 32);
            $table->text('value');
            $table->dateTime('expires_at');
            $table->timestamps();

            // Indexes
            $table->index('type');
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transients');
    }
}
