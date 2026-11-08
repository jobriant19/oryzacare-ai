<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analyses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('image');                 // nama file di public/uploads/analisis
            $table->string('original_name')->nullable();
            $table->string('label', 50)->index();    // Bacterialblight | Blast | Brownspot | Tungro
            $table->decimal('confidence', 5, 2);     // persen
            $table->json('probabilities');           // peluang tiap kelas (persen)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analyses');
    }
};
