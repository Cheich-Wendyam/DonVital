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
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                                <li class="breadcrumb-item active">Centre de Sante</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Gestion des Centres de Sante</h4>
                    </div>
                </div>
            </div>

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

            <!-- Add Centre de Sante Button -->
            <div class="row mb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCentreSanteModal">
                        Ajouter un nouveau centre de sante
                    </button>
                </div>
            </div>

            <!-- Centres de Sante Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Liste des Centres de Santé</h4>
                            <p class="text-muted font-13 mb-4">
                                Vous pouvez gérer les Centres de Santé ici en ajoutant, modifiant ou supprimant des centres de santé.
                            </p>

                            <table id="basic-datatable" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Nom du Centre de Santé</th>
                                        <th>Localisation</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($centres as $centre)
                                        <tr>
                                            <td>{{ $centre->nom }}</td>
                                            <td>{{ $centre->localisation }}</td>
                                            <td>{{ $centre->description }}</td>
                                            <td>
                                                @if ($centre->image)
                                                    <img src="{{ asset('storage/' . $centre->image) }}" alt="{{ $centre->nom }}" width="100">
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Edit Button -->
                                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editCentreSanteModal-{{ $centre->id }}">Modifier</button>

                                                <!-- Delete Button -->
                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteCentreSanteModal-{{ $centre->id }}">Supprimer</button>
                                            </td>
                                        </tr>

                                        <!-- Edit Centre Modal -->
                                        <div class="modal fade" id="editCentreSanteModal-{{ $centre->id }}" tabindex="-1" role="dialog" aria-labelledby="editCentreSanteModalLabel-{{ $centre->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editCentreSanteModalLabel-{{ $centre->id }}">Modifier le Centre de Santé</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('centre_sante.update', $centre->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="centre-name-{{ $centre->id }}">Nom du Centre de Santé</label>
                                                                <input type="text" class="form-control" id="centre-name-{{ $centre->id }}" name="nom" value="{{ old('nom', $centre->nom) }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="centre-description-{{ $centre->id }}">Description du Centre de Santé</label>
                                                                <textarea class="form-control" id="centre-description-{{ $centre->id }}" name="description">{{ old('description', $centre->description) }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="centre-localisation-{{ $centre->id }}">Localisation du Centre de Santé</label>
                                                                <input type="text" class="form-control" id="centre-localisation-{{ $centre->id }}" name="localisation" value="{{ old('localisation', $centre->localisation) }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="centre-image-{{ $centre->id }}">Image du Centre de Santé</label>
                                                                <input type="file" class="form-control" id="centre-image-{{ $centre->id }}" name="image">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="centre-latitude-{{ $centre->id }}">Latitude du Centre de Santé</label>
                                                                <input type="text" class="form-control" id="centre-latitude-{{ $centre->id }}" name="latitude" value="{{ old('latitude', $centre->latitude) }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="centre-longitude-{{ $centre->id }}">Longitude du Centre de Santé</label>
                                                                <input type="text" class="form-control" id="centre-longitude-{{ $centre->id }}" name="longitude" value="{{ old('longitude', $centre->longitude) }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Delete Centre Modal -->
                                        <div class="modal fade" id="deleteCentreSanteModal-{{ $centre->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteCentreSanteModalLabel-{{ $centre->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteCentreSanteModalLabel-{{ $centre->id }}">Confirmer la suppression</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer le centre de santé <strong>{{ $centre->nom }}</strong> ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('centre_sante.destroy', $centre->id) }}" method="POST" style="display:inline;">
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

<!-- Add Centre Modal -->
<div class="modal fade" id="addCentreSanteModal" tabindex="-1" role="dialog" aria-labelledby="addCentreSanteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCentreSanteModalLabel">Ajouter un Centre de Santé</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('centre_sante.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="centre-name">Nom du Centre de Santé</label>
                        <input type="text" class="form-control" id="centre-name" name="nom" placeholder="Nom du Centre de Santé" value="{{ old('nom') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="centre-description">Description du Centre de Santé</label>
                        <textarea class="form-control" id="centre-description" name="description" placeholder="Description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="centre-localisation">Localisation du Centre de Santé</label>
                        <input type="text" class="form-control" id="centre-localisation" name="localisation" placeholder="Localisation" value="{{ old('localisation') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="centre-image">Image du Centre de Santé</label>
                        <input type="file" class="form-control" id="centre-image" name="image">
                    </div>
                    <div class="form-group">
                        <label for="centre-latitude">Latitude du Centre de Santé</label>
                        <input type="text" class="form-control" id="centre-latitude" name="latitude" value="{{ old('latitude') }}">
                    </div>
                    <div class="form-group">
                        <label for="centre-longitude">Longitude du Centre de Santé</label>
                        <input type="text" class="form-control" id="centre-longitude" name="longitude" value="{{ old('longitude') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
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

<script>
    $(document).ready(function() {
        $('#permissions-datatable').DataTable();
    });
</script>
@endsection
