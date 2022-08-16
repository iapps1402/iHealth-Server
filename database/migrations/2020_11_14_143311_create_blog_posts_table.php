<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('name_fa');
            $table->string('name_en');
            $table->text('text_fa');
            $table->text('text_en');
            $table->string('summary_fa', 1000);
            $table->string('summary_en', 1000);
            $table->enum('status', ['published', 'draft']);
            $table->unsignedBigInteger('picture_id');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedInteger('views')->default(0);
            $table->enum('language', ['en', 'fa'])->default('fa');
            $table->timestamps();

            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('blog_post_categories')
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
        Schema::dropIfExists('blog_posts');
    }
}
