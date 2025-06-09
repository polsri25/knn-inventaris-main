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
        Schema::create('history_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenisbarang_id')
                ->constrained('jenis_barangs')
                ->onDelete('cascade');
            $table->foreignId('gudang_id')
                ->constrained('gudangs')
                ->onDelete('cascade');
            // $table->string('tipe'); // 'masuk' or 'keluar'
            $table->enum('prioritas', ['tinggi', 'rendah', 'sedang'])->nullable();
            $table->integer('jumlah');
            // $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historybarangs');
    }
};
