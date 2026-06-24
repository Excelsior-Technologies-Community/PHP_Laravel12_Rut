<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitizenController;

Route::get('/', function () {
    return redirect()->route('citizens.index');
});

Route::resource('citizens', CitizenController::class);

Route::get('/generator',
    [CitizenController::class, 'generator'])
    ->name('generator');

Route::post('/search-rut',
    [CitizenController::class, 'search'])
    ->name('search.rut');

Route::get('/search',
    [CitizenController::class, 'searchCitizen'])
    ->name('citizens.search');

Route::get('/citizens-export',
    [CitizenController::class, 'export'])
    ->name('citizens.export');