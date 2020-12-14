<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Rockbuzz\LaraPosts\Enums\{Status, Type};
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->text('body')->nullable();
            $table->integer('view')->default(0);
            $table->string('status')->default(Status::DRAFT);
            $table->string('type')->default(Type::ARTICLE);
            $table->json('metadata')->nullable();
            $table->string('order_column')->nullable();
            $table->uuid('author_id');
            $table->foreign('author_id')
                ->references('id')
                ->on(config('posts.tables.authors'))
                ->onDelete('cascade');
            $table->timestamp('published_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
