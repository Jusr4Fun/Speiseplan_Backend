<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WocheController;
use App\Http\Controllers\WochenBestellungController;
use App\Http\Controllers\AbteilungController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UsersController::class)->group(function () {
    Route::get('/users','index');

    Route::get('/user={id}','show');

    Route::delete('/delete={id}','destroy');

    Route::post('/users','store');

    Route::post('/updateUser','update');
});

Route::controller(WocheController::class)->group(function() {
    Route::get('/wochen', 'index');

    Route::post('/wochen','store');

    Route::get('/woche={id}','show');

    Route::get('/wochen={id}=Bestellungen', 'returnWochenBestellungen');

    Route::get('/wochen={id}=SpezialEssen', 'returnSpezialEssen');
});

Route::controller(WochenBestellungController::class)->group(function() {
    Route::get('/wochenBestellungen', 'index');

    Route::get('/wochenBestellungen={id}=SpezialEssen', 'returnSpezialEssen');
});

Route::controller(AbteilungController::class)->group(function() {
    Route::get('/abteilungen', 'index');

    Route::post('/abteilung','store');

    Route::get('/abteilung={id}','show');
});