@extends('layouts.layout')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <!-- Titre -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Conversations</h4>
                    </div>
                </div>
            </div>

            <!-- Message de succès -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Message d'erreur -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <!-- Bouton ajouter -->
            <div class="row mb-3">
                <div class="col-12">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addConversationModal">
                        Nouvelle conversation
                    </button>
                </div>
            </div>

            <!-- Tableau des conversations -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Liste des conversations</h4>
                            <table class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Groupe ?</th>
                                        <th>Participants</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conversations as $conversation)
                                        <tr>
                                            <td>{{ $conversation->name ?? 'N/A' }}</td>
                                            <td>{{ $conversation->is_group ? 'Oui' : 'Non' }}</td>
                                            <td>
                                                @foreach ($conversation->participants as $participant)
                                                    <span class="badge badge-info">{{ $participant->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.conversations.messages', $conversation->id) }}" class="btn btn-secondary btn-sm">Messages</a>
                                                <form action="{{ route('admin.conversations.destroy', $conversation->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette conversation ?')">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal ajout -->
           <!-- Bouton pour ouvrir le premier modal -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addConversationModal">
            ➕ Nouvelle conversation
        </button>

        <!-- Modal 1 : Ajouter conversation -->
        <div class="modal fade" id="addConversationModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form action="{{ route('admin.conversations.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Créer une nouvelle conversation</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nom (optionnel)</label>
                                <input type="text" name="name" class="form-control" placeholder="Nom de la conversation">
                            </div>
                            <div class="form-group">
                                <label>Type</label>
                                <select name="is_group" class="form-control">
                                    <option value="0">Privée</option>
                                    <option value="1">Groupe</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Participants</label>
                                <select name="user_ids[]" class="form-control" multiple required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                <small>Ctrl+clic pour plusieurs</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Créer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

<!-- Bouton pour ouvrir le deuxième modal -->
<button class="btn btn-success mb-3" data-toggle="modal" data-target="#createConversationModal">
    ➕ Créer une conversation entre deux utilisateurs
</button>

<!-- Modal 2 : Créer conversation entre deux utilisateurs -->
<div class="modal fade" id="createConversationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.conversations.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créer une conversation privée</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="is_group" value="0">
                    <div class="form-group">
                        <label>Utilisateur 1</label>
                        <select class="form-control" name="user1" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Utilisateur 2</label>
                        <select class="form-control" name="user2" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </div>
        </form>
    </div>
</div>


     </div>
    </div>
</div>
@endsection
