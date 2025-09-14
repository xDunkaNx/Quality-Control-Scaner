<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('defect_types', function (Blueprint $t) {
            $t->id();
            $t->string('code')->unique();         // PKG, EXP, BRK, LBL...
            $t->string('name');
            $t->boolean('requires_photo')->default(false);
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('defect_types');
    }
};
