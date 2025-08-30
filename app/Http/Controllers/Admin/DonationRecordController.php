<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationRecord;
use App\Models\User;
use App\Models\CentreSante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DonationRecordController extends Controller
{
    /**
     * Liste des dons
     */
    public function index()
    {
        $donations = DonationRecord::with(['user', 'centre'])
            ->orderBy('donation_date', 'desc')
            ->paginate(20);

        $users = User::orderBy('name')->get();
        $centres = CentreSante::orderBy('nom')->get();

        return view('carnet.index', compact('donations', 'users', 'centres'));
    }

    /**
     * Affichage du formulaire d’ajout
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $centres = CentreSante::orderBy('nom')->get();

        return view('carnet.create', compact('users', 'centres'));
    }

    /**
     * Affiche le détail d’un don avec historique utilisateur
     */
    public function show($id)
    {
        $donation = DonationRecord::with(['user', 'centre'])->findOrFail($id);

        // Récupération de l’historique de dons du même utilisateur
        $donationHistory = DonationRecord::where('user_id', $donation->user_id)
            ->orderBy('donation_date', 'asc')
            ->get()
            ->map(function ($don) {
                $don->donation_date_formatted = Carbon::parse($don->donation_date)->format('d/m/Y');
                return $don;
            });

        return view('carnet.show', compact('donation', 'donationHistory'));
    }

    /**
     * Enregistre un nouveau don
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'centre_sante_id' => 'required|exists:centre_santes,id',
            'donation_date'   => 'required|date_format:Y-m-d',
            'volume_ml'       => 'required|integer|min:200|max:500',
            'blood_type'      => 'required|string|max:3',
            'medical_notes'   => 'nullable|string',
            'certificate'     => 'nullable|file|mimes:pdf,jpg,png|max:2048'
        ]);

        $data = $request->only([
            'user_id', 'centre_sante_id', 'donation_date',
            'volume_ml', 'blood_type', 'medical_notes'
        ]);

        if ($request->hasFile('certificate')) {
            $path = $request->file('certificate')->store('certificates', 'public');
            $data['certificate_path'] = $path;
        }

        DonationRecord::create($data);

        return redirect()->route('admin.donation-records.index')
            ->with('success', 'Don ajouté avec succès au carnet.');
    }

    /**
     * Mise à jour d’un don existant
     */
    public function update(Request $request, $id)
    {
        $donation = DonationRecord::findOrFail($id);

        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'centre_sante_id' => 'required|exists:centre_santes,id',
            'donation_date'   => 'required|date_format:Y-m-d',
            'volume_ml'       => 'required|integer|min:200|max:500',
            'blood_type'      => 'required|string|max:3',
            'medical_notes'   => 'nullable|string',
            'certificate'     => 'nullable|file|mimes:pdf,jpg,png|max:2048'
        ]);

        $data = $request->only([
            'user_id', 'centre_sante_id', 'donation_date',
            'volume_ml', 'blood_type', 'medical_notes'
        ]);

        // Suppression du certificat si demandé
        if ($request->has('remove_certificate') && $donation->certificate_path) {
            Storage::disk('public')->delete($donation->certificate_path);
            $data['certificate_path'] = null;
        }

        // Mise à jour du certificat
        if ($request->hasFile('certificate')) {
            if ($donation->certificate_path) {
                Storage::disk('public')->delete($donation->certificate_path);
            }

            $path = $request->file('certificate')->store('certificates', 'public');
            $data['certificate_path'] = $path;
        }

        $donation->update($data);

        return redirect()->route('admin.donation-records.index')
            ->with('success', 'Don mis à jour avec succès.');
    }

    /**
     * Suppression d’un don
     */
    public function destroy($id)
    {
        $donation = DonationRecord::findOrFail($id);

        if ($donation->certificate_path) {
            Storage::disk('public')->delete($donation->certificate_path);
        }

        $donation->delete();

        return redirect()->route('admin.donation-records.index')
            ->with('success', 'Don supprimé avec succès.');
    }

    /**
     * Exporter la liste des dons
     */
    public function export()
    {
        // À implémenter avec Laravel Excel ou autre lib

        return response()->download(storage_path('exports/donation-records.xlsx'));
    }
    /**
 * Affichage du formulaire d’édition d’un don
 */
public function edit($id)
{
    $donation = DonationRecord::findOrFail($id);
    $users = User::orderBy('name')->get();
    $centres = CentreSante::orderBy('nom')->get();

    return view('carnet.edit', compact('donation', 'users', 'centres'));
}

}
