@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h4>Créer une nouvelle campagne</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Erreur(s) :</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('campagnes.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="titre">Titre de la campagne</label>
            <input type="text" name="titre" class="form-control" placeholder="Ex : Campagne Juin Université" value="{{ old('titre') }}" required>
        </div>

        <div class="form-group mt-2">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Détail sur l’événement">{{ old('description') }}</textarea>
        </div>

        <div class="form-group mt-2">
            <label for="lieu">Lieu</label>
            <input type="text" name="lieu" class="form-control" placeholder="Lieu de la campagne" value="{{ old('lieu') }}" required>
        </div>

        <div class="form-group mt-2">
            <label for="date_debut">Date et heure de début</label>
            <input type="datetime-local" name="date_debut" class="form-control" value="{{ old('date_debut') }}" required>
        </div>

        <div class="form-group mt-2">
            <label for="date_fin">Date et heure de fin</label>
            <input type="datetime-local" name="date_fin" class="form-control" value="{{ old('date_fin') }}" required>
        </div>

        <div class="form-group mt-2">
            <label for="groupes_cibles">Groupes sanguins ciblés (séparés par des virgules)</label>
            <input type="text" name="groupes_cibles" class="form-control" placeholder="Ex : O+, A-, AB+" value="{{ old('groupes_cibles') }}">
        </div>

        <div class="form-group mt-2">
            <label for="centre_id">Centre de santé</label>
            <select name="centre_id" class="form-control" required>
                <option value="">-- Sélectionnez un centre --</option>
                @foreach($centres as $centre)
                    <option value="{{ $centre->id }}" {{ old('centre_id') == $centre->id ? 'selected' : '' }}>
                        {{ $centre->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Enregistrer la campagne</button>
            <a href="{{ route('campagnes.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
