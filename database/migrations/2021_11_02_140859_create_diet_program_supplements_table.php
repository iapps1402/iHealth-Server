<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDietProgramSupplementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diet_program_supplements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('supplement_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedFloat('value');
            $table->timestamps();

            $table->foreign('program_id')
                ->references('id')
                ->on('diet_programs')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('supplement_id')
                ->references('id')
                ->on('foods')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('unit_id')
                ->references('id')
                ->on('food_units')
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
        Schema::dropIfExists('diet_program_supplements');
    }
}
