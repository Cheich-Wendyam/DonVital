@extends('layouts.layout')

@section('content')
<div class="container">
    <h2 class="mb-4">Participants à la campagne : <strong>{{ $campagne->titre }}</strong></h2>
    <p><strong>Lieu :</strong> {{ $campagne->lieu }} <br>
       <strong>Début :</strong> {{ $campagne->date_debut }} |
       <strong>Fin :</strong> {{ $campagne->date_fin }}</p>

    @if($campagne->participants->count() > 0)
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Date d’inscription</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campagne->participants as $index => $participation)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $participation->user->name }}</td>
                        <td>{{ $participation->user->email }}</td>
                        <td>{{ $participation->user->phone ?? 'N/A' }}</td>
                        <td>{{ $participation->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-warning mt-4">
            Aucun participant inscrit pour cette campagne.
        </div>
    @endif
</div>
@endsection
