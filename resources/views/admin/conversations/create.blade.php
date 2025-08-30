@extends('layouts.layout')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Créer une nouvelle conversation</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.conversations.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="user_one_id">Premier utilisateur</label>
                    <select name="user_one_id" id="user_one_id" class="form-control" required>
                        <option value="">-- Sélectionnez un utilisateur --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="user_two_id">Deuxième utilisateur</label>
                    <select name="user_two_id" id="user_two_id" class="form-control" required>
                        <option value="">-- Sélectionnez un utilisateur --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Créer la conversation</button>
                <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection
