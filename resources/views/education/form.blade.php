@extends('layouts.layout')

@section('content')
<div class="container-fluid">
    <!-- Page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    {{ isset($content) ? 'Éditer le Contenu' : 'Créer un Nouveau Contenu' }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ isset($content) ? route('education.update', $content->id) : route('education.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($content)) @method('PUT') @endif

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Titre *</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ old('title', $content->title ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description *</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $content->description ?? '') }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Type *</label>
                                            <select class="form-control" id="type" name="type" required>
                                                <option value="">Sélectionner...</option>
                                                @foreach(['infographic', 'video', 'quiz', 'article', 'checklist', 'testimony'] as $type)
                                                    <option value="{{ $type }}"
                                                        {{ old('type', $content->type ?? '') == $type ? 'selected' : '' }}>
                                                        {{ ucfirst($type) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category">Catégorie *</label>
                                            <select class="form-control" id="category" name="category" required>
                                                <option value="">Sélectionner...</option>
                                                @foreach(['discovery', 'preparation', 'quiz', 'testimonials'] as $category)
                                                    <option value="{{ $category }}"
                                                        {{ old('category', $content->category ?? '') == $category ? 'selected' : '' }}>
                                                        {{ ucfirst($category) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="difficulty">Difficulté (1-5)</label>
                                            <input type="number" class="form-control" id="difficulty" name="difficulty"
                                                min="1" max="5"
                                                value="{{ old('difficulty', $content->difficulty ?? 1) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="points">Points</label>
                                            <input type="number" class="form-control" id="points" name="points"
                                                value="{{ old('points', $content->points ?? 10) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Section Quiz -->
                                <div id="quiz-section" class="mt-3" style="{{ ($content->type ?? '') == 'quiz' ? '' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label for="quiz_data">Données du Quiz (JSON)</label>
                                        <textarea class="form-control" id="quiz_data" name="quiz_data" rows="6">{{ old('quiz_data', $content->quiz_data ?? '') }}</textarea>
                                        <small class="text-muted">
                                            Format: {"questions": [{"question": "Texte", "options": [{"text": "Option", "correct": true/false}]}]}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Média</h5>

                                        @if(isset($content) && $content->media_path)
                                            <div class="mb-3 text-center">
                                                <img src="{{ Storage::url($content->media_path) }}" class="img-fluid rounded mb-2" style="max-height: 150px;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="remove_media" name="remove_media">
                                                    <label class="form-check-label" for="remove_media">
                                                        Supprimer le média existant
                                                    </label>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="media">Télécharger un média</label>
                                            <input type="file" class="form-control-file" id="media" name="media">
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="media_url">Ou URL du média</label>
                                            <input type="text" class="form-control" id="media_url" name="media_url"
                                                value="{{ old('media_url', $content->media_url ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        {{ isset($content) ? 'Mettre à jour' : 'Créer le contenu' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const quizSection = document.getElementById('quiz-section');

        function toggleQuizSection() {
            quizSection.style.display = typeSelect.value === 'quiz' ? 'block' : 'none';
        }

        typeSelect.addEventListener('change', toggleQuizSection);
        toggleQuizSection(); // Initial state
    });
</script>
@endpush
@endsection
