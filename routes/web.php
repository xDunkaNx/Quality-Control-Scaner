<?php

use App\Http\Controllers\DefectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/defects', [DefectController::class,'index'])->name('defects.index')->middleware('permission:manage defects|scan defects');
    Route::get('/defects/create', [DefectController::class,'create'])->name('defects.create')->middleware('permission:scan defects');
    Route::post('/defects', [DefectController::class,'store'])->name('defects.store')->middleware('permission:scan defects');
});

require __DIR__.'/auth.php';
