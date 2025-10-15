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
                                <li class="breadcrumb-item active">Campagnes</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Gestion des campagnes</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Messages -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <!-- Bouton Ajouter -->
            <div class="row mb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCampagneModal">
                        Ajouter une nouvelle campagne
                    </button>
                </div>
            </div>

            <!-- Liste des campagnes -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Liste des campagnes</h4>
                            <p class="text-muted font-13 mb-4">
                                Gérez vos campagnes : ajoutez, modifiez ou supprimez-les.
                            </p>
                            <table id="basic-datatable" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Description</th>
                                        <th>Lieu</th>
                                        <th>Centre</th>
                                        <th>Dates</th>
                                        <th>Image</th>
                                        <th>Groupes ciblés</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($campagnes as $campagne)
                                        <tr>

                                            <td>{{ $campagne->titre }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($campagne->description, 50) }}</td>
                                            <td>{{ $campagne->lieu }}</td>
                                            <td>{{ $campagne->centreSante->nom ?? '—' }}</td>
                                            <td>
                                                <strong>{{ \Carbon\Carbon::parse($campagne->date_debut)->format('d/m/Y H:i') }}</strong> <br>
                                                <span class="mdi mdi-arrow-down"></span> <br>
                                                <strong>{{ \Carbon\Carbon::parse($campagne->date_fin)->format('d/m/Y H:i') }}</strong>
                                            </td>
                                            <td>
                                                @if($campagne->image_url)
                                                    <img src="{{($campagne->image_url) }}" alt="Image" style="max-height: 50px;" class="img-thumbnail">
                                                @else
                                                    <div class="text-center">
                                                        <i class="mdi mdi-image-remove" style="font-size: 24px;"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $groupes = is_array($campagne->groupes_cibles)
                                                        ? $campagne->groupes_cibles
                                                        : json_decode($campagne->groupes_cibles, true);
                                                @endphp
                                                @if(!empty($groupes))
                                                    @if(count($groupes) == 8)
                                                        <span class="badge badge-success">Tous les groupes</span>
                                                    @else
                                                        @foreach($groupes as $groupe)
                                                            <span class="badge badge-info">{{ $groupe }}</span>
                                                        @endforeach
                                                    @endif
                                                @else
                                                    <span class="badge badge-light">Aucun</span>
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Boutons regroupés -->
                                                <button class="btn {{ $campagne->is_active ? 'btn-warning' : 'btn-info' }} btn-sm mb-1"
                                                        data-toggle="modal" data-target="#toggleStatusCampagneModal-{{ $campagne->id }}">
                                                    {{ $campagne->is_active ? 'Désactiver' : 'Activer' }}
                                                </button>

                                                <button class="btn btn-success btn-sm mb-1" data-toggle="modal" data-target="#editCampagneModal-{{ $campagne->id }}">
                                                    Modifier
                                                </button>

                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteCampagneModal-{{ $campagne->id }}">
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

        </div>
    </div>
</div>

<!-- Modals -->
@foreach($campagnes as $campagne)

