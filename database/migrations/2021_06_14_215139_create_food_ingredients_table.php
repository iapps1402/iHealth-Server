<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_cooking_ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cooking_id');
            $table->string('name');
            $table->string('value');
            $table->timestamps();

            $table->foreign('cooking_id')
                ->references('id')
                ->on('food_cookings')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food_cooking_ingredients');
    }
}
