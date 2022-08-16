<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationVersionChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_version_changes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('version_id');
            $table->string('text_fa');
            $table->string('text_en');
            $table->timestamps();

            $table->foreign('version_id')
                ->references('id')
                ->on('application_versions')
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
        Schema::dropIfExists('application_version_changes');
    }
}
