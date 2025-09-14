<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('barcode')->index();            // EAN/UPC/Code128 — no unique por si distintas presentaciones comparten
            $t->string('sku')->nullable()->index();
            $t->string('name');
            $t->string('brand')->nullable();
            $t->string('category')->nullable()->index();
            $t->timestamps();

            $t->index(['barcode','sku']);             // búsquedas frecuentes
        });
    }
    public function down(): void {
        Schema::dropIfExists('products');
    }
};