<!-- Modal Activer/Désactiver -->
<div class="modal fade" id="toggleStatusCampagneModal-{{ $campagne->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('campagnes.toggle-status', $campagne->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header {{ $campagne->is_active ? 'bg-warning' : 'bg-info' }} text-white">
                    <h5 class="modal-title">
                        {{ $campagne->is_active ? 'Désactiver la campagne' : 'Activer la campagne' }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>
                        Êtes-vous sûr de vouloir
                        <strong>{{ $campagne->is_active ? 'désactiver' : 'activer' }}</strong>
                        la campagne <strong>{{ $campagne->titre }}</strong> ?
                    </p>
                    @if($campagne->image_url)
                        <div class="text-center mt-3">
                            <img src="{{($campagne->image_url) }}" alt="Image" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    @endif
                    <div class="alert {{ $campagne->is_active ? 'alert-warning' : 'alert-info' }} mt-3">
                        <i class="mdi mdi-alert-circle-outline mr-2"></i>
                        Cette action changera l'état de la campagne immédiatement.
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn {{ $campagne->is_active ? 'btn-warning' : 'btn-info' }}">
                        {{ $campagne->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="editCampagneModal-{{ $campagne->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('campagnes.update', $campagne->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Modifier la campagne</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Titre <span class="text-danger">*</span></label>
                        <input type="text" name="titre" value="{{ $campagne->titre }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ $campagne->description }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lieu <span class="text-danger">*</span></label>
                                <input type="text" name="lieu" value="{{ $campagne->lieu }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Centre de santé <span class="text-danger">*</span></label>
                                <select name="centre_sante_id" class="form-control" required>
                                    @foreach($centres as $centre)
                                        <option value="{{ $centre->id }}" {{ $campagne->centre_sante_id == $centre->id ? 'selected' : '' }}>{{ $centre->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date de début <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="date_debut" value="{{ \Carbon\Carbon::parse($campagne->date_debut)->format('Y-m-d\TH:i') }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date de fin <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="date_fin" value="{{ \Carbon\Carbon::parse($campagne->date_fin)->format('Y-m-d\TH:i') }}" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- Nouveau champ pour les groupes sanguins avec cases à cocher -->
                    <div class="form-group">
                        <label>Groupes sanguins ciblés <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap">
                            @php
                                // Définir les groupes sélectionnés pour cette campagne
                                $groupesCampagne = is_array($campagne->groupes_cibles)
                                    ? $campagne->groupes_cibles
                                    : (json_decode($campagne->groupes_cibles, true) ?? []);
                                $allSelected = count($groupesCampagne) === count($groupesSanguins);
                            @endphp

                            <div class="custom-control custom-checkbox mr-3 mb-2">
                                <input type="checkbox" class="custom-control-input" name="groupes_cibles[]"
                                       id="groupe_tous_{{ $campagne->id }}" value="tous"
                                       {{ $allSelected ? 'checked' : '' }}>
                                <label class="custom-control-label" for="groupe_tous_{{ $campagne->id }}">Tous les groupes</label>
                            </div>

                            @foreach($groupesSanguins as $groupe)
                            <div class="custom-control custom-checkbox mr-3 mb-2">
                                <input type="checkbox" class="custom-control-input groupe-sanguin"
                                       name="groupes_cibles[]" id="groupe_{{ $groupe }}_{{ $campagne->id }}"
                                       value="{{ $groupe }}"
                                       {{ in_array($groupe, $groupesCampagne) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="groupe_{{ $groupe }}_{{ $campagne->id }}">{{ $groupe }}</label>
                            </div>
                            @endforeach
                        </div>
                        <small class="form-text text-muted">Sélectionnez les groupes sanguins ciblés</small>
                    </div>

                    <div class="form-group">
                        <label>Image</label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="customFile-{{ $campagne->id }}">
                            <label class="custom-file-label" for="customFile-{{ $campagne->id }}">Choisir une image</label>
                        </div>
                        @if($campagne->image_url)
                            <div class="mt-3">
                                <img src="{{($campagne->image_url) }}" alt="Image" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="is_active" id="is_active_{{ $campagne->id }}" value="1" {{ $campagne->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active_{{ $campagne->id }}">Campagne active</label>
                        </div>
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
<div class="modal fade" id="deleteCampagneModal-{{ $campagne->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('campagnes.destroy', $campagne->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Supprimer la campagne</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer la campagne <strong>{{ $campagne->titre }}</strong> ?</p>
                    @if($campagne->image_url)
                        <div class="text-center mt-3">
                            <img src="{{($campagne->image_url) }}" alt="Image" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    @endif
                    <div class="alert alert-warning mt-3">
                        <i class="mdi mdi-alert-circle-outline mr-2"></i>Cette action est irréversible et supprimera définitivement la campagne.
                    </div>
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

<!-- Modal Ajouter -->
<div class="modal fade" id="addCampagneModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('campagnes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Ajouter une nouvelle campagne</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Titre <span class="text-danger">*</span></label>
                        <input type="text" name="titre" class="form-control" placeholder="Titre de la campagne" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Description de la campagne (objectifs, informations, etc.)"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lieu <span class="text-danger">*</span></label>
                                <input type="text" name="lieu" class="form-control" placeholder="Lieu de la campagne" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Centre de santé <span class="text-danger">*</span></label>
                                <select name="centre_sante_id" class="form-control" required>
                                    <option value="" disabled selected>Sélectionnez un centre</option>
                                    @foreach($centres as $centre)
                                        <option value="{{ $centre->id }}">{{ $centre->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date de début <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="date_debut" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date de fin <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="date_fin" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- Nouveau champ pour les groupes sanguins avec cases à cocher -->
                    <div class="form-group">
                        <label>Groupes sanguins ciblés <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap">
                            <div class="custom-control custom-checkbox mr-3 mb-2">
                                <input type="checkbox" class="custom-control-input" name="groupes_cibles[]"
                                       id="groupe_tous" value="tous" checked>
                                <label class="custom-control-label" for="groupe_tous">Tous les groupes</label>
                            </div>
                            @foreach($groupesSanguins as $groupe)
                            <div class="custom-control custom-checkbox mr-3 mb-2">
                                <input type="checkbox" class="custom-control-input groupe-sanguin"
                                       name="groupes_cibles[]" id="groupe_{{ $groupe }}"
                                       value="{{ $groupe }}" checked>
                                <label class="custom-control-label" for="groupe_{{ $groupe }}">{{ $groupe }}</label>
                            </div>
                            @endforeach
                        </div>
                        <small class="form-text text-muted">Sélectionnez les groupes sanguins ciblés</small>
                    </div>

                    <div class="form-group">
                        <label>Image</label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choisir une image</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="is_active" id="is_active" value="1" checked>
                            <label class="custom-control-label" for="is_active">Campagne active</label>
                        </div>
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
<script src="{{ asset('libs/datatables/dataTables.buttons.min.js') }}></script>
<script src="{{ asset('libs/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('libs/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('libs/datatables/buttons.flash.min.js') }}"></script>
<script src="{{ asset('libs/datatables/buttons.print.min.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.select.min.js') }}"></script>
<script src="{{ asset('libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('libs/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/pages/datatables.init.js') }}"></script>

<script>
    // Script pour les labels des fichiers
    document.addEventListener('DOMContentLoaded', function() {
        // Pour le modal d'ajout
        document.querySelector('#addCampagneModal .custom-file-input').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });

        // Pour les modals de modification
        document.querySelectorAll('.custom-file-input').forEach(function(input) {
            input.addEventListener('change', function(e) {
                var fileName = e.target.files[0].name;
                var nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            });
        });

        // Gestion de la case "Tous les groupes"
        function setupGroupeSanguinCheckboxes(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            const checkBoxTous = modal.querySelector('input[value="tous"]');
            const checkBoxGroupes = modal.querySelectorAll('.groupe-sanguin');

            if (checkBoxTous) {
                checkBoxTous.addEventListener('change', function() {
                    checkBoxGroupes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                        checkbox.disabled = this.checked;
                    });
                });

                // Désactiver les cases individuelles si "Tous" est coché
                if (checkBoxTous.checked) {
                    checkBoxGroupes.forEach(checkbox => {
                        checkbox.disabled = true;
                    });
                }

                // Gérer la case "Tous" quand on modifie les cases individuelles
                checkBoxGroupes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(checkBoxGroupes).every(cb => cb.checked);
                        const noneChecked = Array.from(checkBoxGroupes).every(cb => !cb.checked);

                        if (allChecked) {
                            checkBoxTous.checked = true;
                            checkBoxGroupes.forEach(cb => cb.disabled = true);
                        } else if (noneChecked) {
                            checkBoxTous.checked = false;
                            checkBoxGroupes.forEach(cb => cb.disabled = false);
                        } else {
                            checkBoxTous.checked = false;
                            checkBoxGroupes.forEach(cb => cb.disabled = false);
                        }
                    });
                });
            }
        }

        // Appliquer à tous les modaux
        setupGroupeSanguinCheckboxes('addCampagneModal');

        @foreach($campagnes as $campagne)
            setupGroupeSanguinCheckboxes('editCampagneModal-{{ $campagne->id }}');
        @endforeach

        // Re-initialiser quand les modaux sont ouverts
        $('.modal').on('shown.bs.modal', function() {
            const modalId = $(this).attr('id');
            setTimeout(() => setupGroupeSanguinCheckboxes(modalId), 100);
        });
    });
</script>

@endsection
