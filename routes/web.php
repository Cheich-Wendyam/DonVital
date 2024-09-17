<?php

use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CentreSanteController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PubController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;

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


Route::get('/admin', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'role:admin|éditeur'])->name('admin');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/profiles', function () {
    return view('profile');
})->name('profile');



Route::get('/annonces/fermees', [AnnonceController::class, 'fermees'])->name('annonce.fermees');


Route::get('/annonces/{id}/dons', [AnnonceController::class, 'dons'])->name('annonce.dons');





Route::get('/annonces/attente', [AnnonceController::class, 'attente'])->name('annonce.attente');
Route::patch('/annonces/{id}/reject', [AnnonceController::class, 'reject'])->name('annonces.reject');

Route::get('/utilisateurs', [Controller::class, 'index'])->name('utilisateurs');
Route::post('/user', [Controller::class, 'createUser'])->name('users.store');
Route::put('/users/{id}', [Controller::class, 'updateUser'])->name('users.update');

Route::delete('/users/{id}', [Controller::class, 'deleteUser'])->name('users.destroy');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');



Route::resource('/roles', RoleController::class);

Route::get('/roles/{role}/assign-permissions', [RoleController::class, 'showAssignPermissionsForm'])->name('roles.assign_permissions_form');
Route::post('/roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assign_permissions');

Route::post('users/{id}/change-role', [Controller::class, 'changeRole'])->name('users.changeRole');
Route::resource('/permissions', PermissionController::class);

Route::post('/pub', [PubController::class, 'store'])->name('pub.store');
Route::get('/pub', [PubController::class, 'getPub'])->name('pub.index');
Route::put('/pub/{id}', [PubController::class, 'update'])->name('pub.update');
Route::delete('/pub/{id}', [PubController::class, 'destroy'])->name('pub.destroy');
Route::post('/centre', [CentreSanteController::class, 'CreateCentre'])->name('centre_sante.store');
Route::get('/centre', [CentreSanteController::class, 'getCentreSante'])->name('centre_sante.index');
Route::put('/centre/{id}', [CentreSanteController::class, 'update'])->name('centre_sante.update');
Route::delete('/centre/{id}', [CentreSanteController::class, 'destroy'])->name('centre_sante.destroy');





Route::get('centres/create', [CentreSanteController::class, 'create'])->name('centre_sante.create')->middleware('auth');
Route::get('publication', [PubController::class, 'create'])->name('pub.create')->middleware('auth');
Route::get('/annonces', [AnnonceController::class, 'getAnnonces'])->name('annonce.index');
Route::put('/annonces/{id}', [AnnonceController::class, 'update'])->name('annonces.update');

Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy'])->name('annonces.destroy');
Route::patch('/annonces/{id}', [AnnonceController::class, 'activerAnnonce'])->name('annonces.approve');
Route::post('/annonces', [AnnonceController::class, 'store'])->name('annonces.store');
Route::get('/annonce/{id}', [AnnonceController::class, 'showAnnonce'])->name('annonces.show');


require __DIR__.'/auth.php';
