<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_rekomendasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wisata_asal_id')->constrained('wisatas')->cascadeOnDelete();
            $table->foreignId('wisata_rekomendasi_id')->constrained('wisatas')->cascadeOnDelete();
            $table->decimal('similarity_score', 5, 4);
            $table->string('keyword_pencarian', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_rekomendasis');
    }
};
