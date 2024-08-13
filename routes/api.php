<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;

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

Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'getProfile']);
Route::middleware('auth:sanctum')->post('/updateprofile', [ProfileController::class, 'updateProfile']);
Route::post('/groupsanguin', [RegisteredUserController::class, 'BloodGroup'])
    ->middleware('auth:sanctum');

Route::get('/annonces', [AnnonceController::class, 'index']);
Route::middleware('auth:sanctum')->post('/annonces', [AnnonceController::class, 'store']);
Route::get('/annonces/{id}', [AnnonceController::class, 'show']);
Route::put('/annonces/{id}', [AnnonceController::class, 'update']);
Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy']);

Route::post('/fcm', [RegisteredUserController::class, 'updateFcmToken'])
    ->middleware('auth:sanctum');

Route::get('/notifications', [AnnonceController::class, 'getNotifications'])->middleware('auth:sanctum');
Route::get('/historiques', [AnnonceController::class, 'HistoriqueAnnonces'])->middleware('auth:sanctum');

