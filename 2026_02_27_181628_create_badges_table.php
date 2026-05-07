<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();

            // Public identifier for URLs (optional but useful)
            $table->uuid('public_id')->unique();

            // Admin metadata
            $table->string('code')->nullable()->unique(); // ex: "CURSO-INTRO-DP"
            $table->string('title');
            $table->text('description')->nullable();

            // Badge visual
            $table->string('image_path')->nullable(); // storage path

            // Learning metadata
            $table->unsignedInteger('hours')->default(0); // carga horária padrão
            $table->json('skills')->nullable(); // ["Direito Administrativo", "Peticionamento", ...]
            $table->json('criteria')->nullable(); // texto/itens (livre)

            // Control
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
