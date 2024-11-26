<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $admin = User::create([
            "name"=> "Rodrigo",
            "email"=> "rodrigo_06_27@hotmail.com",
            "password"=> Hash::make("admin"),
            "pdv_id"=> 1,
        ]);
        $admin->assignRole('administrador');
        $viewer = User::create([
            "name"=> "Visualizador",
            "email"=> "visualizador@gmail.com",
            "password"=> Hash::make("visualizador"),
            "pdv_id"=> 1,
        ]);
        $viewer->assignRole('visualizador');
        $supervisor = User::create([
            "name"=> "Supervisor",
            "email"=> "supervisor@gmail.com",
            "password"=> Hash::make("supervisor"),
            "pdv_id"=> 2,
        ]);
        $supervisor->assignRole('supervisor');
        $assist = User::create([
            "name"=> "Asistencia",
            "email"=> "asistencia@gmail.com",
            "password"=> Hash::make("asistencia"),
            "pdv_id"=> 3,
        ]);
        $assist->assignRole('asistencia');
    }
}
