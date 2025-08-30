<?php

namespace App\Http\Controllers;

use App\Models\Campagne;
use App\Models\CentreSante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // ✅ Déjà importé
use Illuminate\Support\Str;
use App\Models\User;
use App\Services\FirebaseService;
use Barryvdh\DomPDF\Facade\Pdf;

class CampagneController extends Controller
{
    public function index()
    {
        $campagnes = Campagne::latest()->paginate(10);
        $centres = CentreSante::all();

        return view('campagnes.index', compact('campagnes', 'centres'));
    }

    public function create()
    {
        $centres = CentreSante::all();
        return view('campagnes.create', compact('centres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lieu' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'groupes_cibles' => 'required|string',
            'centre_sante_id' => 'required|exists:centre_santes,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['groupes_cibles'] = array_map('trim', explode(',', $request->groupes_cibles));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('campagnes', 'public');
            $validated['image_url'] = $path;
        }

        $validated['is_active'] = true;

        $campagne = Campagne::create($validated);
        $this->notifyNewCampagne($campagne);

        return redirect()->route('campagnes.index')
            ->with('success', 'Campagne créée avec succès!');
    }

    public function edit(Campagne $campagne)
    {
        $centres = CentreSante::all();
        return view('campagnes.edit', compact('campagne', 'centres'));
    }

    public function update(Request $request, Campagne $campagne)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lieu' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'groupes_cibles' => 'required|string',
            'centre_sante_id' => 'required|exists:centre_santes,id',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'sometimes|boolean'
        ]);

        $validated['groupes_cibles'] = array_map('trim', explode(',', $request->groupes_cibles));

        if ($request->hasFile('image')) {
            if ($campagne->image_url) {
                Storage::disk('public')->delete($campagne->image_url);
            }
            $path = $request->file('image')->store('campagnes', 'public');
            $validated['image_url'] = $path;
        }

        $campagne->update($validated);

        return redirect()->route('campagnes.index')
            ->with('success', 'Campagne mise à jour avec succès!');
    }

    public function destroy(Campagne $campagne)
    {
        if ($campagne->image_url) {
            Storage::disk('public')->delete($campagne->image_url);
        }

        $campagne->delete();

        return redirect()->route('campagnes.index')
            ->with('success', 'Campagne supprimée avec succès!');
    }

    public function toggleStatus(Campagne $campagne)
    {
        $campagne->update(['is_active' => !$campagne->is_active]);

        return back()->with('success', 'Statut de la campagne mis à jour!');
    }

    private function notifyNewCampagne(Campagne $campagne): void
    {
        $title = "Nouvelle campagne de don de sang !";
        $body = "{$campagne->titre} à {$campagne->lieu} - le {$campagne->date_debut->format('d/m/Y')}";

        $tokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

        if (empty($tokens)) {
            \Log::info('Aucun token FCM disponible pour la notification de campagne.');
            return;
        }

        $data = [
            'type' => 'campagne',
            'campagne_id' => (string) $campagne->id,
            'titre' => $campagne->titre,
            'lieu' => $campagne->lieu,
        ];

        try {
            app(\App\Services\FirebaseService::class)->queueBulkNotifications($tokens, $title, $body, $data);
            \Log::info("Notification campagne envoyée à " . count($tokens) . " utilisateurs.");
        } catch (\Throwable $e) {
            \Log::error("Erreur notification campagne : " . $e->getMessage());
        }
    }

    public function show($id)
    {
        $campagne = Campagne::with('centreSante')->find($id);

        if (!$campagne) {
            \Log::warning('Campagne non trouvée', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Campagne non trouvée'
            ], 404);
        }

        \Log::info('Campagne trouvée', ['id' => $id, 'titre' => $campagne->titre]);

        return response()->json([
            'success' => true,
            'data' => $campagne
        ]);
    }

    public function activeCampagnes()
    {
        try {
            $now = now()->toDateString();
            $campagnes = Campagne::where('is_active', true)
                ->where('date_fin', '>=', $now)
                ->with('centreSante')
                ->orderBy('date_debut', 'asc')
                ->get()
                ->map(function ($campagne) {
                    // CORRECTION PRINCIPALE : Utilisation de Storage::url()
                    return [
                        'id' => $campagne->id,
                        'titre' => $campagne->titre,
                        'description' => $campagne->description,
                        'lieu' => $campagne->lieu,
                        'date_debut' => $campagne->date_debut,
                        'date_fin' => $campagne->date_fin,
                        'groupes_cibles' => $campagne->groupes_cibles,
                        'is_active' => $campagne->is_active,
                        'centre_sante' => $campagne->centreSante?->nom,
                        'image_url' => $campagne->image_url,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $campagnes
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur serveur dans activeCampagnes', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur interne du serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function exportParticipantsPdf($id)
    {
        $campagne = Campagne::with('participants.user')->findOrFail($id);

        // Récupération du chemin de l’image si elle existe
        $imagePath = $campagne->image_url
            ? public_path('storage/' . $campagne->image_url)
            : null;

        $pdf = Pdf::loadView('pdf.participants', compact('campagne', 'imagePath'))
                ->setPaper('A4', 'portrait');

        return $pdf->download('participants_campagne_'.$campagne->id.'.pdf');
    }

}
