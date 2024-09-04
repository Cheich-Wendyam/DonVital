<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Don;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $usersLastWeek = User::where('last_login_at', '>=', $now->subWeek())->count();
        $usersLastMonth = User::where('last_login_at', '>=', $now->subMonth())->count();
        // Récupérer le nombre total d'annonces
        $totalAnnonces = Annonce::count();

        // Récupérer le nombre total de dons
        $totalDons = Don::count();

        // Récupérer le nombre d'annonces actives
        $annoncesActives = Annonce::where('etat', 'actif')->count();

        // Récupérer le nombre d'annonces inactives
        $annoncesInactives = Annonce::where('etat', 'inactif')->count();

        // Récupérer le nombre d'annonces fermées
        $annoncesFermees = Annonce::where('etat', 'fermé')->count();

        // Récupérer le nombre de dons effectués ce mois
        $donsMoisEnCours = Don::whereMonth('created_at', date('m'))
                                ->whereYear('created_at', date('Y'))
                                ->count();

        // Créer une liste des mois pour les graphiques
        $mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

        // Récupérer les annonces et dons par mois avec les noms des mois
        $annoncesParMois = Annonce::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
                                  ->groupBy('mois')
                                  ->pluck('total', 'mois')
                                  ->toArray();

        $donsParMois = Don::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
                          ->groupBy('mois')
                          ->pluck('total', 'mois')
                          ->toArray();

        // Compléter les mois manquants avec des zéros
        $annoncesParMois = array_replace(array_fill(1, 12, 0), $annoncesParMois);
        $donsParMois = array_replace(array_fill(1, 12, 0), $donsParMois);

        // Réordonner les mois pour correspondre à l'ordre correct
        $annoncesParMois = array_values($annoncesParMois);
        $donsParMois = array_values($donsParMois);

        return view('admin', compact(
            'totalAnnonces', 'totalDons', 'annoncesActives', 'annoncesInactives', 'annoncesFermees',
            'donsMoisEnCours', 'annoncesParMois', 'donsParMois', 'mois', 'usersLastWeek', 'usersLastMonth'
        ));
    }
}