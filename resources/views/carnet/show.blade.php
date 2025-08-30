@extends('layouts.layout')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">DonVital</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.donation-records.index') }}">Carnet de Dons</a></li>
                                <li class="breadcrumb-item active">Détails du don #{{ $donation->id }}</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Détails du Don #{{ $donation->id }}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Informations du Don</h5>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">
                                                    <strong>Donneur:</strong>
                                                    <a href="{{ route('carnet.show', $donation->user_id) }}">
                                                        {{ $donation->user->name }}
                                                    </a>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Centre:</strong> {{ $donation->centre->nom }}
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Date:</strong> {{ $donation->donation_date->format('d/m/Y') }}
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Volume:</strong> {{ $donation->volume_ml }} ml
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Groupe sanguin:</strong>
                                                    <span class="badge badge-danger">{{ $donation->blood_type }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Certificat</h5>
                                            @if($donation->certificate_path)
                                                <div class="text-center">
                                                    <a href="{{ asset('storage/' . $donation->certificate_path) }}"
                                                       target="_blank" class="btn btn-primary mb-3">
                                                        <i class="uil-file-download"></i> Télécharger
                                                    </a>
                                                    <div class="ratio ratio-1x1">
                                                        <iframe src="{{ asset('storage/' . $donation->certificate_path) }}"
                                                                class="border rounded"
                                                                style="width: 100%; height: 200px;">
                                                        </iframe>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-info text-center">
                                                    Aucun certificat associé à ce don
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="card-title">Notes Médicales</h5>
                                    <p>{{ $donation->medical_notes ?? 'Aucune note médicale' }}</p>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="card-title">Historique des Dons</h5>
                                    <canvas id="donationChart" height="100"></canvas>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('admin.donation-records.edit', $donation->id) }}" class="btn btn-warning">
                                    <i class="uil-edit"></i> Modifier
                                </a>
                                <button class="btn btn-danger ml-1" data-toggle="modal" data-target="#deleteDonationModal">
                                    <i class="uil-trash"></i> Supprimer
                                </button>
                                <a href="{{ route('admin.donation-records.index') }}" class="btn btn-secondary ml-1">
                                    <i class="uil-arrow-left"></i> Retour
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container -->
    </div> <!-- content -->
</div>

<!-- Modal Supprimer -->
<div class="modal fade" id="deleteDonationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.donation-records.destroy', $donation->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Supprimer le Don</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce don ?</p>
                    <div class="alert alert-warning">
                        <strong>Attention:</strong> Cette action est irréversible et supprimera définitivement l'enregistrement.
                    </div>
                    <ul>
                        <li><strong>Donneur:</strong> {{ $donation->user->name }}</li>
                        <li><strong>Date:</strong> {{ $donation->donation_date->format('d/m/Y') }}</li>
                        <li><strong>Centre:</strong> {{ $donation->centre->nom }}</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('libs/chart-js/chart.min.js') }}"></script>
<script>
    // Graphique de l'historique des dons
    var ctx = document.getElementById('donationChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($donationHistory->pluck('donation_date_formatted')) !!},
            datasets: [{
                label: 'Volume des dons (ml)',
                data: {!! json_encode($donationHistory->pluck('volume_ml')) !!},
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: 'rgba(220, 53, 69, 1)',
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Volume (ml)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date des dons'
                    }
                }
            }
        }
    });
</script>
@endsection
