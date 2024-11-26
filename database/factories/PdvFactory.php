<?php

namespace Database\Factories;

use App\Models\Pdv;
use Illuminate\Database\Eloquent\Factories\Factory;


class PdvFactory extends Factory
{
    protected $model = Pdv::class;

    public function definition()
    {
        return [
            'unit' => 'DAM',
            'spot' => $this->faker->unique()->word,
            'address' => $this->faker->address,
            'zonal_id' => $this->faker->numberBetween(1, 3), // Aleatorio entre 1 y 3
            'horary_id' => 1, // Ajusta según tus necesidades
        ];
    }
}
