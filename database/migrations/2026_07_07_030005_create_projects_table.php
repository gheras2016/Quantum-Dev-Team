<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('description');
            $table->string('client_name')->nullable()->index();
            $table->string('project_url')->nullable();
            $table->string('image')->nullable();
            $table->string('duration')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->text('case_study')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->string('github_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('documentation_url')->nullable();
            $table->string('status')->default('draft')->index(); // draft, published, archived
            $table->boolean('featured')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
