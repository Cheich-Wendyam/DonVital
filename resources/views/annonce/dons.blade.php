

@extends('layouts.layout')
@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin') }}">DonVital</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('annonce.fermees') }}">Annonces Fermées</a></li>
                                <li class="breadcrumb-item active">Dons Associés</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Dons Associés à l'Annonce : {{ $annonce->titre }}</h4>
                    </div>
                </div>
            </div>

            <!-- Liste des dons associés -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Liste des Dons</h4>
                            <p class="text-muted font-13 mb-4">
                                Voici la liste des dons associés à l'annonce sélectionnée.
                            </p>

                            <table id="basic-datatable" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Nom de l'utilisateur</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Date de Don</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dons as $don)
                                        <tr>
                                            <td>{{ $don->user->name }}</td>
                                            <td>{{ $don->user->email }}</td>
                                            <td>{{ $don->user->phone }}</td>
                                            <td>{{ $don->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Bouton Retour -->
                            <a href="{{ route('annonce.fermees') }}" class="btn btn-secondary mt-3">
                                <i class="fas fa-arrow-left"></i> Retour aux annonces fermées
                            </a>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div> <!-- end row-->
        </div> <!-- container -->
    </div> <!-- content -->
</div> <!-- content-page -->

@endsection
