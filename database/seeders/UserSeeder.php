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
        ]);
        $admin->assignRole('admin');
    }
}
