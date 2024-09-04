@extends('layouts.layout')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">DonVital</a></li>
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
            </div>
            <!-- Fin de la visualisation -->
        </div>
    </div>
</div>



<!-- Vendor js -->
<script src="{{ asset('js/vendor.min.js') }}"></script>




<!-- Third Party js-->
<script src="{{asset('libs/peity/jquery.peity.min.js')}}"></script>
<script src="{{asset('libs/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('libs/jquery-vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('libs/jquery-vectormap/jquery-jvectormap-us-merc-en.js')}}"></script>


<!-- Initialisation des graphiques -->
<script>
    // Convertir les données PHP en JSON pour les utiliser en JS
    var annoncesParMois = JSON.parse(document.getElementById('annoncesDonsChart').getAttribute('annonces_data'));
    var donsParMois = JSON.parse(document.getElementById('annoncesDonsChart').getAttribute('dons_data'));
    var mois = JSON.parse(document.getElementById('annoncesDonsChart').getAttribute('mois'));
    var $annoncesActives = JSON.parse(document.getElementById('annoncesActivesChart').getAttribute('active_data'));
    var $annoncesInactives = JSON.parse(document.getElementById('annoncesActivesChart').getAttribute('inactive_data'));
    var $annoncesFermees = JSON.parse(document.getElementById('annoncesActivesChart').getAttribute('ferme_data'));

    // Graphique des annonces et dons par mois
    var options1 = {
        chart: {
            type: 'bar',
            height: 350
        },
        series: [{
            name: 'Annonces',
            data: annoncesParMois
        }, {
            name: 'Dons',
            data: donsParMois
        }],
        xaxis: {
            categories: mois
        }
    };
    var chart1 = new ApexCharts(document.querySelector("#annoncesDonsChart"), options1);
    chart1.render();

    // Graphique des annonces actives vs inactives
    var options2 = {
        chart: {
            type: 'pie',
            height: 350
        },
        series: [ $annoncesActives ,  $annoncesInactives, $annoncesFermees],
        labels: ['Actifs', 'Inactifs', 'Fermées'],
    };
    var chart2 = new ApexCharts(document.querySelector("#annoncesActivesChart"), options2);
    chart2.render();

    // Récupération des données des utilisateurs connectés
    var usersLastWeek = JSON.parse(document.getElementById('apex-radialbar-2').getAttribute('data_weekUsers'));
    var usersLastMonth = JSON.parse(document.getElementById('apex-radialbar-3').getAttribute('data_monthUsers'));

    // Graphique radial pour les utilisateurs connectés cette semaine
    var radialOptions1 = {
        chart: {
            type: 'radialBar',
            height: 350
        },
        series: [usersLastWeek],
        labels: ['Connexions cette semaine'],
        plotOptions: {
            radialBar: {
                dataLabels: {
                    name: {
                        fontSize: '22px',
                    },
                    value: {
                        fontSize: '16px',
                    }
                }
            }
        }
    };
    var radialChart1 = new ApexCharts(document.querySelector("#apex-radialbar-2"), radialOptions1);
    radialChart1.render();

    // Graphique radial pour les utilisateurs connectés ce mois
    var radialOptions2 = {
        chart: {
            type: 'radialBar',
            height: 350
        },
        series: [usersLastMonth],
        labels: ['Connexions ce mois'],
        plotOptions: {
            radialBar: {
                dataLabels: {
                    name: {
                        fontSize: '22px',
                    },
                    value: {
                        fontSize: '16px',
                    }
                }
            }
        }
    };
    var radialChart2 = new ApexCharts(document.querySelector("#apex-radialbar-3"), radialOptions2);
    radialChart2.render();
</script>

@endsection
