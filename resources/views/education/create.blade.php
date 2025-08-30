@extends('layouts.layout')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Créer un nouveau contenu éducatif</h4>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card">
                <div class="card-body px-4">
                    <form method="POST" action="{{ route('education.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Partie gauche -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Titre *</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description *</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Type *</label>
                                            <select class="form-control" id="type" name="type" required>
                                                <option value="">Sélectionner...</option>
                                                @foreach(['infographic', 'video', 'quiz', 'article', 'checklist', 'testimony'] as $type)
                                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
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
                                                    <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="difficulty">Difficulté (1-5)</label>
                                            <input type="number" class="form-control" id="difficulty" name="difficulty" min="1" max="5" value="1">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="points">Points</label>
                                            <input type="number" class="form-control" id="points" name="points" value="10">
                                        </div>
                                    </div>
                                </div>

                                <div id="quiz-section" style="display: none;">
                                    <div class="form-group">
                                        <label for="quiz_data">Données du Quiz (JSON)</label>
                                        <textarea class="form-control" id="quiz_data" name="quiz_data" rows="6"></textarea>
                                        <small class="text-muted">
                                            Format: {"questions": [{"question": "Texte", "options": [{"text": "Option", "correct": true/false}]}]}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Partie droite -->
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body">
                                        <h5 class="card-title">Média</h5>

                                        <div class="form-group">
                                            <label for="media">Télécharger un média</label>
                                            <input type="file" class="form-control-file" id="media" name="media">
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="media_url">Ou URL du média</label>
                                            <input type="text" class="form-control" id="media_url" name="media_url">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Créer le contenu
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

        toggleQuizSection(); // au chargement
        typeSelect.addEventListener('change', toggleQuizSection);
    });
</script>
@endpush
@endsection
