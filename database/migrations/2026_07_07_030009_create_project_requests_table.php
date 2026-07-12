<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('whatsapp')->nullable();
            $table->string('project_type'); // web_app, mobile_app, system, other
            $table->string('budget_range'); // under_5k, 5k_10k, 10k_25k, 25k_50k, over_50k
            $table->text('description');
            $table->string('status')->default('pending')->index(); // pending, in_review, approved, in_progress, completed, rejected
            $table->boolean('is_read')->default(false)->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_requests');
    }
};
