@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h4>Créer une nouvelle campagne</h4>

    {{-- Affichage des erreurs --}}
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

    {{-- Formulaire --}}
    <form action="{{ route('campagnes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Titre --}}
        <div class="form-group">
            <label for="titre">Titre de la campagne</label>
            <input
                type="text"
                name="titre"
                class="form-control"
                placeholder="Ex : Campagne Juin Université"
                value="{{ old('titre') }}"
                required
            >
        </div>

        {{-- Description --}}
        <div class="form-group mt-2">
            <label for="description">Description</label>
            <textarea
                name="description"
                class="form-control"
                rows="3"
                placeholder="Détail sur l’événement">{{ old('description') }}</textarea>
        </div>

        {{-- Lieu --}}
        <div class="form-group mt-2">
            <label for="lieu">Lieu</label>
            <input
                type="text"
                name="lieu"
                class="form-control"
                placeholder="Lieu de la campagne"
                value="{{ old('lieu') }}"
                required
            >
        </div>

        {{-- Date et heure de début --}}
        <div class="form-group mt-2">
            <label for="date_debut">Date et heure de début</label>
            <input
                type="datetime-local"
                name="date_debut"
                class="form-control"
                value="{{ old('date_debut') }}"
                required
            >
        </div>

        {{-- Date et heure de fin --}}
        <div class="form-group mt-2">
            <label for="date_fin">Date et heure de fin</label>
            <input
                type="datetime-local"
                name="date_fin"
                class="form-control"
                value="{{ old('date_fin') }}"
                required
            >
        </div>

        {{-- Groupes sanguins --}}
        <div class="form-group mt-2">
            <label for="groupes_cibles">Groupes sanguins ciblés (séparés par des virgules)</label>
            <input
                type="text"
                name="groupes_cibles"
                class="form-control"
                placeholder="Ex : O+, A-, AB+"
                value="{{ old('groupes_cibles') }}"
            >
        </div>

        {{-- Centre de santé --}}
        <div class="form-group mt-2">
            <label for="centre_sante_id">Centre de santé</label>
            <select name="centre_sante_id" class="form-control" required>
                <option value="">-- Sélectionnez un centre --</option>
                @foreach($centres as $centre)
                    <option
                        value="{{ $centre->id }}"
                        {{ old('centre_sante_id') == $centre->id ? 'selected' : '' }}
                    >
                        {{ $centre->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Image optionnelle --}}
        <div class="form-group mt-2">
            <label for="image">Image (optionnelle)</label>
            <input
                type="file"
                name="image"
                class="form-control"
            >
        </div>

        {{-- Activer/Désactiver la campagne --}}
        <div class="form-check mt-2">
            <input
                type="checkbox"
                name="is_active"
                class="form-check-input"
                id="is_active"
                value="1"
                {{ old('is_active', 1) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="is_active">Activer cette campagne</label>
        </div>

        {{-- Boutons --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Enregistrer la campagne</button>
            <a href="{{ route('campagnes.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
