<?php

namespace Database\Factories;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;


class WorkerFactory extends Factory
{
    protected $model = Worker::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'document_type' => $this->faker->randomElement(['DNI', 'CARNET DE EXTRANJERIA', 'OTROS']),
            'num_document' => $this->faker->unique()->numerify('###########'),
            'entry_date' => $this->faker->date,
            'exit_date' => $this->faker->optional()->date,
            'birth_date' => $this->faker->optional()->date,
            'phone' => $this->faker->optional()->numerify('###########'),
            'email' => $this->faker->optional()->safeEmail,
            'address' => $this->faker->unique()->address,
            'charge_id' => $this->faker->numberBetween(1, 10), // Ajusta según tus datos
            'pdv_id' => $this->faker->numberBetween(1, 100), // Ajusta según tus datos
        ];
    }
}
