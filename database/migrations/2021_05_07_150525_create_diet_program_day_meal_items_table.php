<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDietProgramDayMealItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diet_program_day_meal_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meal_id');
            $table->unsignedBigInteger('food_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedFloat('value');
            $table->timestamps();

            $table->foreign('meal_id')
                ->references('id')
                ->on('diet_program_day_meals')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('food_id')
                ->references('id')
                ->on('foods')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('unit_id')
                ->references('id')
                ->on('food_units')
                ->onDelete('restrict')
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
        Schema::dropIfExists('diet_program_day_meal_items');
    }
}
