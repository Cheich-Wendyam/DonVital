@extends('layouts.layout')

@section('content')
<div class="container">
    <h4>Liste des campagnes</h4>
    <a href="{{ route('campagnes.create') }}" class="btn btn-primary mb-2">Ajouter une campagne</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Lieu</th>
                <th>Dates</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($campagnes as $campagne)
                <tr>
                    <td>{{ $campagne->titre }}</td>
                    <td>{{ $campagne->lieu }}</td>
                    <td>{{ $campagne->date_debut }} à {{ $campagne->date_fin }}</td>
                    <td>
                        <a href="{{ route('campagnes.edit', $campagne->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('campagnes.destroy', $campagne->id) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $campagnes->links() }}
</div>
@endsection
