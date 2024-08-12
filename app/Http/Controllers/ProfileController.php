<?php


namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si nécessaire
            if ($user->image) {
                Storage::delete($user->image);
            }

            // Stocker la nouvelle image
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Déconnecter l'utilisateur
        Auth::logout();

        // Supprimer l'utilisateur
        $user->delete();

        // Invalider et régénérer le jeton de session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the user's profile.
     */
    public function getProfile(Request $request)
    {
        // Obtenir l'utilisateur authentifié
        $user = Auth::user();


    // Vérifiez si l'utilisateur a une image de profil
    if ($user->image) {
        // Créez l'URL complète pour l'image de profil
        $user->image = asset('storage/' . $user->image);
    }
       
        // Retourner toutes les informations de l'utilisateur
        return response()->json($user);
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {
        try {
            $user = $request->user();
            $data = $request->validated();
    
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si nécessaire
                if ($user->image) {
                    Storage::delete($user->image);
                }
    
                // Stocker la nouvelle image
                $data['image'] = $request->file('image')->store('images', 'public');
            }
    
            $user->fill($data);
    
            $user->save();
    
            // Retourner une réponse JSON de succès
            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            // Gérer les erreurs et retourner une réponse JSON d'échec
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   
}
