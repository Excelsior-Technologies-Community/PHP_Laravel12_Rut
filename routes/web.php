<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitizenController;

// Route::get('/', function () {
//     return view('welcome');
// });


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
