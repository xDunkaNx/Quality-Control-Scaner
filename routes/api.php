<?php

use App\Http\Controllers\Api\CatalogController;
use Illuminate\Support\Facades\Route;

Route::middleware('integration.token')->prefix('catalogs')->group(function () {
    Route::get('defect-types', [CatalogController::class, 'defectTypes']);
    Route::get('locations', [CatalogController::class, 'locations']);
});

