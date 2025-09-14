<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('defects', function (Blueprint $t) {
            $t->id();

            // claves foráneas principales
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('defect_type_id')->constrained()->cascadeOnDelete();

            // ubicación (elige UNA de las dos estrategias):
            // a) referencial a locations (recomendado)
            $t->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            // b) ó solo texto libre:
            $t->string('location_text')->nullable()->index();

            // metadatos del evento
            $t->unsignedTinyInteger('severity')->default(1);    // 1..5
            $t->string('status')->default('open')->index();      // open|review|closed|scrapped
            $t->string('lot')->nullable()->index();
            $t->text('notes')->nullable();
            $t->string('photo_path')->nullable();

            // tiempos y usuario
            $t->timestamp('found_at')->useCurrent()->index();
            $t->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('resolved_at')->nullable()->index();

            $t->timestamps();

            // índices compuestos útiles en reportes
            $t->index(['product_id', 'defect_type_id']);
            $t->index(['found_at', 'product_id']);
            $t->index(['location_id', 'found_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('defects');
    }
};
