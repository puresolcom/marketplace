<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64);
            $table->string('email', 254)->unique();
            $table->string('phone_primary', 20);
            $table->string('phone_secondary', 20)->nullable();
            $table->string('password');
            $table->boolean('active')->nullable();
            $table->boolean('approved')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('name');
            $table->index('email');
            $table->index('phone_primary');
            $table->index('approved');
            $table->index('active');

            // Foreign Keys
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
        Schema::dropIfExists('users');
    }
}
