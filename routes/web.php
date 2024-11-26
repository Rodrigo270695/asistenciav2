<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AssistController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailhoraryController;
use App\Http\Controllers\HoraryController;
use App\Http\Controllers\PdvController;
use App\Http\Controllers\ReasonController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ZonalController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function (): RedirectResponse {
    if (auth()->check()) {
        if (auth()->user()->hasRole('asistencia')) {
            return redirect('/assist');
        }
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::get('/register', function (): RedirectResponse {
    return redirect('/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('asistencia')) {
            return redirect('/assist');
        }
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    //zonales
    Route::get('/zonal/export', [ZonalController::class, 'export'])->name('zonal.export');
    Route::get('zonal/search', [ZonalController::class, 'search' ])->name('zonal.search');
    Route::resource('zonal', ZonalController::class);
    Route::put('zonal/change/{zonal}', [ZonalController::class, 'change'])->name('zonal.change');

    //pdv
    Route::get('/pdv/export', [PdvController::class, 'export'])->name('pdv.export');
    Route::get('pdv/search', [PdvController::class, 'search' ])->name('pdv.search');
    Route::resource('pdv', PdvController::class);
    Route::put('pdv/change/{pdv}', [PdvController::class, 'change'])->name('pdv.change');

    //charges
    Route::get('charge/search', [ChargeController::class, 'search' ])->name('charge.search');
    Route::resource('charge', ChargeController::class);

    //workers
    Route::get('worker/search', [WorkerController::class, 'search' ])->name('worker.search');
    Route::resource('worker', WorkerController::class);
    Route::put('worker/change/{worker}', [WorkerController::class, 'change'])->name('worker.change');

    //reasons
    Route::get('reason/search', [ReasonController::class, 'search' ])->name('reason.search');
    Route::resource('reason', ReasonController::class);
    Route::put('reason/change/{reason}', [ReasonController::class, 'change'])->name('reason.change');

    //horaries
    Route::get('/horary/export', [HoraryController::class, 'export'])->name('horary.export');
    Route::get('horary/search', [HoraryController::class, 'search' ])->name('horary.search');
    Route::resource('horary', HoraryController::class);
    Route::put('horary/change/{horary}', [HoraryController::class, 'change'])->name('horary.change');

    //detailhoraries
    Route::get('horary/{horary}/detailhorary', [DetailhoraryController::class, 'index'])->name('detailhorary.index');
    Route::post('horary/{horary}/detailhorary', [DetailhoraryController::class, 'store'])->name('detailhorary.store');
    Route::put('detailhorary/{detailhorary}', [DetailhoraryController::class, 'update'])->name('detailhorary.update');
    Route::delete('detailhorary/{detailhorary}', [DetailhoraryController::class, 'destroy'])->name('detailhorary.destroy');

    //absences
    Route::get('absence/search', [AbsenceController::class, 'search' ])->name('absence.search');
    Route::resource('absence', AbsenceController::class);
    Route::put('absence/change/{absence}', [AbsenceController::class, 'change'])->name('absence.change');
    Route::get('/absences/download/{file}', [AbsenceController::class, 'download'])->name('absence.download');

    //users
    Route::get('user/search', [UserController::class, 'search' ])->name('user.search');
    Route::resource('user', UserController::class);

    //assists
    Route::resource('assist', AssistController::class);
    Route::get('/assist/worker/dni/{dni}', [AssistController::class, 'getWorker'])->name('assist.worker');

    //reports
    Route::get('/report/export', [ReportController::class, 'export'])->name('report.export');
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

});
