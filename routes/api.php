<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CentreSanteController;
use App\Http\Controllers\DonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PubController;
use App\Http\Controllers\SendNotification;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'apiRegister']);

Route::post('login', [App\Http\Controllers\Auth\RegisteredUserController::class, 'apiLogin']);
Route::post('logout', [ProfileController::class, 'deconnexion'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'getProfile']);
Route::middleware('auth:sanctum')->post('/updateprofile', [ProfileController::class, 'updateProfile']);
Route::post('/groupsanguin', [RegisteredUserController::class, 'BloodGroup'])
    ->middleware('auth:sanctum');

Route::get('/annonces', [AnnonceController::class, 'index']);
Route::middleware('auth:sanctum')->post('/annonces', [AnnonceController::class, 'store']);
Route::get('/annonces/{id}', [AnnonceController::class, 'show'])->middleware('auth:sanctum');
//Route::put('/annonces/{id}', [AnnonceController::class, 'update']);
//Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy']);

Route::post('/fcm', [RegisteredUserController::class, 'updateFcmToken']);

Route::get('/notifications', [AnnonceController::class, 'getNotifications'])->middleware('auth:sanctum');
Route::post('/mark/{id}', [AnnonceController::class, 'markAsRead'])->middleware('auth:sanctum');
Route::get('/historiques', [AnnonceController::class, 'HistoriqueAnnonces'])->middleware('auth:sanctum');


Route::get('/notifications/{id}/annonce', [AnnonceController::class, 'getAnnonceByNotification']);

Route::apiResource('centres', CentreSanteController::class)->middleware('auth:sanctum');
Route::apiResource('dons',DonController::class)->middleware('auth:sanctum');


Route::get('/annonce/{id}/dons', [AnnonceController::class, 'getDons'])->middleware('auth:sanctum');
Route::post('annonceEtat/{id}', [AnnonceController::class, 'desactiverAnnonce'])->middleware('auth:sanctum');
Route::get('myDon', [DonController::class, 'myDon'])->middleware('auth:sanctum');
Route::post('confirmDon/{id}', [DonController::class, 'confirmDon'])->middleware('auth:sanctum');
Route::post('annulerDon/{id}', [DonController::class, 'annulerDon'])->middleware('auth:sanctum');
Route::apiResource('pub',PubController::class)->middleware('auth:sanctum');

Route::post('/passwordlink', [PasswordResetLinkController::class, 'sendResetLinkEmail']);
Route::post('/passwordreset', [PasswordResetLinkController::class, 'updatePassword']);
Route::post('/verify', [PasswordResetLinkController::class, 'verifyCode'] );

Route::get('/send', [SendNotification::class, 'sendNotification']);
