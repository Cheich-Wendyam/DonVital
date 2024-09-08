<!-- resources/views/annonces/attente.blade.php -->

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
                                <li class="breadcrumb-item active">Annonces en attente</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Gestion des annonces en attente</h4>
                    </div>
                </div>
            </div>

            <!-- Liste des annonces inactives -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Liste des annonces en attente</h4>
                            <p class="text-muted font-13 mb-4">
                                Vous pouvez approuver, voir les détails, supprimer ou rejeter les annonces en attente.
                            </p>

                            <table id="basic-datatable" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Description</th>
                                        <th>Groupe Sanguin</th>
                                        <th>Raison</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($annonces as $annonce)
                                        <tr>
                                            <td>{{ $annonce->titre }}</td>
                                            <td>{{ $annonce->description }}</td>
                                            <td>{{ $annonce->groupesanguin }}</td>
                                            <td>{{ $annonce->raison }}</td>
                                            <td>
                                                <!-- Bouton Voir Détail -->
                                                <a href="{{ route('annonces.show', $annonce->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Voir détail
                                                </a>

                                                <!-- Bouton Approuver -->
                                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#approveAnnonceModal-{{ $annonce->id }}">
                                                    <i class="fas fa-check"></i> Approuver
                                                </button>

                                                <!-- Bouton Rejeter -->
                                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#rejectAnnonceModal-{{ $annonce->id }}">
                                                    <i class="fas fa-times"></i> Rejeter
                                                </button>

                                                <!-- Bouton Supprimer -->
                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteAnnonceModal-{{ $annonce->id }}">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
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

                                        <!-- Modal pour Rejeter une annonce -->
                                        <div class="modal fade" id="rejectAnnonceModal-{{ $annonce->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectAnnonceModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="rejectAnnonceModalLabel">Rejeter l'annonce</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir rejeter cette annonce ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('annonces.reject', $annonce->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-warning">Rejeter</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal pour Supprimer une annonce -->
                                        <div class="modal fade" id="deleteAnnonceModal-{{ $annonce->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteAnnonceModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteAnnonceModalLabel">Supprimer l'annonce</h5>
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

<script>
    $(document).ready(function() {
        $('#permissions-datatable').DataTable();
    });
</script>
@endsection
