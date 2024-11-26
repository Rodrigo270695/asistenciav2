<?php

namespace Database\Factories;

use App\Models\Absence;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsenceFactory extends Factory
{
    protected $model = Absence::class;

    public function definition()
    {
        return [
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->optional()->date,
            'file' => $this->faker->optional()->filePath(),
            'status' => $this->faker->randomElement(['Por aprobar', 'Aprobado', 'Rechazado', 'Observado']),
            'status_detail' => $this->faker->optional()->sentence,
            'worker_id' => $this->faker->numberBetween(1, 100), // Ajusta según tus datos
            'reason_id' => $this->faker->numberBetween(1, 10), // Ajusta según tus datos
        ];
    }
}
