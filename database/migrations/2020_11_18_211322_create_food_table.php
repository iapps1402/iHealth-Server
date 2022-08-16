<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->unsignedBigInteger('picture_id');
            $table->unsignedBigInteger('cooking_id');
            $table->string('name_fa');
            $table->string('name_en');
            $table->string('barcode')->nullable();
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('food_categories')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('cooking_id')
                ->references('id')
                ->on('food_cookings')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('picture_id')
                ->references('id')
                ->on('medias')
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
        Schema::dropIfExists('foods');
    }
}
