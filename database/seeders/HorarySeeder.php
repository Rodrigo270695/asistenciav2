<?php

namespace Database\Seeders;

use App\Models\Horary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HorarySeeder extends Seeder
{

    public function run(): void
    {
        Horary::create([
            "name" => "Horario DAM"
        ]);
    }
}
