<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    ['prefix' => 'v1'],
    function () {
        Route::get('listing', [\App\Http\Controllers\SurveysController::class, 'index']);
        Route::get('listing/{code}', [\App\Http\Controllers\SurveysController::class, 'show']);
    }
);

