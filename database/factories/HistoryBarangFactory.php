<?php

namespace Database\Factories;

use App\Models\Gudang;
use App\Models\JenisBarang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistoryBarang>
 */
class HistoryBarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $tipe = $this->faker->randomElement(['masuk', 'keluar']);
        // $prioritas = $tipe === 'keluar' ? null : $this->faker->randomElement(['tinggi', 'sedang', 'rendah']);

        return [
            'jenisbarang_id' => JenisBarang::inRandomOrder()->first()?->id ?? JenisBarang::factory(),
            'gudang_id' => Gudang::inRandomOrder()->first()?->id ?? Gudang::factory(),
            // 'tipe' => $tipe,
            'jumlah' => $this->faker->numberBetween(1, 100),
            // 'keterangan' => $this->faker->optional()->sentence,
            'prioritas' => $this->faker->randomElement(['tinggi', 'sedang', 'rendah']),
        ];
    }
}
