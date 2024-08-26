@extends('layouts.layout')
@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">DonVital</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Annonce</a></li>
                                <li class="breadcrumb-item active">Liste des annonces</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Gestion des annonces</h4>
                    </div>
                </div>
            </div>

            <!-- Button d'ajout -->
            <div class="row mb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAnnonceModal">Ajouter une annonce</button>
                </div>
            </div>

            <!-- message de success -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Liste des annonces -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Liste des annonces</h4>
                            <p class="text-muted font-13 mb-4">
                                Vous pouvez gérer les annonces ici en ajoutant, modifiant ou supprimant des annonces.
                            </p>

                            <table id="basic-datatable" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Description</th>
                                        <th>Groupe Sanguin</th>
                                        <th>Raison</th>
                                        <th>État</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($annonces as $annonce)
                                        <tr>
                                            <td>{{ $annonce->titre }}</td>
                                            <td>{{ $annonce->description }}</td>
                                            <td>{{ $annonce->TypeSang }}</td>
                                            <td>{{ $annonce->raison }}</td>
                                            <td>
                                                @if($annonce->etat == 'actif')
                                                    <span class="badge badge-success">Actif</span>
                                                @else
                                                    <span class="badge badge-warning">Inactif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#approveAnnonceModal-{{ $annonce->id }}">Approuver</button>
                                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editAnnonceModal-{{ $annonce->id }}">Modifier</button>
                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteAnnonceModal-{{ $annonce->id }}">Supprimer</button>
                                            </td>
                                        </tr>

                                        <!-- Modal pour Approuver une annonce -->
                                        <div class="modal fade" id="approveAnnonceModal-{{ $annonce->id }}" tabindex="-1" role="dialog" aria-labelledby="approveAnnonceModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="approveAnnonceModalLabel">Approuver l'annonce</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir approuver cette annonce ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('annonces.approve', $annonce->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success">Approuver</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal pour Modifier une annonce -->
                                        <div class="modal fade" id="editAnnonceModal-{{ $annonce->id }}" tabindex="-1" role="dialog" aria-labelledby="editAnnonceModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editAnnonceModalLabel">Modifier une annonce</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('annonces.update', $annonce->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="titre">Titre</label>
                                                                <input type="text" class="form-control" id="titre" name="titre" value="{{ $annonce->titre }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="description">Description</label>
                                                                <textarea class="form-control" id="description" name="description" required>{{ $annonce->description }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="groupesanguin">Groupe Sanguin</label>
                                                                <input type="text" class="form-control" id="groupesanguin" name="groupesanguin" value="{{ $annonce->groupesanguin }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="raison">Raison</label>
                                                                <input type="text" class="form-control" id="raison" name="raison" value="{{ $annonce->raison }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal pour Supprimer une annonce -->
                                        <div class="modal fade" id="deleteAnnonceModal-{{ $annonce->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteAnnonceModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteAnnonceModalLabel">Supprimer une annonce</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer cette annonce ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('annonces.destroy', $annonce->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div> <!-- end row-->
        </div> <!-- container -->
    </div> <!-- content -->
</div> <!-- content-page -->

<!-- Modal pour Ajouter une nouvelle annonce -->
<div class="modal fade" id="addAnnonceModal" tabindex="-1" role="dialog" aria-labelledby="addAnnonceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAnnonceModalLabel">Ajouter une nouvelle annonce</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('annonces.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="titre">Titre</label>
                        <input type="text" class="form-control" id="titre" name="titre" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="groupesanguin">Groupe Sanguin</label>
                        <input type="text" class="form-control" id="groupesanguin" name="groupesanguin" required>
                    </div>
                    <div class="form-group">
                        <label for="raison">Raison</label>
                        <input type="text" class="form-control" id="raison" name="raison" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Vendor JS -->
<script src="{{ asset('js/vendor.min.js') }}"></script>

<!-- Datatables JS -->
<script src="{{ asset('libs/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('libs/datatables/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('libs/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('libs/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('libs/datatables/buttons.flash.min.js') }}"></script>
<script src="{{ asset('libs/datatables/buttons.print.min.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.select.min.js') }}"></script>
<script src="{{ asset('libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('libs/pdfmake/vfs_fonts.js') }}"></script>

<!-- Datatables init -->
<script src="{{ asset('js/pages/datatables.init.js') }}"></script>

@endsection
