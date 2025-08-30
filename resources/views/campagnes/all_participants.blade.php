@extends('layouts.layout')

@section('content')
<div class="container py-4">
    <!-- 🔹 Titre principal -->
    <h2 class="mb-5 text-center fw-bold py-3 px-4 rounded shadow-sm"
        style="background: linear-gradient(90deg, #0d6efd, #0dcaf0); color: white;">
        <i class="bi bi-calendar-check"></i> Liste des campagnes et leurs participants
    </h2>

    @foreach($campagnes as $campagne)
        <div class="card shadow-sm mb-5 border-0">
            <!-- 🔹 En-tête de la campagne -->
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">
                        <i class="bi bi-megaphone-fill"></i> {{ $campagne->titre }}
                    </h5>
                    <small>
                        <i class="bi bi-geo-alt-fill"></i> {{ $campagne->lieu }}
                    </small>
                </div>
                <div class="text-end">
                    <span class="badge bg-light text-dark me-1">
                        <i class="bi bi-calendar-event"></i>
                        Du {{ \Carbon\Carbon::parse($campagne->date_debut)->format('d/m/Y') }}
                    </span>
                    <span class="badge bg-light text-dark">
                        <i class="bi bi-calendar-event-fill"></i>
                        au {{ \Carbon\Carbon::parse($campagne->date_fin)->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            <div class="card-body">
                @if($campagne->participants->count() > 0)
                    <!-- 🔹 Bouton Exporter PDF -->
                    <div class="mb-3 text-end">
                        <a href="{{ route('campagnes.participants.pdf', $campagne->id) }}"
                           class="btn btn-danger btn-sm shadow">
                            <i class="bi bi-file-earmark-pdf-fill"></i> Exporter PDF
                        </a>
                    </div>

                    <!-- 🔹 Tableau des participants -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th><i class="bi bi-person-fill"></i> Nom</th>
                                    <th><i class="bi bi-envelope-fill"></i> Email</th>
                                    <th><i class="bi bi-telephone-fill"></i> Téléphone</th>
                                    <th><i class="bi bi-clock-fill"></i> Date d’inscription</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($campagne->participants as $index => $participation)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $participation->user->name }}</td>
                                        <td>{{ $participation->user->email }}</td>
                                        <td>{{ $participation->user->telephone ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $participation->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- 🔹 Message si aucun participant -->
                    <div class="alert alert-warning text-center">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Aucun participant inscrit à cette campagne.
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
