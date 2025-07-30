<?php

use App\Http\Controllers\Admin\NewItemCrudController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('jenis-barang', 'JenisBarangCrudController');
    Route::crud('history-barang', 'HistoryBarangCrudController');
    Route::crud('gudang', 'GudangCrudController');
    // Route::get('dashboard-barang', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard.barang');
    Route::get('/new-item/knn-preview', [App\Http\Controllers\Admin\NewItemCrudController::class, 'showStatistics']);
    Route::get('knn-statistics/{id}', [\App\Http\Controllers\Admin\NewItemCrudController::class, 'showStatistics'])->name('knn.statistics');

    Route::crud('new-item', 'NewItemCrudController');
    Route::crud('user', 'UserCrudController');

    Route::get('get-jenis-barang/{gudangId}', [NewItemCrudController::class, 'getJenisBarang']);

    Route::get('new-item/print-report', [\App\Http\Controllers\Admin\NewItemCrudController::class, 'printReport'])
    ->name('new-item.print-report');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
