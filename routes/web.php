<?php

use App\Http\Controllers\DefectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login'); // ⟵ Enviar siempre al login
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Defectos
    Route::get('/defects', [DefectController::class,'index'])
        ->name('defects.index')
        ->middleware('permission:manage defects|scan defects');

    Route::get('/defects/create', [DefectController::class,'create'])
        ->name('defects.create')
        ->middleware('permission:scan defects');

    Route::post('/defects', [DefectController::class,'store'])
        ->name('defects.store')
        ->middleware('permission:scan defects');

    Route::get('/scan', [DefectController::class,'scan'])
        ->name('defects.scan')
        ->middleware('permission:scan defects');

    Route::patch('/defects/{defect}/status', [DefectController::class,'updateStatus'])
        ->name('defects.updateStatus')
        ->middleware('permission:manage defects');

    // Reportes
    Route::middleware('permission:view reports')->group(function () {
        Route::get('/reports/defects/export', [ReportController::class,'export'])
            ->name('reports.defects.export');

        Route::get('/reports/defects/weekly', [ReportController::class,'weekly'])
            ->name('reports.defects.weekly');
    });
    Route::middleware('permission:view reports')->group(function () {
        Route::get('/reports/defects/export-csv', [ReportController::class, 'exportCsv'])
            ->name('reports.defects.exportCsv');
    });
    Route::post('/defects/get-name', [DefectController::class, 'getName'])->name('defects.getName');

    Route::middleware('permission:manage users')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
