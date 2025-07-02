@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h4>Modifier la campagne : {{ $campagne->titre }}</h4>

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

    <form action="{{ route('campagnes.update', $campagne->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="titre">Titre de la campagne</label>
            <input type="text" name="titre" class="form-control" value="{{ old('titre', $campagne->titre) }}" required>
        </div>

        <div class="form-group mt-2">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $campagne->description) }}</textarea>
        </div>

        <div class="form-group mt-2">
            <label for="lieu">Lieu</label>
            <input type="text" name="lieu" class="form-control" value="{{ old('lieu', $campagne->lieu) }}" required>
        </div>

        <div class="form-group mt-2">
            <label for="date_debut">Date et heure de début</label>
            <input type="datetime-local" name="date_debut" class="form-control" value="{{ old('date_debut', \Carbon\Carbon::parse($campagne->date_debut)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="form-group mt-2">
            <label for="date_fin">Date et heure de fin</label>
            <input type="datetime-local" name="date_fin" class="form-control" value="{{ old('date_fin', \Carbon\Carbon::parse($campagne->date_fin)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="form-group mt-2">
            <label for="groupes_cibles">Groupes sanguins ciblés</label>
            <input type="text" name="groupes_cibles" class="form-control" value="{{ old('groupes_cibles', $campagne->groupes_cibles) }}">
        </div>

        <div class="form-group mt-2">
            <label for="centre_id">Centre de santé</label>
            <select name="centre_id" class="form-control" required>
                <option value="">-- Sélectionnez un centre --</option>
                @foreach($centres as $centre)
                    <option value="{{ $centre->id }}" {{ old('centre_id', $campagne->centre_id) == $centre->id ? 'selected' : '' }}>
                        {{ $centre->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
            <a href="{{ route('campagnes.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
