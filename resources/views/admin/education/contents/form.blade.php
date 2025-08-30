@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            {{ isset($content) ? 'Modifier le Contenu' : 'Créer un Nouveau Contenu' }}
        </h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ isset($content) ? route('admin.education.contents.update', $content->id) : route('admin.education.contents.store') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($content))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="title">Titre *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $content->title ?? '') }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3" required>{{ old('description', $content->description ?? '') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">Contenu Détail</label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content" name="content" rows="5">{{ old('content', $content->content ?? '') }}</textarea>
                            @error('content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="category">Catégorie *</label>
                            <select class="form-control @error('category') is-invalid @enderror"
                                    id="category" name="category" required>
                                <option value="">Sélectionner...</option>
                                @foreach(['discovery' => 'Découverte', 'preparation' => 'Préparation', 'quiz' => 'Quiz', 'testimonials' => 'Témoignages'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('category', $content->category ?? '') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Type *</label>
                            <select class="form-control @error('type') is-invalid @enderror"
                                    id="type" name="type" required>
                                <option value="">Sélectionner...</option>
                                @foreach(['infographic' => 'Infographie', 'video' => 'Vidéo', 'quiz' => 'Quiz', 'article' => 'Article'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('type', $content->type ?? '') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="difficulty">Difficulté (1-5)</label>
                            <input type="number" min="1" max="5"
                                   class="form-control @error('difficulty') is-invalid @enderror"
                                   id="difficulty" name="difficulty"
                                   value="{{ old('difficulty', $content->difficulty ?? 1) }}">
                            @error('difficulty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="points">Points</label>
                            <input type="number" min="0"
                                   class="form-control @error('points') is-invalid @enderror"
                                   id="points" name="points"
                                   value="{{ old('points', $content->points ?? 10) }}">
                            @error('points')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       id="is_active" name="is_active" value="1"
                                       {{ old('is_active', $content->is_active ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Actif</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="media">Média</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('media') is-invalid @enderror"
                                       id="media" name="media">
                                <label class="custom-file-label" for="media">Choisir un fichier...</label>
                                @error('media')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            @if(isset($content) && $content->media_path)
                                <div class="mt-2">
                                    <small>Fichier actuel:</small><br>
                                    @if($content->type === 'video')
                                        <a href="{{ Storage::url($content->media_path) }}" target="_blank">Voir la vidéo</a>
                                    @elseif($content->type === 'infographic')
                                        <img src="{{ Storage::url($content->media_path) }}" alt="Infographie" style="max-height: 100px;">
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Section Quiz (conditionnelle) -->
                <div id="quizSection" style="display: none;">
                    <hr>
                    <h4>Configuration du Quiz</h4>
                    <div id="quizQuestions">
                        <!-- Les questions seront ajoutées dynamiquement ici -->
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" id="addQuestion">
                        <i class="fas fa-plus"></i> Ajouter une Question
                    </button>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($content) ? 'Mettre à jour' : 'Créer' }}
                    </button>
                    <a href="{{ route('admin.education.contents.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script>
    // Initialiser CKEditor
    CKEDITOR.replace('content');

    // Gestion dynamique du quiz
    document.getElementById('type').addEventListener('change', function() {
        const quizSection = document.getElementById('quizSection');
        quizSection.style.display = this.value === 'quiz' ? 'block' : 'none';
    });

    // Déclencher l'événement au chargement si type=quiz
    if(document.getElementById('type').value === 'quiz') {
        document.getElementById('quizSection').style.display = 'block';
    }

    // Ajouter une question
    document.getElementById('addQuestion').addEventListener('click', function() {
        const questionId = Date.now();
        const quizQuestions = document.getElementById('quizQuestions');

        const questionDiv = document.createElement('div');
        questionDiv.className = 'card mb-3';
        questionDiv.innerHTML = `
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Nouvelle Question</h5>
                <button type="button" class="btn btn-sm btn-danger remove-question">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Question</label>
                    <input type="text" name="quiz[${questionId}][question]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Options (cochez la réponse correcte)</label>
                    <div class="options-container">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="radio" name="quiz[${questionId}][correct]" value="0" required>
                                </div>
                            </div>
                            <input type="text" name="quiz[${questionId}][options][]" class="form-control" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-danger remove-option" type="button">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary add-option">
                        <i class="fas fa-plus"></i> Ajouter une Option
                    </button>
                </div>
            </div>
        `;

        quizQuestions.appendChild(questionDiv);

        // Gestion des événements pour cette question
        questionDiv.querySelector('.add-option').addEventListener('click', addOption);
        questionDiv.querySelector('.remove-question').addEventListener('click', function() {
            questionDiv.remove();
        });
    });

    // Ajouter une option à une question
    function addOption() {
        const optionsContainer = this.closest('.form-group').querySelector('.options-container');
        const questionId = this.closest('.card').querySelector('[name^="quiz"]').name.match(/\[(.*?)\]/)[1];
        const optionIndex = optionsContainer.querySelectorAll('.input-group').length;

        const optionDiv = document.createElement('div');
        optionDiv.className = 'input-group mb-2';
        optionDiv.innerHTML = `
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="radio" name="quiz[${questionId}][correct]" value="${optionIndex}" required>
                </div>
            </div>
            <input type="text" name="quiz[${questionId}][options][]" class="form-control" required>
            <div class="input-group-append">
                <button class="btn btn-outline-danger remove-option" type="button">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        optionsContainer.appendChild(optionDiv);
        optionDiv.querySelector('.remove-option').addEventListener('click', function() {
            optionDiv.remove();
        });
    }

    // Déléguer les événements pour les options existantes (pour l'édition)
    document.addEventListener('click', function(e) {
        if(e.target.classList.contains('add-option')) {
            addOption.call(e.target);
        }
        if(e.target.classList.contains('remove-option')) {
            e.target.closest('.input-group').remove();
        }
    });
</script>
@endsection
