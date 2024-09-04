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
                                <li class="breadcrumb-item active">Publicités</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Publicités</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPubModal">
                        Ajouter une nouvelle publicité
                    </button>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Liste des publicités</h4>
                            <p class="text-muted font-13 mb-4">
                                Vous pouvez gérer les publicités ici en ajoutant, modifiant ou supprimant des publicités.
                            </p>
                            <table id="basic-datatable" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Nom de la publicité</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pubs as $pub)
                                        <tr>
                                            <td>{{ $pub->libelle }}</td>
                                            <td>{{ $pub->contenu }}</td>
                                            <td>
                                                <img src="{{ asset('storage/'.$pub->image) }}" alt="{{ $pub->libelle }}" width="100">
                                            </td>
                                            <td>
                                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editPubModal-{{ $pub->id }}">Modifier</button>
                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deletePubModal-{{ $pub->id }}">Supprimer</button>
                                            </td>
                                        </tr>
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

<!-- Modals for Edit and Delete -->
@foreach($pubs as $pub)
<!-- Modal pour modifier la publicité -->
<div class="modal fade" id="editPubModal-{{ $pub->id }}" tabindex="-1" role="dialog" aria-labelledby="editPubModalLabel-{{ $pub->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPubModalLabel-{{ $pub->id }}">Modifier la publicité</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pub.update', $pub->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="libelle-{{ $pub->id }}">Nom de la publicité</label>
                        <input type="text" class="form-control" id="libelle-{{ $pub->id }}" name="libelle" value="{{ $pub->libelle }}" required>
                    </div>
                    <div class="form-group">
                        <label for="contenu-{{ $pub->id }}">Description</label>
                        <textarea class="form-control" id="contenu-{{ $pub->id }}" name="contenu" rows="3" required>{{ $pub->contenu }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="image-{{ $pub->id }}">Image</label>
                        <input type="file" class="form-control" id="image-{{ $pub->id }}" name="image">
                        <small class="text-muted">Laisser vide pour garder l'image actuelle.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour supprimer la publicité -->
<div class="modal fade" id="deletePubModal-{{ $pub->id }}" tabindex="-1" role="dialog" aria-labelledby="deletePubModalLabel-{{ $pub->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePubModalLabel-{{ $pub->id }}">Supprimer la publicité</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pub.destroy', $pub->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette publicité ?</p>
                    <p><strong>{{ $pub->libelle }}</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Modal pour ajouter une publicité -->
<div class="modal fade" id="addPubModal" tabindex="-1" role="dialog" aria-labelledby="addPubModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPubModalLabel">Ajouter une nouvelle publicité</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pub.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="libelle">Nom de la publicité</label>
                        <input type="text" class="form-control" id="libelle" name="libelle" required>
                    </div>
                    <div class="form-group">
                        <label for="contenu">Description</label>
                        <textarea class="form-control" id="contenu" name="contenu" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" id="image" name="image" required>
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