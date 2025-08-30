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
                                <li class="breadcrumb-item active">Carnet de Dons</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Gestion du Carnet de Dons</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('admin.donation-records.create') }}" class="btn btn-primary">
                        <i class="uil-plus"></i> Ajouter un don
                    </a>

                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Historique des Dons</h4>
                            <p class="text-muted font-13 mb-4">
                                Consultez et gérez tous les dons enregistrés dans le carnet numérique.
                            </p>



                            <table id="donationsTable" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Donneur</th>
                                        <th>Centre</th>
                                        <th>Date</th>
                                        <th>Volume</th>
                                        <th>Groupe</th>
                                        <th>Certificat</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($donations as $donation)
                                    <tr>
                                        <td>{{ $donation->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.donation-records.show', $donation->user_id) }}">
                                                {{ $donation->user->name }}
                                            </a>
                                        </td>
                                        <td>{{ $donation->centre->nom }}</td>
                                        <td>{{ $donation->donation_date->format('d/m/Y') }}</td>
                                        <td>{{ $donation->volume_ml }} ml</td>
                                        <td>
                                            <span class="badge badge-danger">{{ $donation->blood_type }}</span>
                                        </td>
                                        <td>
                                            @if($donation->certificate_path)
                                                <a href="{{ asset('storage/' . $donation->certificate_path) }}"
                                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="uil-file-download"></i> Voir
                                                </a>
                                            @else
                                                <span class="text-muted">Aucun</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.donation-records.show', $donation->id) }}"
                                            class="btn btn-sm btn-info text-white me-1" title="Voir">
                                                Voir
                                            </a>
                                            <a href="{{ route('admin.donation-records.edit', $donation->id) }}"
                                            class="btn btn-sm btn-warning text-white me-1" title="Modifier">
                                                Modifier
                                            </a>
                                            <button class="btn btn-sm btn-danger text-white" title="Supprimer"
                                                    data-toggle="modal" data-target="#deleteDonationModal-{{ $donation->id }}">
                                                Supprimer
                                            </button>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container -->
    </div> <!-- content -->
</div>

<!-- Modals pour la suppression -->
@foreach($donations as $donation)
<div class="modal fade" id="deleteDonationModal-{{ $donation->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteDonationLabel-{{ $donation->id }}" aria-hidden="true">
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
                        <li><strong>Centre:</strong> {{ $donation->centre->name }}</li>
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
@endforeach

<!-- Scripts -->
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('libs/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('libs/datatables/responsive.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        var table = $('#donationsTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
            },
            order: [[3, 'desc']],
            responsive: true
        });

        // Filtre de recherche
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Filtre par donneur
        $('#userFilter').on('change', function() {
            table.column(1).search(this.value).draw();
        });

        // Filtre par centre
        $('#centreFilter').on('change', function() {
            table.column(2).search(this.value).draw();
        });

        // Réinitialiser les filtres
        $('#resetFilters').on('click', function() {
            $('#searchInput').val('');
            $('#userFilter').val('');
            $('#centreFilter').val('');
            table.search('').columns().search('').draw();
        });
    });
</script>
@endsection
