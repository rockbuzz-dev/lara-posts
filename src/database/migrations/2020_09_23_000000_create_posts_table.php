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
        $columnType = config('posts.author_reference_uuid') ? 'uuid' : 'unsignedBigInteger';

        Schema::create(config('posts.tables.posts'), function (Blueprint $table) use ($columnType) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->text('body')->nullable();
            $table->integer('view')->default(0);
            $table->string('status')->default(Status::DRAFT);
            $table->string('type')->default(Type::ARTICLE);
            $table->json('metadata')->nullable();
            $table->string('order_column')->nullable();
            $table->{$columnType}('author_id');
            $table->foreign('author_id')
                ->references('id')
                ->on(config('posts.tables.authors'))
                ->onDelete('cascade');
            $table->timestamp('published_at')->default(now());
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
        Schema::dropIfExists(config('posts.tables.posts'));
    }
}
