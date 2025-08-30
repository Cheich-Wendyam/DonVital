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
                                <li class="breadcrumb-item active">Récompenses</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Récompenses</h4>
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

            <div class="row mb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRewardModal">
                        Ajouter une nouvelle récompense
                    </button>
                </div>
            </div>

            <!-- Liste des récompenses -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Liste des récompenses</h4>
                            <p class="text-muted font-13 mb-4">
                                Gérez les récompenses attribuées aux donneurs : ajoutez, modifiez ou supprimez-les.
                            </p>
                            <table id="basic-datatable" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Description</th>
                                        <th>Coût (pts)</th>
                                        <th>Image</th>
                                        <th>Niveau requis</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rewards as $reward)
                                        <tr>
                                            <td>{{ $reward->name }}</td>
                                            <td>{{ $reward->description }}</td>
                                            <td>{{ $reward->cost }}</td>
                                            <td>
                                                @if($reward->image_url)
                                                    <img src="{{ asset('storage/' . $reward->image_url) }}" alt="Image" style="max-height: 50px;" class="img-thumbnail">
                                                @else
                                                    Pas d'image
                                                @endif
                                            </td>
                                            <td>{{ $reward->required_level }}</td>
                                            <td>
                                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editRewardModal-{{ $reward->id }}">
                                                    Modifier
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteRewardModal-{{ $reward->id }}">
                                                    Supprimer
                                                </button>
                                                <button class="btn {{ $reward->is_active ? 'btn-warning' : 'btn-info' }} btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#toggleRewardModal-{{ $reward->id }}">
                                                    {{ $reward->is_active ? 'Désactiver' : 'Activer' }}
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

        </div>
    </div>
</div>

<!-- Modals pour chaque récompense -->
@foreach($rewards as $reward)

<!-- Modal Modifier -->
<div class="modal fade" id="editRewardModal-{{ $reward->id }}" tabindex="-1" role="dialog" aria-labelledby="editRewardLabel-{{ $reward->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('rewards.update', $reward->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la récompense</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="name" value="{{ $reward->name }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" required>{{ $reward->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Coût</label>
                        <input type="number" name="cost" value="{{ $reward->cost }}" class="form-control" required>
                    </div>
                    <!-- Champ d'upload d'image -->
                    <div class="form-group">
                        <label>Image</label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="customFile-{{ $reward->id }}">
                            <label class="custom-file-label" for="customFile-{{ $reward->id }}">Choisir un fichier</label>
                        </div>
                        @if($reward->image_url)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $reward->image_url) }}" alt="Image actuelle" style="max-height: 100px;" class="img-thumbnail">
                                <p class="text-muted small mt-1">Image actuelle</p>
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="editIsActive-{{ $reward->id }}"
                                   name="is_active" value="1" {{ $reward->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="editIsActive-{{ $reward->id }}">Récompense active</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Niveau requis</label>
                        <input type="number" name="required_level" value="{{ $reward->required_level }}"
                               class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Supprimer -->
<div class="modal fade" id="deleteRewardModal-{{ $reward->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteRewardLabel-{{ $reward->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('rewards.destroy', $reward->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Supprimer la récompense</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer la récompense <strong>{{ $reward->name }}</strong> ?</p>
                    @if($reward->image_url)
                        <div class="mt-3">
                            <img src="{{ asset('storage/' . $reward->image_url) }}" alt="Image" style="max-height: 100px;" class="img-thumbnail">
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Activer/Désactiver -->
<div class="modal fade" id="toggleRewardModal-{{ $reward->id }}" tabindex="-1" role="dialog" aria-labelledby="toggleRewardLabel-{{ $reward->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('rewards.toggle-status', $reward->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $reward->is_active ? 'Désactiver la récompense' : 'Activer la récompense' }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>
                        Êtes-vous sûr de vouloir
                        <strong>{{ $reward->is_active ? 'désactiver' : 'activer' }}</strong>
                        la récompense <strong>{{ $reward->name }}</strong> ?
                    </p>
                    @if($reward->image_url)
                        <div class="mt-3">
                            <img src="{{ asset('storage/' . $reward->image_url) }}" alt="Image" style="max-height: 100px;" class="img-thumbnail">
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn {{ $reward->is_active ? 'btn-warning' : 'btn-info' }}">
                        {{ $reward->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


@endforeach

<!-- Modal Ajouter une récompense -->
<div class="modal fade" id="addRewardModal" tabindex="-1" role="dialog" aria-labelledby="addRewardLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('rewards.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une nouvelle récompense</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Coût</label>
                        <input type="number" name="cost" class="form-control" required>
                    </div>
                    <!-- Champ d'upload d'image -->
                    <div class="form-group">
                        <label>Image</label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choisir un fichier</label>
                        </div>
                        <small class="form-text text-muted">Formats acceptés: JPG, PNG, GIF (max 2MB)</small>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="addIsActive" name="is_active" value="1" checked>
                            <label class="custom-control-label" for="addIsActive">Récompense active</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Niveau requis</label>
                        <input type="number" name="required_level" class="form-control" value="1" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/vendor.min.js') }}"></script>
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
<script src="{{ asset('js/pages/datatables.init.js') }}"></script>

<!-- Script pour afficher le nom du fichier dans l'input -->
<script>
    // Pour le modal d'ajout
    document.getElementById('customFile').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    // Pour les modals d'édition
    @foreach($rewards as $reward)
        document.getElementById('customFile-{{ $reward->id }}').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    @endforeach
</script>

@endsection
