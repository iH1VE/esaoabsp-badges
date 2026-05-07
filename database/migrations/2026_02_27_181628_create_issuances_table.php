<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issuances', function (Blueprint $table) {
            $table->id();

            $table->uuid('public_id')->unique();               // usado na URL pública
            $table->foreignId('badge_id')->constrained()->cascadeOnDelete();

            // "Issued to" (começa simples e depois evoluímos)
            $table->string('recipient_name');
            $table->string('recipient_email')->nullable();

            // Dados para exibir na autenticidade
            $table->date('issued_on');
            $table->unsignedInteger('total_hours')->default(0);

            // Cursos feitos para atingir (lista)
            $table->json('courses')->nullable();               // ["Curso X", "Curso Y"]
            // Skills exibidas
            $table->json('skills')->nullable();                // ["Metodologia", "Ética"]
            // Critérios (texto ou lista)
            $table->text('criteria')->nullable();

            // Segurança/estado
            $table->boolean('is_revoked')->default(false);
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();

            $table->timestamps();

            $table->index(['badge_id', 'issued_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issuances');
    }
};
