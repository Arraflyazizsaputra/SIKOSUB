<?php

namespace Database\Factories;

use App\Models\Kost;
use Illuminate\Database\Eloquent\Factories\Factory;

class KostFactory extends Factory
{
    protected $model = Kost::class;

    public function definition(): array
    {
        return [
            'nama_kost' => 'Kost ' . $this->faker->lastName(),
            'tipe_kost' => $this->faker->randomElement(['putra', 'putri', 'campur']),
            'harga_per_bulan' => $this->faker->numberBetween(500000, 2000000),
            'harga_diskon' => $this->faker->randomElement([0, $this->faker->numberBetween(400000, 1800000)]),
            'kategori_wilayah' => $this->faker->randomElement(['Soklat', 'Pasirkareumbi', 'Cigadung', 'Dangdeur']),
            'alamat' => $this->faker->address(),
            'no_wa' => '628' . $this->faker->randomNumber(9, true),
            'maps' => 'https://maps.google.com/?q=' . $this->faker->latitude . ',' . $this->faker->longitude,
            'gambar_utama' => 'default.jpg',
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}