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

            $table->uuid('public_id')->unique();

            $table->string('code')->nullable()->unique(); // ex: "CURSO-INTRO-DP"
            $table->string('title');
            $table->text('description')->nullable();

            $table->string('image_path')->nullable(); // caminho no storage

            $table->unsignedInteger('hours')->default(0);
            $table->json('skills')->nullable();   // ["...","..."]
            $table->json('criteria')->nullable(); // livre (texto/itens)

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
