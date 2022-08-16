<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('mime_type');
            $table->unsignedInteger('size');
            $table->unsignedBigInteger('thumbnail_id')->nullable();
            $table->string('path');
            $table->string('filename');
            $table->boolean('uploaded')->default(0);
            $table->timestamps();

            $table->foreign('thumbnail_id')
                ->references('id')
                ->on('medias')
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
        Schema::dropIfExists('medias');
    }
}
