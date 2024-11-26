<?php

namespace Database\Seeders;

use App\Models\Pdv;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PdvSeeder extends Seeder
{
    public function run(): void
    {
        Pdv::factory()->count(100)->create();
    }
}
