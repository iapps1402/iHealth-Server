<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('food_id');
            $table->string('name_fa');
            $table->string('name_en');
            $table->unsignedFloat('carbs');
            $table->unsignedFloat('fat');
            $table->unsignedFloat('protein');
            $table->unsignedFloat('fiber');
            $table->unsignedFloat('calorie');
            $table->boolean('default')->default(0);
            $table->string('icon');
            $table->timestamps();

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
        Schema::dropIfExists('food_units');
    }
}
