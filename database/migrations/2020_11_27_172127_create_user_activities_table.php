<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('relation_id')->nullable();
            $table->string('name_fa');
            $table->string('name_en');
            $table->unsignedInteger('minutes');
            $table->unsignedInteger('calorie_ratio');
            $table->timestamps();

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
        Schema::dropIfExists('user_activities');
    }
}
