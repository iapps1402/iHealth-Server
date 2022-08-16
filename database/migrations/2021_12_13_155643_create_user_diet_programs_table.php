<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDietProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_diet_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diet_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('writer_id');
            $table->unsignedFloat('protein');
            $table->unsignedFloat('carbs');
            $table->unsignedFloat('fat');
            $table->dateTime('user_read_at')->nullable();
            $table->text('note')->nullable();
            $table->smallInteger('decrease_or_increase_coefficient')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('diet_id')
                ->references('id')
                ->on('diet_programs')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('writer_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('user_diet_programs');
    }
}
