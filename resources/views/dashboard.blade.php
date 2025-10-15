@extends('layouts.layout')

@section('content')
<!-- Loader -->
<div id="dashboard-loader" style="position:fixed;top:0;left:0;width:100%;height:100%;background:white;z-index:9999;display:flex;justify-content:center;align-items:center;">
    <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
        <span class="visually-hidden">Chargement...</span>
    </div>
</div>


<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin') }}">DonVital</a></li>
                                <li class="breadcrumb-item active">Admin</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Tableau de bord</h4>
                    </div>
                </div>
            </div>

            <!-- Statistiques des annonces et des dons -->
            <div class="row">
                <div class="col-xl-3">
                    <div class="card-box">
                        <h4 class="mt-0 font-16">Total des Annonces</h4>
                        <h2 class="text-primary my-4 text-center">{{ $totalAnnonces }}</h2>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card-box">
                        <h4 class="mt-0 font-16">Total des Dons</h4>
                        <h2 class="text-primary my-4 text-center">{{ $totalDons }}</h2>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card-box">
                        <h4 class="mt-0 font-16">Annonces Actives</h4>
                        <h2 class="text-primary my-4 text-center">{{ $annoncesActives }}</h2>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card-box">
                        <h4 class="mt-0 font-16">Annonces inactives</h4>
                        <h2 class="text-primary my-4 text-center">{{ $annoncesInactives }}</h2>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card-box">
                        <h4 class="mt-0 font-16">Annonces Fermées</h4>
                        <h2 class="text-primary my-4 text-center">{{ $annoncesFermees }}</h2>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card-box">
                        <h4 class="mt-0 font-16">Dons du mois</h4>
                        <h2 class="text-primary my-4 text-center">{{ $donsMoisEnCours }}</h2>
                    </div>
                </div>
                 <!-- NOUVELLES STATISTIQUES DES CAMPAGNES -->
    <div class="col-xl-3">
        <div class="card-box">
            <h4 class="mt-0 font-16">Total des Campagnes</h4>
            <h2 class="text-primary my-4 text-center">{{ $totalCampagnes }}</h2>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card-box">
            <h4 class="mt-0 font-16">Campagnes en cours</h4>
            <h2 class="text-success my-4 text-center">{{ $campagnesEnCours }}</h2>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card-box">
            <h4 class="mt-0 font-16">Campagnes à venir</h4>
            <h2 class="text-warning my-4 text-center">{{ $campagnesAVenir }}</h2>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card-box">
            <h4 class="mt-0 font-16">Campagnes terminées</h4>
            <h2 class="text-info my-4 text-center">{{ $campagnesTerminees }}</h2>
        </div>
    </div>
            </div>
            <!-- Fin des statistiques -->

            <!-- Visualisation avec des graphiques -->
            <div class="row">
                <!-- Graphique des annonces et dons par mois -->
                <div class="col-xl-6">
                    <div class="card-box">
                        <h4 class="header-title">Annonces et Dons par Mois</h4>
                        <div id="annoncesDonsChart" dons_data='@json($donsParMois)' annonces_data='@json($annoncesParMois)' mois='@json($mois)'></div>
                    </div>
                </div>

                <!-- Graphique des annonces actives vs inactives -->
                <div class="col-xl-6">
                    <div class="card-box">
                        <h4 class="header-title">Annonces Actives, Inactives et Fermées</h4>
                        <div id="annoncesActivesChart" active_data='@json($annoncesActives)' inactive_data='@json($annoncesInactives)' ferme_data='@json($annoncesFermees)'></div>
                    </div>
                </div>
            </div>

            <!-- Radial Bar Charts -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card-box">
                        <h4 class="header-title">Utilisateurs connectés la semaine</h4>
                        <div id="apex-radialbar-2" data_weekUsers='@json($usersLastWeek)'></div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card-box">
                        <h4 class="header-title">Utilisateurs connectés le mois</h4>
                        <div id="apex-radialbar-3" data_monthUsers='@json($usersLastMonth)'></div>
                    </div>
                </div>

                <div class="row">
    <div class="col-xl-6">
        <div class="card-box">
            <h4 class="header-title">Statut des Campagnes</h4>
            <div id="campagnesChart"
                 total_campagnes="{{ $totalCampagnes }}"
                 en_cours="{{ $campagnesEnCours }}"
                 a_venir="{{ $campagnesAVenir }}"
                 terminees="{{ $campagnesTerminees }}">
            </div>
        </div>
    </div>
</div>
            </div>
            <!-- Fin de la visualisation -->
        </div>
    </div>
</div>


<!-- CSS personnalisé -->
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<!-- Vendor js -->
<script src="{{ asset('js/vendor.min.js') }}"></script>

<!-- Librairies -->
<script src="{{ asset('libs/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('libs/jquery-vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('libs/jquery-vectormap/jquery-jvectormap-us-merc-en.js') }}"></script>

<!-- JS personnalisé -->
<script src="{{ asset('js/dashboard.js') }}"></script>


@endsection
