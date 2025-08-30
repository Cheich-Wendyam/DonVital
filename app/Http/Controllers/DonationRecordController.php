<?php

namespace App\Http\Controllers;

use App\Models\DonationRecord;
use App\Models\CentreSante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DonationRecordController extends Controller
{
    /**
     * Afficher les enregistrements de dons de l'utilisateur connecté.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Pagination des enregistrements de dons de l'utilisateur connecté
        $records = Auth::user()->donationRecords()->with('centre')->paginate(10);  // Pagination de 10 résultats par page
        return response()->json($records);
    }

    /**
     * Créer un nouvel enregistrement de don de sang.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validation des données de la requête
        $request->validate([
            'centre_sante_id' => 'required|exists:centre_santes,id',
            'donation_date' => 'required|date_format:Y-m-d',
            'volume_ml' => 'required|integer|min:200|max:500',
            'blood_type' => 'required|string|max:3',
            'medical_notes' => 'nullable|string',
            'certificate' => 'nullable|file|mimes:pdf,jpg,png|max:2048'
        ]);

        // Gestion du fichier certificat
        $certificatePath = null;
        if ($request->hasFile('certificate')) {
            $certificatePath = $request->file('certificate')->store('certificates', 'public');
        }

        // Création de l'enregistrement de don
        $record = DonationRecord::create([
            'user_id' => Auth::id(),
            'centre_sante_id' => $request->centre_sante_id,
            'donation_date' => $request->donation_date,
            'volume_ml' => $request->volume_ml,
            'blood_type' => $request->blood_type,
            'medical_notes' => $request->medical_notes,
            'certificate_path' => $certificatePath
        ]);

        return response()->json($record, 201);
    }

    /**
     * Supprimer un enregistrement de don de sang.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Récupérer l'enregistrement de don
        $record = DonationRecord::findOrFail($id);

        // Vérification des autorisations
        if ($record->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Supprimer le certificat si existant
        if ($record->certificate_path) {
            Storage::disk('public')->delete($record->certificate_path);
        }

        // Supprimer l'enregistrement de don
        $record->delete();
        return response()->json(null, 204);
    }

    /**
     * Récupérer la prochaine date éligible pour un don.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function nextEligibleDate()
    {
        // Récupérer le dernier don de l'utilisateur
        $lastDonation = Auth::user()->donationRecords()
            ->latest('donation_date')
            ->first();

        // Si aucun don n'a été effectué, retourner null
        if (!$lastDonation) {
            return response()->json(['date' => null]);
        }

        // Calculer la date éligible suivante (90 jours après le dernier don)
        $nextDate = $lastDonation->donation_date->addDays(90);

        return response()->json(['date' => $nextDate->toDateString()]);
    }
   public function show($id)
{
    // Récupérer l'enregistrement de don particulier
    $donation = DonationRecord::findOrFail($id);

    // Récupérer l'historique des dons de l'utilisateur connecté
    $donationHistory = DonationRecord::where('user_id', $donation->user_id)
                                      ->orderBy('donation_date', 'asc')
                                      ->get();

    // Passer les données à la vue
    return view('carnet.show', compact('donation', 'donationHistory'));
}


}
