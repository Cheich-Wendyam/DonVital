@extends('layouts.layout')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <!-- Titre de la page -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Tableau de Bord Éducatif</h4>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card widget-box-one border border-primary">
                        <div class="card-body">
                            <div class="float-right avatar-lg rounded-circle bg-soft-primary">
                                <i class="mdi mdi-book-open-variant font-22 widget-icon"></i>
                            </div>
                            <div class="wigdet-one-content">
                                <p class="m-0 text-uppercase font-weight-bold text-muted">Total Contenus</p>
                                <h2 class="my-2"><span>{{ $stats['total_contents'] }}</span></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card widget-box-one border border-success">
                        <div class="card-body">
                            <div class="float-right avatar-lg rounded-circle bg-soft-success">
                                <i class="mdi mdi-comment-question-outline font-22 widget-icon"></i>
                            </div>
                            <div class="wigdet-one-content">
                                <p class="m-0 text-uppercase font-weight-bold text-muted">Quiz</p>
                                <h2 class="my-2"><span>{{ $stats['total_quizzes'] }}</span></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card widget-box-one border border-info">
                        <div class="card-body">
                            <div class="float-right avatar-lg rounded-circle bg-soft-info">
                                <i class="mdi mdi-check-all font-22 widget-icon"></i>
                            </div>
                            <div class="wigdet-one-content">
                                <p class="m-0 text-uppercase font-weight-bold text-muted">Complétions</p>
                                <h2 class="my-2"><span>{{ $stats['total_completions'] }}</span></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card widget-box-one border border-warning">
                        <div class="card-body">
                            <div class="float-right avatar-lg rounded-circle bg-soft-warning">
                                <i class="mdi mdi-account-multiple font-22 widget-icon"></i>
                            </div>
                            <div class="wigdet-one-content">
                                <p class="m-0 text-uppercase font-weight-bold text-muted">Utilisateurs Actifs</p>
                                <h2 class="my-2"><span>{{ $stats['active_users'] }}</span></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3">Activité Récente (7 derniers jours)</h4>
                            <div>
                                <canvas id="activityChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3">Répartition des Contenus</h4>
                            <div>
                                <canvas id="categoryChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container-fluid -->
    </div> <!-- content -->
</div> <!-- content-page -->
@endsection

@push('scripts')
<script src="{{ asset('libs/chart.js/Chart.bundle.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique d'activité
        new Chart(document.getElementById('activityChart'), {
            type: 'bar',
            data: {
                labels: @json($activity['dates']),
                datasets: [{
                    label: 'Complétions',
                    data: @json($activity['completions']),
                    backgroundColor: '#1abc9c',
                    borderColor: '#1abc9c',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Graphique de répartition
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: @json($categories['names']),
                datasets: [{
                    data: @json($categories['counts']),
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });
</script>
@endpush
