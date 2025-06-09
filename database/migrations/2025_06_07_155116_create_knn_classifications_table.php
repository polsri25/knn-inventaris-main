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
        Schema::create('knn_classifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')
                ->constrained('history_barangs')
                ->onDelete('cascade');
            $table->integer('nilai_k');
            $table->json('neighbors')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knn_classifications');
    }
};
