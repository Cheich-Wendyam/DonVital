<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PasswordResetLinkController extends Controller
{
    /**
     * Affiche la vue de demande de réinitialisation du mot de passe.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Gère la demande d'envoi du lien de réinitialisation du mot de passe.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }

    
    
    /**
     * Envoie un email de réinitialisation de mot de passe avec un token personnalisé.
     */
    public function sendResetLinkEmail(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }

    // Génération d'un code de réinitialisation
    $code = Str::random(6); // Un code de 6 caractères, par exemple

    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        ['token' => Hash::make($code), 'created_at' => Carbon::now()]
    );

    // Envoi du mail avec le code de réinitialisation
    Mail::to($request->email)->send(new PasswordResetMail($code));

    return response()->json(['message' => 'Email de réinitialisation envoyé']);
}

    

// verifier si le code de réinitialisation est valide
public function verifyCode(Request $request)
{
    $request->validate([
        'code' => 'required',
        'email' => 'required|email'
    ]);

    $reset = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

    if (!$reset || !Hash::check($request->code, $reset->token)) {
        return response()->json(['message' => 'Code de réinitialisation invalide'], 400);
    }

    return response()->json(['message' => 'Code de réinitialisation valide']);
}

    /**
     * Réinitialise le mot de passe de l'utilisateur.
     */
public function updatePassword(Request $request)
{
    // Validation des champs
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|min:8|confirmed',
    ]);

    // Récupérer l'utilisateur en fonction de l'email
    $user = User::where('email', $request->email)->first();

    if ($user) {
        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Mot de passe mis à jour avec succès.'], 200);
    }

    return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
}
}
