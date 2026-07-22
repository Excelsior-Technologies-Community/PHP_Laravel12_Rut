<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\ImportController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard',
    [CitizenController::class, 'dashboard'])
    ->name('dashboard');

Route::get('/citizens/trash',
    [CitizenController::class, 'trash'])
    ->name('citizens.trash');

Route::post('/citizens/{id}/restore',
    [CitizenController::class, 'restore'])
    ->name('citizens.restore');

Route::delete('/citizens/{id}/force-delete',
    [CitizenController::class, 'forceDelete'])
    ->name('citizens.forceDelete');

Route::get('/citizen/{id}/card',
    [CitizenController::class, 'printCard'])
    ->name('citizens.card');

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

Route::get('/search-live',
    [CitizenController::class, 'liveSearch'])
    ->name('citizens.liveSearch');

Route::get('/citizens-export',
    [CitizenController::class, 'export'])
    ->name('citizens.export');

Route::get('/report',
    [CitizenController::class, 'generateReport'])
    ->name('citizens.report');

Route::get('/import',
    [ImportController::class, 'create'])
    ->name('import.create');

Route::post('/import',
    [ImportController::class, 'store'])
    ->name('import.store');

Route::get('/import/template',
    [ImportController::class, 'template'])
    ->name('import.template');

Route::post('/parse-id',
    [CitizenController::class, 'parseId'])
    ->name('parse.id');
