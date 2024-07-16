<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandmodelController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\HoraryController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\RouteZonesController;
use App\Http\Controllers\StatusrouteController;
use App\Http\Controllers\TypeuserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiclecolorController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleimageController;
use App\Http\Controllers\VehicleoccupantController;
use App\Http\Controllers\VehicleroutesController;
use App\Http\Controllers\VehicletypeController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ZonecoordController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::get('/register', function () {
    return redirect('/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    //rutas
});
