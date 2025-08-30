@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil Éducatif de {{ $user->name }}</h1>
        <a href="{{ route('admin.education.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistiques</h6>
                </div>
                <div class="card-body text-center">
                    <img class="img-profile rounded-circle mb-3"
                         src="{{ $user->avatar_url }}" width="120" height="120">
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>

                    <div class="mt-4">
                        <div class="mb-3">
                            <h5 class="text-primary">{{ $user->completed_contents_count }}</h5>
                            <small class="text-muted">Contenus Complétés</small>
                        </div>
                        <div class="mb-3">
                            <h5 class="text-success">{{ $user->points }}</h5>
                            <small class="text-muted">Points</small>
                        </div>
                        <div>
                            <h5 class="text-info">Niveau {{ $user->education_level }}</h5>
                            <small class="text-muted">Progrès</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Progrès par Catégorie</h6>
                </div>
                <div class="card-body">
                    @foreach($categories as $category)
                    <h5 class="small font-weight-bold mt-3">{{ $category['name'] }}
                        <span class="float-right">{{ $category['completed'] }}/{{ $category['total'] }} ({{ $category['percentage'] }}%)</span>
                    </h5>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-{{ $category['color'] }}" role="progressbar"
                             style="width: {{ $category['percentage'] }}%"
                             aria-valuenow="{{ $category['percentage'] }}"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dernières Activités</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse($activities as $activity)
                        <div class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $activity->content->title }}</h6>
                                <small>{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ $activity->content->category_name }} • {{ $activity->content->type_name }}</p>
                            <small>Score: {{ $activity->score }}% • Points: +{{ $activity->points_earned }}</small>
                        </div>
                        @empty
                        <div class="list-group-item">
                            Aucune activité récente
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
