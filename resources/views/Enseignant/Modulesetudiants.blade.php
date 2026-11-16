{{-- resources/views/Enseignant/enseignant.blade.php --}}

@include('layouts.haut', [
    'titre' => 'UniGest — Espace Enseignant'
])

<h2>Liste des étudiants</h2>
@if($modules->isNotEmpty())
    <h3>
        Année universitaire : {{ $modules->first()->annee }}
    </h3>
    <p>
        Nombre d'étudiants : {{ $modules->count() }}
    </p>
    <br>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>CNE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($modules as $etudiant)
                <tr>
                    <td>{{ $etudiant->nom }}</td>
                    <td>{{ $etudiant->prenom }}</td>
                    <td>{{ $etudiant->cne }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>Aucun étudiant trouvé pour cette année universitaire.</p>
@endif
        </div>
    </div>