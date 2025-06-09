<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisBarang>
 */
class JenisBarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $items = [
        //     'Router Enterprise',
        //     'Router Kecil/CPE',
        //     'Router Nirkabel',
        //     'Switch Layer 2',
        //     'Switch Layer 3',
        //     'Switch PoE',
        //     'Access Point',
        //     'Wireless Controller',
        //     'Antena Outdoor',
        //     'Server Rack',
        //     'Server Blade',
        //     'NAS (Network Attached Storage)',
        //     'Kabel UTP Cat 6',
        //     'Kabel Fiber Optik',
        //     'Patch Cord',
        //     'Firewall',
        //     'VPN Concentrator',
        //     'IDS/IPS',
        //     'UPS (Uninterruptible Power Supply)',
        //     'PDU (Power Distribution Unit)',
        //     'Battery Backup',
        //     'OLT (Optical Line Terminal)',
        //     'ONT (Optical Network Terminal)',
        //     'GPON SFP',
        //     'Crimping Tool',
        //     'Fiber Splicer',
        //     'Rack Server',
        //     'SFP Module',
        //     'Media Converter',
        //     'Cooling Fan',
        // ];
        $items = [
            'Router',
            'Switch',
            'Kabel LAN',
            'Access Point',
            'Modem',
        ];
        $nama = $this->faker->unique()->randomElement($items);
        $kode = strtoupper(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9 ]/', '', $nama))) . '-' . $this->faker->unique()->numberBetween(100, 999);

        return [
            'nama' => $nama,
            'kode' => $kode,
            'keterangan' => $this->faker->sentence(),
            'satuan' => $this->faker->randomElement(['Unit', 'Pcs', 'Box']),
        ];
    }
}
