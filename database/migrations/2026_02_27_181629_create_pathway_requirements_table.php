<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pathway_requirements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pathway_id')->constrained()->cascadeOnDelete();
            $table->foreignId('required_badge_id')->constrained('badges')->cascadeOnDelete();

            $table->unsignedInteger('min_count')->default(1);

            $table->timestamps();

            $table->unique(['pathway_id', 'required_badge_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pathway_requirements');
    }
};
