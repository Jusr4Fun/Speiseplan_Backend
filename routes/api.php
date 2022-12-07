<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WocheController;
use App\Http\Controllers\WochenBestellungController;
use App\Http\Controllers\AbteilungController;
use App\Http\Controllers\RolleController;
use App\Http\Controllers\TeilnehmerController;
use App\Http\Controllers\SupportMailController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SpezialEssenController;

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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users/auth', AuthController::class);
});

Route::middleware(['auth:sanctum'])->controller(UsersController::class)->group(function () {
    Route::get('/users','returnAllUsers');

    Route::delete('/deleteUser/{id}','deleteUser');

    Route::post('/storeUser','storeUser');

    Route::post('/updateUser','updateUser');

    Route::post('/updatePasswort' , 'updatePasswort');
});

Route::middleware(['auth:sanctum'])->controller(WocheController::class)->group(function() {
    Route::get('/woche/{id}','returnSpezifischeWoche');

    Route::get('/wochen/{id}/SpezialEssen', 'returnSpezialEssen');

    Route::post('/aktuelleWoche', 'returnAktuelleWoche');

    Route::post('/naechsteWoche', 'returnNaechsteWoche');
});

Route::middleware(['auth:sanctum'])->controller(WochenBestellungController::class)->group(function() {
    Route::post('/updateOrCreateWochenBestellung', 'updateOrCreateWochenBestellungSpezialBestellungen');
});

Route::middleware(['auth:sanctum'])->controller(AbteilungController::class)->group(function() {
    Route::get('/abteilungen', 'returnAlleAbteilungen');

    Route::get('/abteilung/{abteilung}/woche/{woche}','returnBestellungenWocheAbteilung');

    Route::get('/abteilungTeilnehmer/{id}','returnTeilnehmerAbteilung');

    Route::get('/abteilungTeilnehmerName/{id}/Bestellung/{bestellung}','returnTeilnehmerNameBestellung');
});

Route::middleware(['auth:sanctum'])->controller(RolleController::class)->group(function () {
    Route::get('/RolleRoleID','returnRollenRoleID');
});

Route::middleware(['auth:sanctum'])->controller(SpezialEssenController::class)->group(function () {
    Route::post('/deleteSpezialEssen','deleteSpezialEssen');
});

Route::middleware(['auth:sanctum'])->controller(ImageController::class)->group(function () {
    Route::post('/uploadImage','uploadImage');

    Route::get('/ImageWoche/{id}','getImageWoche');
});

Route::middleware(['auth:sanctum'])->controller(TeilnehmerController::class)->group(function () {
    Route::delete('/deleteTeilnehmer/{id}','deleteTeilnehmer');

    Route::post('/updateTeilnehmer','updateTeilnehmer');

    Route::post('/storeTeilnehmer','storeTeilnehmer');
});

Route::controller(SupportMailController::class)->group(function () {
    Route::post('/SupportMail','SupportMail');
});