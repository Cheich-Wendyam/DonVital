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
                                <li class="breadcrumb-item active">Ajouter un don</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Ajouter un don au carnet</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.donation-records.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Donneur</label>
                                            <select name="user_id" class="form-control" required>
                                                <option value="">Sélectionnez un donneur</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Centre de santé</label>
                                            <select name="centre_sante_id" class="form-control" required>
                                                <option value="">Sélectionnez un centre</option>
                                                @foreach($centres as $centre)
                                                    <option value="{{ $centre->id }}" {{ old('centre_sante_id') == $centre->id ? 'selected' : '' }}>
                                                        {{ $centre->nom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Date du don</label>
                                            <input type="date" name="donation_date" class="form-control" value="{{ old('donation_date') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Volume (ml)</label>
                                            <input type="number" name="volume_ml" class="form-control" min="200" max="500" value="{{ old('volume_ml', 450) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Groupe sanguin</label>
                                            <select name="blood_type" class="form-control" required>
                                                <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ old('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Notes médicales</label>
                                    <textarea name="medical_notes" class="form-control" rows="3">{{ old('medical_notes') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Certificat de don</label>
                                    <div class="custom-file">
                                        <input type="file" name="certificate" class="custom-file-input" id="certificateFile">
                                        <label class="custom-file-label" for="certificateFile">Choisir un fichier</label>
                                    </div>
                                    <small class="form-text text-muted">Formats acceptés: PDF, JPG, PNG (max 2MB)</small>
                                </div>

                                <div class="form-group text-right mb-0">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <a href="{{ route('admin.donation-records.index') }}" class="btn btn-secondary ml-1">Annuler</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container -->
    </div> <!-- content -->
</div>

<script>
    // Afficher le nom du fichier sélectionné
    document.getElementById('certificateFile').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endsection
