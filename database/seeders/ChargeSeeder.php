<?php

namespace Database\Seeders;

use App\Models\Charge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChargeSeeder extends Seeder
{
    public function run(): void
    {
        Charge::insert([
            ['id' => 1, 'name' => 'ASESOR DE VENTAS', 'description' => ''],
            ['id' => 2, 'name' => 'SUPERVISOR MASIVO AGENCIA', 'description' => ''],
            ['id' => 3, 'name' => 'ASESOR DE VENTAS VOLANTE', 'description' => ''],
            ['id' => 4, 'name' => 'ANALISTA COMERCIAL', 'description' => ''],
            ['id' => 5, 'name' => 'ALMACENERO(A)', 'description' => ''],
            ['id' => 6, 'name' => 'SUPERVISOR DE VENTAS I', 'description' => ''],
            ['id' => 7, 'name' => 'CAJERO (A) PART TIME', 'description' => ''],
            ['id' => 8, 'name' => 'ASESOR MULTISKILL', 'description' => ''],
            ['id' => 9, 'name' => 'ALMACENERO(A) PART TIME', 'description' => ''],
            ['id' => 10, 'name' => 'BACK OFFICE', 'description' => ''],
            ['id' => 11, 'name' => 'CAJERO(A)', 'description' => ''],
            ['id' => 12, 'name' => 'COORDINADOR DE BIENVENIDA', 'description' => ''],
            ['id' => 13, 'name' => 'OP. LIMPIEZA', 'description' => ''],
            ['id' => 14, 'name' => 'ASESOR COMERCIAL', 'description' => ''],
            ['id' => 15, 'name' => 'ANFITRION(A)', 'description' => ''],
            ['id' => 16, 'name' => 'JEFE DE TIENDA I', 'description' => ''],
            ['id' => 17, 'name' => 'ANFITRION (A) PART TIME', 'description' => ''],
            ['id' => 18, 'name' => 'ORIENTADOR', 'description' => ''],
            ['id' => 19, 'name' => 'JEFE DE TIENDA II', 'description' => ''],
            ['id' => 20, 'name' => 'ANALISTA DE SISTEMAS', 'description' => ''],
            ['id' => 21, 'name' => 'ASISTENTE DE VENTAS MASIVO', 'description' => ''],
            ['id' => 22, 'name' => 'ASISTENTE DE COBRANZA', 'description' => ''],
            ['id' => 23, 'name' => 'JEFE DE VENTAS', 'description' => ''],
            ['id' => 24, 'name' => 'GERENTE TERRITORIAL', 'description' => ''],
            ['id' => 25, 'name' => 'ANALISTA DE CONTROL DE ALMACENES', 'description' => ''],
            ['id' => 26, 'name' => 'JEFE TERRITORIAL', 'description' => ''],
            ['id' => 27, 'name' => 'CAPACITADOR (A)', 'description' => ''],
            ['id' => 28, 'name' => 'AUXILIAR DE TIENDA', 'description' => ''],
            ['id' => 29, 'name' => 'SEGURIDAD', 'description' => ''],
            ['id' => 30, 'name' => 'GESTOR VIRTUAL', 'description' => ''],
            ['id' => 31, 'name' => 'COORDINADOR (A) DE RECUPEROS', 'description' => ''],
            ['id' => 32, 'name' => 'RECLUTADOR', 'description' => ''],
            ['id' => 33, 'name' => 'BACK FIJA', 'description' => ''],
            ['id' => 34, 'name' => 'SUPERVISOR FIJA', 'description' => ''],
            ['id' => 35, 'name' => 'SUPERVISOR DE VENTAS II', 'description' => ''],
            ['id' => 36, 'name' => 'JEFE DE CANAL POSTPAGO DE LA AD', 'description' => ''],
            ['id' => 37, 'name' => 'ASISTENTE DE OPERACIONES', 'description' => ''],
            ['id' => 38, 'name' => 'JEFE DE OPERACIONES', 'description' => ''],
            ['id' => 39, 'name' => 'ANALISTA DE VENTAS', 'description' => ''],
            ['id' => 40, 'name' => 'JEFE DE CONTROL DE ALMACENES', 'description' => ''],
            ['id' => 41, 'name' => 'GERENTE REGIONAL', 'description' => ''],
            ['id' => 42, 'name' => 'ANALISTA DE GESTION DE RECLAMOS', 'description' => ''],
        ]);
    }
}
