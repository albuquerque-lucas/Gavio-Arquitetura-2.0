<?php

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
            $table->string('slug')->nullable()->unique()->after('uuid');
        });

        User::query()->whereNull('slug')->each(function (User $user) {
            $base = Str::slug((string) $user->username);
            if ($base === '') {
                $base = 'usuario';
            }

            $slug = $base;
            $counter = 1;
            while (
                User::query()
                    ->where('slug', $slug)
                    ->where('id', '!=', $user->id)
                    ->exists()
            ) {
                $slug = $base.'-'.$counter;
                $counter++;
            }

            $user->slug = $slug;
            $user->saveQuietly();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
