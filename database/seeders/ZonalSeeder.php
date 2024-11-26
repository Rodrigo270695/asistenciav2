<?php

namespace Database\Seeders;

use App\Models\Zonal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZonalSeeder extends Seeder
{
    public function run(): void
    {
        Zonal::create([
            "name"=> "Chiclayo",
        ]);
        Zonal::create([
            "name"=> "Cajamarca",
        ]);
        Zonal::create([
            "name"=> "Tumbes",
        ]);
    }
}
