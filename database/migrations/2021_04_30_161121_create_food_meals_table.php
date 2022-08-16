<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_category_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('category_id');
            $table->unsignedBigInteger('food_id');
            $table->timestamps();

            $table->foreign('meal_id')
                ->references('id')
                ->on('food_categories')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('food_id')
                ->references('id')
                ->on('foods')
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
        Schema::dropIfExists('food_category_relations');
    }
}
