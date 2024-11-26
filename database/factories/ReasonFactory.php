<?php

namespace Database\Factories;

use App\Models\Reason;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReasonFactory extends Factory
{
    protected $model = Reason::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
        ];
    }
}
