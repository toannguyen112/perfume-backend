<?php

use App\Models\PostCategory;
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
        Schema::create('post_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable()->default(0);
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->integer('view')->default(0);
            $table->string('status', 30)->default(PostCategory::STATUS_ACTIVE);

            $table->string('meta_title')->nullable();
            $table->string('custom_slug')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id', 'parent_id');
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
        Schema::dropIfExists('post_categories');
    }
};
