<?php

use Illuminate\Support\Facades\Route;
use Krunalvatvani\LocationDropdowns\Http\Controllers\LocationController;

Route::prefix('api/locations')->group(function () {
    Route::get('/countries', [LocationController::class, 'getCountries']);
    Route::get('/states/{country}', [LocationController::class, 'getStates']);
    Route::get('/cities/{state}', [LocationController::class, 'getCities']);
    Route::get('/flags/{iso2}', [LocationController::class, 'getFlag']);
});
