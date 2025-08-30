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
                                <li class="breadcrumb-item active">Modifier un don</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Modifier le Don #{{ $donation->id }}</h4>
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
                            <form action="{{ route('admin.donation-records.update', $donation->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Donneur</label>
                                            <select name="user_id" class="form-control" required>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ $donation->user_id == $user->id ? 'selected' : '' }}>
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
                                                @foreach($centres as $centre)
                                                    <option value="{{ $centre->id }}"
                                                        {{ $donation->centre_sante_id == $centre->id ? 'selected' : '' }}>
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
                                            <input type="date" name="donation_date" class="form-control"
                                                   value="{{ $donation->donation_date->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Volume (ml)</label>
                                            <input type="number" name="volume_ml" class="form-control"
                                                   min="200" max="500" value="{{ $donation->volume_ml }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Groupe sanguin</label>
                                            <select name="blood_type" class="form-control" required>
                                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                                    <option value="{{ $type }}"
                                                        {{ $donation->blood_type == $type ? 'selected' : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Notes médicales</label>
                                    <textarea name="medical_notes" class="form-control" rows="3">{{ $donation->medical_notes }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Certificat de don</label>
                                    <div class="custom-file">
                                        <input type="file" name="certificate" class="custom-file-input" id="editCertificate">
                                        <label class="custom-file-label" for="editCertificate">Choisir un fichier</label>
                                    </div>
                                    @if($donation->certificate_path)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $donation->certificate_path) }}"
                                               target="_blank" class="btn btn-sm btn-outline-primary">
                                                Voir le certificat actuel
                                            </a>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox"
                                                       name="remove_certificate" id="removeCert">
                                                <label class="form-check-label" for="removeCert">
                                                    Supprimer le certificat
                                                </label>
                                            </div>
                                        </div>
                                    @endif
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
    document.getElementById('editCertificate').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endsection
