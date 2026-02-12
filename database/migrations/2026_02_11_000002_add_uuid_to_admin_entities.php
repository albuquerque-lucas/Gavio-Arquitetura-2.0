<?php

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        User::query()->whereNull('uuid')->each(function (User $user) {
            $user->uuid = (string) Str::uuid();
            $user->saveQuietly();
        });

        Project::query()->whereNull('uuid')->each(function (Project $project) {
            $project->uuid = (string) Str::uuid();
            $project->saveQuietly();
        });

        Category::query()->whereNull('uuid')->each(function (Category $category) {
            $category->uuid = (string) Str::uuid();
            $category->saveQuietly();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
