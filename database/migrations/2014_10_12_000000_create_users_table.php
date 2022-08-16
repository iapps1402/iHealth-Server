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
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_number', 10)->unique()->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->timestamp('online_at')->nullable();
            $table->string('password')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->unsignedFloat('weight')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedFloat('fat_ratio')->nullable();
            $table->unsignedFloat('protein_ratio')->nullable();
            $table->unsignedFloat('goal_weight')->nullable();
            $table->boolean('customer')->default(0);
            $table->unsignedInteger('coins')->default(0);
            $table->dateTime('invited_at')->nullable();
            $table->unsignedBigInteger('invited_by')->nullable();
            $table->string('invitation_code', 8);
            $table->smallInteger('decrease_or_increase_coefficient')->nullable();
            $table->enum('language', ['en', 'fa'])->default('fa');
            $table->unsignedInteger('diet_program_period')->default(30);
            $table->enum('activity', [
                'no_physical_activity',
                'sedentary',
                'somehow_active',
                'active',
                'very_active'
            ])->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('invited_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
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
        Schema::dropIfExists('users');
    }
}
