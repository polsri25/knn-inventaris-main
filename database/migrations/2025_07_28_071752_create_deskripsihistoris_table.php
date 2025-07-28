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
        Schema::create('deskripsihistoris', function (Blueprint $table) {
            $table->id(); // Auto-increment ID
            $table->foreignId('jenisbarang_id')
                ->constrained('jenis_barangs')
                ->onDelete('cascade');
            $table->foreignId('gudang_id')
                ->constrained('gudangs')
                ->onDelete('cascade');
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah']); // Prioritas
            $table->text('deskripsi_pengaturan'); // Deskripsi Pengaturan
            $table->timestamps(); // Timestamp untuk created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deskripsihistoris');
    }
};
