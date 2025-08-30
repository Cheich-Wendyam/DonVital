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
use App\Services\FirebaseService;
use App\Http\Controllers\CampagneController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\Admin\AdminConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DonController;
use App\Http\Controllers\Admin\DonationRecordController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\CampagneParticipationController;


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


Route::get('/admin', [DashboardController::class, 'index'])->middleware(['auth', 'block.user','verified', 'role:admin|éditeur'])->name('admin');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    return redirect()->route('admin');
});
route::post('/register' ,[RegisteredUserController::class,'register']);

Route::post('block/{id}', [RegisteredUserController::class, 'toggleBlockUser'])->name('block');

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

Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
Route::resource('rewards', RewardController::class);
Route::post('rewards/{reward}/toggle-status', [RewardController::class, 'toggleStatus'])
    ->name('rewards.toggle-status');


Route::get('/admin/conversations', [AdminConversationController::class, 'index'])->name('admin.conversations.index');
// Exporter en PDF les participants d'une campagne
Route::get('/campagnes/{id}/participants/pdf', [CampagneController::class, 'exportParticipantsPdf'])
    ->name('campagnes.participants.pdf');





Route::middleware(['auth', 'role:admin|éditeur', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dons', [DonController::class, 'listeAdmin'])->name('dons.index');
    Route::post('/dons/{id}/confirmer', [DonController::class, 'confirmerViaWeb'])->name('dons.confirmer');
    Route::post('/dons/{id}/annuler', [DonController::class, 'annulerViaWeb'])->name('dons.annuler');
});



// Gestion du carnet de dons
Route::prefix('admin/donation-records')->name('admin.donation-records.')->group(function () {
    Route::get('/', [DonationRecordController::class, 'index'])->name('index');
    Route::get('/create', [DonationRecordController::class, 'create'])->name('create');
    Route::post('/', [DonationRecordController::class, 'store'])->name('store');
    Route::get('/export', [DonationRecordController::class, 'export'])->name('export');
    Route::get('/{id}', [DonationRecordController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [DonationRecordController::class, 'edit'])->name('edit'); // <-- ajout de la route edit
    Route::put('/{id}', [DonationRecordController::class, 'update'])->name('update');
    Route::delete('/{id}', [DonationRecordController::class, 'destroy'])->name('destroy');
});

Route::prefix('education')->name('education.')->group(function () {
    Route::get('dashboard', [EducationController::class, 'dashboard'])->name('dashboard');
    Route::get('contents', [EducationController::class, 'index'])->name('index');
    Route::get('contents/create', [EducationController::class, 'create'])->name('create');
    Route::post('contents', [EducationController::class, 'store'])->name('store');
    Route::get('contents/{id}', [EducationController::class, 'show'])->name('show');
    Route::get('contents/{id}/edit', [EducationController::class, 'edit'])->name('edit');
    Route::put('contents/{id}', [EducationController::class, 'update'])->name('update');
    Route::delete('contents/{id}', [EducationController::class, 'destroy'])->name('destroy');
    Route::get('statistics', [EducationController::class, 'statistics'])->name('statistics');
});



Route::resource('campagnes', CampagneController::class)->except(['show']);
Route::post('campagnes/{campagne}/toggle-status', [CampagneController::class, 'toggleStatus'])
    ->name('campagnes.toggle-status');
Route::get('/campagnes/{id}/participants', [CampagneParticipationController::class, 'showParticipants'])
    ->name('campagnes.participants');
Route::get('/admin/campagnes/participants', [\App\Http\Controllers\CampagneParticipationController::class, 'allParticipants'])
    ->name('campagnes.participants.all');





Route::get('/annonces/attente', [AnnonceController::class, 'attente'])->name('annonce.attente');
Route::patch('/annonces/{id}/reject', [AnnonceController::class, 'reject'])->name('annonces.reject');

Route::get('/utilisateurs', [UserController::class, 'index'])->name('utilisateurs');
Route::post('/user', [UserController::class, 'createUser'])->name('users.store');
Route::put('/users/{id}', [UserController::class, 'updateUser'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'deleteUser'])->name('users.destroy');
Route::post('users/{id}/change-role', [UserController::class, 'changeRole'])->name('users.changeRole');


Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');



Route::resource('/roles', RoleController::class);

Route::get('/roles/{role}/assign-permissions', [RoleController::class, 'showAssignPermissionsForm'])->name('roles.assign_permissions_form');
Route::post('/roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assign_permissions');

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
Route::post('/annonces', [AnnonceController::class, 'store2'])->name('annonces.store');
Route::get('/annonce/{id}', [AnnonceController::class, 'showAnnonce'])->name('annonces.show');




Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('conversations', ConversationController::class);
    Route::resource('messages', MessageController::class);

});

Route::middleware(['auth', 'role:admin|éditeur', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/conversations', [AdminConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/create', [AdminConversationController::class, 'create'])->name('conversations.create');
    Route::post('/conversations', [AdminConversationController::class, 'store'])->name('conversations.store');
    Route::get('/conversations/{id}/messages', [AdminConversationController::class, 'show'])->name('conversations.messages');
    Route::delete('/conversations/{id}', function ($id) {
        \App\Models\Conversation::findOrFail($id)->delete();
        return redirect()->route('admin.conversations.index')->with('success', 'Conversation supprimée.');
    })->name('conversations.destroy');
});




require __DIR__.'/auth.php';


Route::get('/notify', function () {
    $firebaseService = new FirebaseService();
    $firebaseService->sendNotification("dDAdjIXtTruqg6XfwsZI7A:APA91bGX8wh4hXS7vmn1yWJIiWY02pN5O77MZ1uqb5HiGbmH9ubpJQG26wrwsyFueBwcqiLESY6JgexWckOLos_ezgK168U0lEoukVslv-6-q1qqFeVGE6indlcin8UcQL7Cfsk_yFni",
     'Annonce de demande de sang',
     'Une nouvelle annonce de demande de sang correspond à votre groupe sanguin!', []);
});



