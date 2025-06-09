<?php

namespace Database\Seeders;

use App\Models\Gudang;
use App\Models\HistoryBarang;
use App\Models\JenisBarang;
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
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin'
        ]);
        User::factory()->create([
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'role' => 'staff',
        ]);
        User::factory()->create([
            'name' => 'Pimpinan',
            'email' => 'pimpinan@gmail.com',
            'role' => 'pimpinan',
        ]);

        Gudang::insert([
            ['nama' => 'Gudang A', 'kode' => 'GD-A', 'alamat' => 'Jl. Gudang A'],
            ['nama' => 'Gudang B', 'kode' => 'GD-B', 'alamat' => 'Jl. Gudang B'],
            ['nama' => 'Gudang C', 'kode' => 'GD-C', 'alamat' => 'Jl. Gudang C'],
        ]);

        JenisBarang::insert([
            ['nama' => 'Router', 'kode' => 'R-01', 'satuan' => 'Unit'],
            ['nama' => 'Switch', 'kode' => 'SW-212', 'satuan' => 'Unit'],
            ['nama' => 'Kabel LAN', 'kode' => 'KLAN-01', 'satuan' => 'Box'],
            ['nama' => 'Access Point', 'kode' => 'ACCP-08', 'satuan' => 'Unit'],
            ['nama' => 'Modem', 'kode' => 'MDM-09', 'satuan' => 'Unit'],
        ]);

        HistoryBarang::insert([
            ['jenisbarang_id' => '1', 'jumlah' => '50', 'gudang_id' => '1', 'prioritas' => 'tinggi'],
            ['jenisbarang_id' => '2', 'jumlah' => '30', 'gudang_id' => '2', 'prioritas' => 'sedang'],
            ['jenisbarang_id' => '3', 'jumlah' => '100', 'gudang_id' => '1', 'prioritas' => 'rendah'],
            ['jenisbarang_id' => '4', 'jumlah' => '20', 'gudang_id' => '3', 'prioritas' => 'tinggi'],
            ['jenisbarang_id' => '5', 'jumlah' => '40', 'gudang_id' => '2', 'prioritas' => 'sedang'],
        ]);
    }
}
