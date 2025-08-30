<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Participants - {{ $campagne->titre }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        h2 { text-align: center; }
        .campagne-img { display: block; margin: 10px auto; max-width: 200px; max-height: 150px; }
    </style>
</head>
<body>
    <h2>Participants de la campagne : {{ $campagne->titre }}</h2>
    <p><strong>Lieu :</strong> {{ $campagne->lieu }}</p>
    <p><strong>Période :</strong>
        {{ \Carbon\Carbon::parse($campagne->date_debut)->format('d/m/Y') }}
        - {{ \Carbon\Carbon::parse($campagne->date_fin)->format('d/m/Y') }}
    </p>

    {{-- ✅ Affichage de l’image de la campagne --}}
    @if(isset($imagePath) && file_exists($imagePath))
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents($imagePath)) }}"
             class="campagne-img" alt="Image campagne">
    @endif

    @if($campagne->participants->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Date d’inscription</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campagne->participants as $index => $participation)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $participation->user->name }}</td>
                        <td>{{ $participation->user->email }}</td>
                        <td>{{ $participation->user->telephone ?? 'N/A' }}</td>
                        <td>{{ $participation->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p><em>Aucun participant inscrit.</em></p>
    @endif
</body>
</html>
