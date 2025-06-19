<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;



Route::get('/', [SearchController::class, 'index']);
Route::post('/search', [SearchController::class, 'search']);
Route::get('/export_csv/{uid}', [SearchController::class, 'export_csv'])->name('results.csv');
Route::get('/export_all_csv', [SearchController::class, 'export_all_csv']);
