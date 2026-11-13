<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('reviewer_name', 120);
            $table->text('review_text')->nullable();
            $table->string('youtube_url', 2048)->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('published')->default(false);
            $table->boolean('permission_confirmed')->default(false);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
            $table->index(['published', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_reviews');
    }
};
