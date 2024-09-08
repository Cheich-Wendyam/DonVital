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
                                <li class="breadcrumb-item"><a href="javascript: void(0);">DonVital</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('annonce.index') }}">Annonces</a></li>
                                <li class="breadcrumb-item active">Détail de l'annonce</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Détail de l'annonce</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{ $annonce->titre }}</h4>
                            <p class="text-muted">{{ $annonce->description }}</p>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Groupe Sanguin:</strong> {{ $annonce->TypeSang }}</li>
                                <li class="list-group-item"><strong>Raison:</strong> {{ $annonce->raison }}</li>
                                <li class="list-group-item"><strong>Centre de santé:</strong> {{ $annonce->CentreSante }}</li>
                                <li class="list-group-item">
                                    <strong>État:</strong>
                                    @if($annonce->etat == 'actif')
                                        <span class="badge badge-success">Actif</span>
                                    @elseif($annonce->etat == 'inactif')
                                        <span class="badge badge-warning">Inactif</span>
                                    @elseif($annonce->etat == 'fermé')
                                        <span class="badge badge-danger">Fermé</span>
                                    @endif
                                </li>
                            </ul>
                            <div class="mt-4">
                                <a href="{{ route('annonce.index') }}" class="btn btn-secondary">Retour à la liste</a>
                            </div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div> <!-- end row-->
        </div> <!-- container -->
    </div> <!-- content -->
</div> <!-- content-page -->
@endsection
