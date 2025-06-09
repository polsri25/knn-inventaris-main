<?php

namespace Database\Seeders;

use App\Models\Gudang;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
        ]);

        \App\Models\JenisBarang::factory()->count(29)->create();

        Gudang::insert([
            ['nama' => 'Gudang A', 'kode' => 'GD-A', 'alamat' => 'Jl. Gudang A'],
            ['nama' => 'Gudang B', 'kode' => 'GD-B', 'alamat' => 'Jl. Gudang B'],
            ['nama' => 'Gudang C', 'kode' => 'GD-C', 'alamat' => 'Jl. Gudang C'],
        ]);

        // Buat data dummy history barang, diasumsikan jenisbarang sudah ada
        \App\Models\HistoryBarang::factory()->count(30)->create();
    }
}
