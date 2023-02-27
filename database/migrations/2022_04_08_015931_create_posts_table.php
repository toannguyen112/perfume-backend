<?php

use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('status', 30)->default(Post::STATUS_INACTIVE);
            $table->text('description')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->integer('view')->default(0);
            $table->string('author')->nullable();
            $table->date('posted_at')->nullable();
            $table->boolean('is_home')->nullable()->default(0);
            $table->boolean('is_featured')->nullable()->default(0);

            $table->string('meta_title')->nullable();
            $table->string('custom_slug')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status', 'status');
            $table->index('deleted_at', 'deleted_at');
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
};
