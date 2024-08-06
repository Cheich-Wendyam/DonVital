<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'age' => ['nullable', 'integer', 'min:1'],
            'sexe' => ['nullable', 'string', 'in:Homme,Femme'],
            'pays' => ['nullable', 'string'],
            'ville' => ['nullable', 'string'],
            'telephone' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'blood_group' => ['nullable', 'string'],
        ]);

        // Gestion du fichier d'image si présent
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'age' => $request->age,
            'sexe' => $request->sexe,
            'pays' => $request->pays,
            'ville' => $request->ville,
            'telephone' => $request->telephone,
            'image' => $imagePath, 
            'blood_group' => $request->blood_group,
        ]);

         // Attribution du rôle 'utilisateur_normal'
        $user->assignRole('utilisateur_normal');

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

        /**
     * Handle an API registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiRegister(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'age' => ['nullable', 'integer', 'min:1'],
        'sexe' => ['nullable', 'string', 'in:Homme,Femme'],
        'pays' => ['nullable', 'string'],
        'ville' => ['nullable', 'string'],
        'telephone' => ['nullable', 'string'],
        'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        'blood_group' => ['nullable', 'string'],
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Gestion du fichier d'image si présent
    $imagePath = null;
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $image->store('images', 'public');
    }

    // Déconnexion de l'utilisateur actuel
    Auth::logout();

    // Création de l'utilisateur
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'age' => $request->age,
        'sexe' => $request->sexe,
        'pays' => $request->pays,
        'ville' => $request->ville,
        'telephone' => $request->telephone,
        'image' => $imagePath,
        'blood_group' => $request->blood_group,
    ]);

    // Attribution du rôle 'utilisateur_normal'
    if ($user->assignRole('utilisateur_normal')) {
        // Assurez-vous que la gestion des rôles est configurée
    }

    event(new Registered($user));

    // Connexion automatique
    Auth::login($user);

    // Générer un token API
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'User registered successfully.',
        'user' => $user,
        'token' => $token,
    ], 201);
}


     /**
     * Handle an API login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiLogin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Générer un token API
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully.',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function BloodGroup(Request $request): JsonResponse
{
    $request->validate([
        'blood_group' => ['required', 'string'],
    ]);

    $user = $request->user();
    $user->blood_group = $request->input('blood_group');
    $user->save();

    return response()->json(['message' => 'Groupe sanguin enregistré avec succès.']);
}

}
