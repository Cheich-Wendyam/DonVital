@extends('layouts.layout')

@section('content')
<div class="container py-4">
    <!-- Page title -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ $content->title }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Description</h5>
                    <p class="card-text">{{ $content->description }}</p>

                    @if($content->media_path || $content->media_url)
                        <div class="mt-4">
                            <h5>Média</h5>
                            @if($content->type === 'video')
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="{{ $content->media_url }}" allowfullscreen></iframe>
                                </div>
                            @else
                                <img src="{{ $content->media_path ? Storage::url($content->media_path) : $content->media_url }}"
                                     class="img-fluid rounded">
                            @endif
                        </div>
                    @endif

                    @if($content->type === 'quiz')
                        <div class="mt-4">
                            <h5>Questions du Quiz</h5>
                            @php
                                $quizData = json_decode($content->quiz_data, true);
                            @endphp

                            @if(is_array($quizData) && isset($quizData['questions']))
                                @foreach($quizData['questions'] as $index => $question)
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <strong>Question #{{ $index + 1 }}:</strong> {{ $question['question'] ?? 'Question non définie' }}
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            @foreach($question['options'] ?? [] as $option)
                                                <li class="list-group-item">
                                                    {{ $option['text'] ?? 'Option manquante' }}
                                                    @if(!empty($option['correct']))
                                                        <span class="badge badge-success float-right">Correct</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-danger">Aucune question trouvée ou format du quiz invalide.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statistiques</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Catégorie
                            <span class="badge badge-primary">{{ ucfirst($content->category) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Type
                            <span class="badge badge-info">{{ ucfirst($content->type) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Difficulté
                            <span>
                                @for($i = 0; $i < $content->difficulty; $i++)
                                    <i class="mdi mdi-star text-warning"></i>
                                @endfor
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Points
                            <span class="badge badge-success">{{ $content->points }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Complétions
                            <span>{{ $content->completions_count }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Taux de réussite
                            <span>{{ number_format($content->success_rate, 1) }}%</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Créé le
                            <span>{{ $content->created_at->format('d/m/Y') }}</span>
                        </li>
                    </ul>

                    <div class="mt-3 d-flex justify-content-between">
                        <a href="{{ route('education.edit', $content->id) }}" class="btn btn-primary">
                            <i class="mdi mdi-pencil mr-1"></i> Modifier
                        </a>
                        <a href="{{ route('education.index') }}" class="btn btn-light">
                            <i class="mdi mdi-arrow-left mr-1"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des complétions -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Historique des Complétions</h5>
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Date</th>
                                    <th>Score</th>
                                    <th>Points gagnés</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($completions as $completion)
                                    <tr>
                                        <td>{{ $completion->user->name }}</td>
                                        <td>{{ $completion->completed_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $completion->score }}%</td>
                                        <td>{{ $completion->points_earned }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Aucune complétion enregistrée pour ce contenu.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $completions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
