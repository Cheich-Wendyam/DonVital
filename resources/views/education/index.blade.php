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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">DonVital</a></li>
                                <li class="breadcrumb-item active">Contenus Éducatifs</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Gestion des Contenus Éducatifs</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('education.create') }}" class="btn btn-success">
                        <i class="uil-plus"></i> Nouveau Contenu
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Liste des Contenus</h4>
                            <p class="text-muted font-13 mb-4">
                                Gérez les contenus éducatifs disponibles pour les utilisateurs.
                            </p>

                            <table id="contentsTable" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Catégorie</th>
                                        <th>Type</th>
                                        <th>Difficulté</th>
                                        <th>Points</th>
                                        <th>Complétions</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contents as $content)
                                    <tr>
                                        <td>{{ $content->title }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ ucfirst($content->category) }}</span>
                                        </td>
                                        <td>{{ ucfirst($content->type) }}</td>
                                        <td>
                                            @for($i = 0; $i < $content->difficulty; $i++)
                                                <i class="mdi mdi-star text-warning"></i>
                                            @endfor
                                        </td>
                                        <td>{{ $content->points }}</td>
                                        <td>{{ $content->completions_count }}</td>
                                        <td>
                                            <a href="{{ route('education.show', $content->id) }}"
                                               class="btn btn-sm btn-info text-white me-1" title="Voir">
                                                Voir
                                            </a>
                                            <a href="{{ route('education.edit', $content->id) }}"
                                               class="btn btn-sm btn-warning text-white me-1" title="Modifier">
                                                Modifier
                                            </a>
                                            <button class="btn btn-sm btn-danger text-white" title="Supprimer"
                                                    data-toggle="modal" data-target="#deleteContentModal-{{ $content->id }}">
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

        </div> <!-- container -->
    </div> <!-- content -->
</div>

<!-- Modals de suppression -->
@foreach($contents as $content)
<div class="modal fade" id="deleteContentModal-{{ $content->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteContentLabel-{{ $content->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('education.destroy', $content->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Supprimer le contenu</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce contenu éducatif ?</p>
                    <div class="alert alert-warning">
                        <strong>Attention :</strong> Cette action est irréversible.
                    </div>
                    <ul>
                        <li><strong>Titre :</strong> {{ $content->title }}</li>
                        <li><strong>Catégorie :</strong> {{ ucfirst($content->category) }}</li>
                        <li><strong>Type :</strong> {{ ucfirst($content->type) }}</li>
                    </ul>
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

<!-- Scripts DataTables -->
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('libs/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('libs/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('libs/datatables/responsive.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#contentsTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
            },
            order: [[0, 'asc']],
            responsive: true
        });
    });
</script>

@endsection
