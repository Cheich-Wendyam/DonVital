<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CentreSanteController;
use App\Http\Controllers\PubController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('centres/create', [CentreSanteController::class, 'create'])->name('centre_sante.create')->middleware('auth');
Route::post('centresante', [CentreSanteController::class, 'store'])->name('centre_sante.store');
Route::post('pub', [PubController::class, 'store'])->name('pub.store');
Route::get('publication', [PubController::class, 'create'])->name('pub.create')->middleware('auth');


require __DIR__.'/auth.php';
