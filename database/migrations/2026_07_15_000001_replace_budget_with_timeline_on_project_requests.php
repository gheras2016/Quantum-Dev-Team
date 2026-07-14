<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->string('timeline')->nullable()->after('project_type');
        });

        Schema::table('project_requests', function (Blueprint $table) {
            $table->dropColumn('budget_range');
        });
    }

    public function down(): void
    {
        Schema::table('project_requests', function (Blueprint $table) {
            $table->string('budget_range')->nullable()->after('project_type');
            $table->dropColumn('timeline');
        });
    }
};
