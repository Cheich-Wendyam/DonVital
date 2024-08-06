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
Route::middleware('auth:sanctum')->put('/updateprofile', [ProfileController::class, 'updateProfile']);
Route::post('/groupsanguin', [RegisteredUserController::class, 'BloodGroup'])
    ->middleware('auth:sanctum');

Route::get('/annonces', [AnnonceController::class, 'index']);
Route::post('/annonces', [AnnonceController::class, 'store']);
Route::get('/annonces/{id}', [AnnonceController::class, 'show']);
Route::put('/annonces/{id}', [AnnonceController::class, 'update']);
Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy']);
