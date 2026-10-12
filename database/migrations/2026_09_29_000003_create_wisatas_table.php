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
        Schema::create('wisatas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnDelete();
            $table->string('nama_wisata', 100);
            $table->string('slug', 110)->unique();
            $table->string('kecamatan', 50);
            $table->text('deskripsi');
            $table->text('alamat');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->integer('harga_tiket')->default(0);
            $table->decimal('rating_google', 2, 1)->default(0.0);
            $table->integer('jumlah_review_google')->default(0);
            $table->string('jam_operasional', 100)->nullable();
            $table->string('foto_utama', 255)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisatas');
    }
};
