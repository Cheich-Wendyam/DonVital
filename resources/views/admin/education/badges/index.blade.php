@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Badges</h1>
        <a href="{{ route('admin.education.badges.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau Badge
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Badges</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($badges as $badge)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="{{ $badge->icon }} fa-3x text-{{ $badge->color }}"></i>
                            </div>
                            <h5 class="card-title">{{ $badge->name }}</h5>
                            <p class="card-text">{{ $badge->description }}</p>
                            <p class="text-muted">Débloqué par: {{ $badge->users_count }} utilisateurs</p>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="btn-group btn-group-sm w-100">
                                <a href="{{ route('admin.education.badges.edit', $badge->id) }}"
                                   class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.education.badges.destroy', $badge->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Supprimer ce badge?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <a href="{{ route('admin.education.badges.show', $badge->id) }}"
                                   class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
