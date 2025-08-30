@extends('layouts.layout')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <!-- Titre -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">
                            Messages - {{ $conversation->name ?? 'Conversation ID #' . $conversation->id }}
                        </h4>
                        <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary mt-2">⬅ Retour aux conversations</a>
                    </div>
                </div>
            </div>

            <!-- Liste des messages -->
            <div class="row mt-3">
                <div class="col-12">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Liste des messages</h4>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Envoyé par</th>
                                        <th>Contenu</th>
                                        <th>Type</th>
                                        <th>Lu ?</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($conversation->messages as $message)
                                        <tr>
                                            <td>{{ $message->sender->name ?? 'Utilisateur supprimé' }}</td>
                                            <td>
                                                @if ($message->type === 'text')
                                                    {{ $message->content }}
                                                @elseif ($message->type === 'image')
                                                    <img src="{{ asset('storage/' . $message->content) }}" alt="Image" width="100">
                                                @elseif ($message->type === 'audio')
                                                    <audio controls src="{{ asset('storage/' . $message->content) }}"></audio>
                                                @elseif ($message->type === 'file')
                                                    <a href="{{ asset('storage/' . $message->content) }}" target="_blank">📄 Voir le fichier</a>
                                                @else
                                                    <em>Type inconnu</em>
                                                @endif
                                            </td>
                                            <td>{{ ucfirst($message->type) }}</td>
                                            <td>{{ $message->is_read ? '✅ Oui' : '❌ Non' }}</td>
                                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Aucun message trouvé</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            {{-- Pagination si tu en ajoutes plus tard --}}
                            {{-- {{ $conversation->messages->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
