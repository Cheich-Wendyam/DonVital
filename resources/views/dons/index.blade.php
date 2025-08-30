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
                                <li class="breadcrumb-item active">Dons</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Gestion des Dons</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <!-- Liste des dons -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Liste des dons</h4>
                            <p class="text-muted font-13 mb-4">
                                Confirmez ou annulez les dons effectués par les utilisateurs.
                            </p>
                            <table id="basic-datatable" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Donneur</th>
                                        <th>Annonce</th>
                                        <th>Date</th>
                                        <th>État</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dons as $don)
                                        <tr>
                                            <td>{{ $don->id }}</td>
                                            <td>{{ $don->user->name ?? 'Inconnu' }}</td>
                                            <td>{{ $don->annonce->titre ?? 'Annonce supprimée' }}</td>
                                            <td>{{ $don->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($don->etat === 'confirmé')
                                                    <span class="badge badge-success">Confirmé</span>
                                                @elseif($don->etat === 'annulé')
                                                    <span class="badge badge-danger">Annulé</span>
                                                @else
                                                    <span class="badge badge-warning">En attente</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @if($don->etat !== 'confirmé')
                                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#confirmDonModal-{{ $don->id }}">
                                                            <i class="fas fa-check"></i> Confirmer
                                                        </button>
                                                    @endif

                                                    @if($don->etat !== 'annulé')
                                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancelDonModal-{{ $don->id }}">
                                                            <i class="fas fa-times"></i> Annuler
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal Confirmer -->
                                        <div class="modal fade" id="confirmDonModal-{{ $don->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDonLabel-{{ $don->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.dons.confirmer', $don->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirmer le don</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Voulez-vous vraiment confirmer le don <strong>#{{ $don->id }}</strong> de <strong>{{ $don->user->name ?? 'Inconnu' }}</strong> pour l'annonce "<em>{{ $don->annonce->titre ?? 'Annonce supprimée' }}</em>" ?</p>
                                                            <p class="text-muted small">Date : {{ $don->created_at->format('d/m/Y H:i') }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-success">Confirmer</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Modal Annuler -->
                                        <div class="modal fade" id="cancelDonModal-{{ $don->id }}" tabindex="-1" role="dialog" aria-labelledby="cancelDonLabel-{{ $don->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.dons.annuler', $don->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Annuler le don</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Voulez-vous vraiment annuler le don <strong>#{{ $don->id }}</strong> de <strong>{{ $don->user->name ?? 'Inconnu' }}</strong> pour l'annonce "<em>{{ $don->annonce->titre ?? 'Annonce supprimée' }}</em>" ?</p>
                                                            <p class="text-muted small">Date : {{ $don->created_at->format('d/m/Y H:i') }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-danger">Annuler</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('libs/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('libs/datatables/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/pages/datatables.init.js') }}"></script>

@endsection
