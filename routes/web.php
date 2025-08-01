<?php

use App\Http\Controllers\Admin\NewItemCrudController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/knn-statistics', [App\Http\Controllers\Admin\NewItemCrudController::class, 'showStatistics']);

Route::get('get-jenis-barang/{gudangId}', [NewItemCrudController::class, 'getJenisBarang']);
