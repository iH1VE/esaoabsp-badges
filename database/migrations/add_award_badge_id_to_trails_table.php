<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trails', function (Blueprint $table) {
            $table->foreignId('award_badge_id')
                ->nullable()
                ->after('description')
                ->constrained('badges')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('trails', function (Blueprint $table) {
            $table->dropConstrainedForeignId('award_badge_id');
        });
    }
};
