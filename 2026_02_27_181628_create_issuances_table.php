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

            $table->foreignId('badge_id')->constrained()->cascadeOnDelete();

            // Public page for authenticity
            $table->uuid('public_id')->unique(); // usado em /a/{public_id}

            // Recipient
            $table->string('recipient_name')->nullable();
            $table->string('recipient_email')->index(); // para busca e emissão em lote

            // Evidence / details shown on authenticity page
            // Exemplo:
            // {
            //   "courses": [{"title":"...", "hours":10}],
            //   "total_hours": 40,
            //   "skills": ["...","..."]
            // }
            $table->json('evidence')->nullable();

            // Issuance data
            $table->timestamp('issued_at')->nullable();

            // Status control
            $table->string('status')->default('issued'); // issued | revoked | pending
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();

            $table->timestamps();

            // Avoid accidental duplicates:
            $table->unique(['badge_id', 'recipient_email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issuances');
    }
};
