<?php

namespace Database\Seeders;

use App\Models\Gudang;
use App\Models\HistoryBarang;
use App\Models\JenisBarang;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        DB::table('deskripsihistoris')->insert([
            // Router
            [
                'jenisbarang_id' => 1, // Router
                'gudang_id' => 1, // Gudang A
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak A1 untuk akses cepat dan langsung.'
            ],
            [
                'jenisbarang_id' => 1, // Router
                'gudang_id' => 1,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Diletakkan di Rak A2 yang bersisian dengan peralatan lainnya.'
            ],
            [
                'jenisbarang_id' => 1, // Router
                'gudang_id' => 1,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan di Kotak penyimpanan di bawah Rak A untuk memudahkan penghitungan stok.'
            ],
            [
                'jenisbarang_id' => 1, // Router
                'gudang_id' => 2, // Gudang B
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak B1 untuk kemudahan akses instalasi.'
            ],
            [
                'jenisbarang_id' => 1, // Router
                'gudang_id' => 2,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Diletakkan di Rak B2, terpisah dari barang lainnya.'
            ],
            [
                'jenisbarang_id' => 1, // Router
                'gudang_id' => 2,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan dalam kotak di sudut Rak B untuk pengambilan jarang.'
            ],

            // Switch
            [
                'jenisbarang_id' => 2, // Switch
                'gudang_id' => 1, // Gudang A
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak A1 dengan label yang jelas untuk akses cepat.'
            ],
            [
                'jenisbarang_id' => 2, // Switch
                'gudang_id' => 1,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Diletakkan di Rak A2, dekat kursi teknisi untuk akses mudah.'
            ],
            [
                'jenisbarang_id' => 2, // Switch
                'gudang_id' => 1,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan di Rak A3, belakang untuk penggunaan tidak teratur.'
            ],
            [
                'jenisbarang_id' => 2, // Switch
                'gudang_id' => 2, // Gudang B
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak B1 dengan akses cepat untuk setiap instalasi.'
            ],
            [
                'jenisbarang_id' => 2, // Switch
                'gudang_id' => 2,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Diletakkan di Rak B2, di dekat pintu untuk akses cepat.'
            ],
            [
                'jenisbarang_id' => 2, // Switch
                'gudang_id' => 2,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak B3, belakang untuk penggunaan tidak teratur.'
            ],
            [
                'jenisbarang_id' => 2, // Switch
                'gudang_id' => 3, // Gudang C
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak C1 agar mudah dijangkau.'
            ],
            [
                'jenisbarang_id' => 2, // Switch
                'gudang_id' => 3,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Diletakkan di Rak C2, dekat aksesori pendukung.'
            ],
            [
                'jenisbarang_id' => 2, // Switch
                'gudang_id' => 3,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan di Rak C3, bagian belakang untuk pengambilan yang tidak teratur.'
            ],

            // Kabel LAN
            [
                'jenisbarang_id' => 3, // Kabel LAN
                'gudang_id' => 1, // Gudang A
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Disimpan di Rak A2 dengan label panjang untuk akses cepat.'
            ],
            [
                'jenisbarang_id' => 3, // Kabel LAN
                'gudang_id' => 1,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak A3, dikelompokkan sesuai ukuran.'
            ],
            [
                'jenisbarang_id' => 3, // Kabel LAN
                'gudang_id' => 1,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan dalam container di belakang Rak A4 untuk akses jika perlu.'
            ],
            [
                'jenisbarang_id' => 3, // Kabel LAN
                'gudang_id' => 2, // Gudang B
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak B1 untuk pengambilan cepat.'
            ],
            [
                'jenisbarang_id' => 3, // Kabel LAN
                'gudang_id' => 2,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Diletakkan di Rak B2, dikelompokkan berdasarkan jenis kabel.'
            ],
            [
                'jenisbarang_id' => 3, // Kabel LAN
                'gudang_id' => 2,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan di Rak B3, tempat sampah untuk pengambilan jarang.'
            ],
            [
                'jenisbarang_id' => 3, // Kabel LAN
                'gudang_id' => 3, // Gudang C
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Disimpan di Rak C1 untuk akses cepat.'
            ],
            [
                'jenisbarang_id' => 3, // Kabel LAN
                'gudang_id' => 3,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak C2, berdasarkan ukuran dan panjang.'
            ],
            [
                'jenisbarang_id' => 3, // Kabel LAN
                'gudang_id' => 3,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan di Rak C3, bagian belakang untuk pengambilan jarang.'
            ],

            // Access Point
            [
                'jenisbarang_id' => 4, // Access Point
                'gudang_id' => 1, // Gudang A
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak A1, mudah diakses untuk instalasi.'
            ],
            [
                'jenisbarang_id' => 4, // Access Point
                'gudang_id' => 1,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Diletakkan di Rak A2, praktis untuk akses cepat.'
            ],
            [
                'jenisbarang_id' => 4, // Access Point
                'gudang_id' => 1,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan di Rak A3, untuk akses jarang.'
            ],
            [
                'jenisbarang_id' => 4, // Access Point
                'gudang_id' => 2, // Gudang B
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak B1, akses cepat dan terlihat.'
            ],
            [
                'jenisbarang_id' => 4, // Access Point
                'gudang_id' => 2,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Diletakkan di Rak B2, sebelah Switch.'
            ],
            [
                'jenisbarang_id' => 4, // Access Point
                'gudang_id' => 2,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan di Rak B3, bagian bawah untuk jarang diakses.'
            ],
            [
                'jenisbarang_id' => 4, // Access Point
                'gudang_id' => 3, // Gudang C
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak C1 untuk kemudahan pengambilan.'
            ],
            [
                'jenisbarang_id' => 4, // Access Point
                'gudang_id' => 3,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Diletakkan di Rak C2, di dekat aksesoris pendukung.'
            ],
            [
                'jenisbarang_id' => 4, // Access Point
                'gudang_id' => 3,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan di Rak C3, di bagian belakang untuk jarang diakses.'
            ],

            // Modem
            [
                'jenisbarang_id' => 5, // Modem
                'gudang_id' => 1, // Gudang A
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak A1, mudah dijangkau untuk konfigurasi.'
            ],
            [
                'jenisbarang_id' => 5, // Modem
                'gudang_id' => 1,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Diletakkan di Rak A2, berdekatan dengan Router.'
            ],
            [
                'jenisbarang_id' => 5, // Modem
                'gudang_id' => 1,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan di Rak A3, di bawah untuk penggunaan jarang.'
            ],
            [
                'jenisbarang_id' => 5, // Modem
                'gudang_id' => 2, // Gudang B
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak B1, akses cepat saat debugging.'
            ],
            [
                'jenisbarang_id' => 5, // Modem
                'gudang_id' => 2,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Diletakkan di Rak B2, strategis untuk pengambilan.'
            ],
            [
                'jenisbarang_id' => 5, // Modem
                'gudang_id' => 2,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan di Rak B3, belakang untuk pengambilan yang jarang.'
            ],
            [
                'jenisbarang_id' => 5, // Modem
                'gudang_id' => 3, // Gudang C
                'prioritas' => 'Tinggi',
                'deskripsi_pengaturan' => 'Ditempatkan di Rak C1, mudah diakses.'
            ],
            [
                'jenisbarang_id' => 5, // Modem
                'gudang_id' => 3,
                'prioritas' => 'Sedang',
                'deskripsi_pengaturan' => 'Diletakkan di Rak C2, sesuai dengan aksesori.'
            ],
            [
                'jenisbarang_id' => 5, // Modem
                'gudang_id' => 3,
                'prioritas' => 'Rendah',
                'deskripsi_pengaturan' => 'Disimpan di Rak C3, untuk akses tidak teratur.'
            ],
        ]);
    }
}
