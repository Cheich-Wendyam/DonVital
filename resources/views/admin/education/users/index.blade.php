@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Progrès des Utilisateurs</h1>
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-download fa-sm text-white-50"></i> Exporter
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#">Excel</a>
                <a class="dropdown-item" href="#">PDF</a>
                <a class="dropdown-item" href="#">CSV</a>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Utilisateurs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Contenus Complétés</th>
                            <th>Points</th>
                            <th>Niveau</th>
                            <th>Dernière Activité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->completed_contents_count }} / {{ $totalContents }}</td>
                            <td>{{ $user->points }}</td>
                            <td>
                                <span class="badge badge-info">Niveau {{ $user->education_level }}</span>
                            </td>
                            <td>{{ $user->last_education_activity ? $user->last_education_activity->diffForHumans() : 'Jamais' }}</td>
                            <td>
                                <a href="{{ route('admin.education.users.show', $user->id) }}"
                                   class="btn btn-sm btn-primary" title="Détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.education.users.progress', $user->id) }}"
                                   class="btn btn-sm btn-success" title="Progrès">
                                    <i class="fas fa-chart-line"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            "order": [[4, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
            }
        });
    });
</script>
@endsection
