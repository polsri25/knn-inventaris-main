<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/knn-statistics', [App\Http\Controllers\Admin\NewItemCrudController::class, 'showStatistics']);
