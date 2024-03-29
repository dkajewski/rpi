<?php

use App\Http\Controllers\NotesController;
use App\Http\Controllers\WeatherController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([], function () {
    Route::post('auth', function () { return 1; });
    Route::post('deleteNote', [NotesController::class, 'deleteNote']);
    Route::get('getAllFutureNotes', [NotesController::class, 'getAllFutureNotes']);
    Route::get('getCurrentWeather', [WeatherController::class, 'getCurrentWeather']);
    Route::get('getNotes', [NotesController::class, 'index']);
    Route::post('saveNote', [NotesController::class, 'store']);
});

