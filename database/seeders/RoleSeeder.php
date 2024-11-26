<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(["name"=> "administrador",]);
        Role::create(["name"=> "visualizador",]);
        Role::create(["name"=> "supervisor",]);
        Role::create(["name"=> "asistencia",]);
    }
}
