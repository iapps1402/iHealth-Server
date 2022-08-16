<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_foods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('relation_id')->nullable();
            $table->unsignedBigInteger('data_id')->nullable();
            $table->unsignedBigInteger('food_id')->nullable();
            $table->string('name_fa');
            $table->string('name_en');
            $table->unsignedFloat('protein');
            $table->unsignedFloat('fat');
            $table->unsignedFloat('carbs');
            $table->unsignedFloat('fiber');
            $table->string('description_fa')->nullable();
            $table->string('description_en')->nullable();
            $table->boolean('countable')->default(1);
            $table->boolean('countable')->default(1);
            $table->text('description_fa')->nullable();
            $table->text('description_en')->nullable();
            $table->enum('meal', [
                'breakfast',
                'lunch',
                'dinner',
                'snacks'
            ]);
            $table->timestamps();

            $table->foreign('food_id')
                ->references('id')
                ->on('foods')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('data_id')
                ->references('id')
                ->on('user_food_data')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('relation_id')
                ->references('id')
                ->on('user_date_relations')
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
        Schema::dropIfExists('user_foods');
    }
}
