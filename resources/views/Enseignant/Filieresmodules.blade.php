{{-- resources/views/Enseignant/enseignant.blade.php --}}

@include('layouts.haut', [
    'titre' => 'UniGest — Espace Enseignant'
])
{{-- Bienvenue --}}
<section class="welcome">
    <h1>Bienvenue sur votre espace Enseignant</h1>
    <p>
        Gérez vos modules, vos étudiants, vos notes et vos supports de cours.
    </p>
</section>
<br>
{{-- Mes Etudiants --}}
    <div class="card span-2">
        <div class="card-head">
            <h2>Les modules pour chaque filière</h2>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Filières</th>
                        <th>Modules</th>
                        <th>Semestre</th>
                        <th>Année universitaire</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($filieres as $groupe)
                        @foreach ($groupe as $index => $fil)
                            <tr>
                                @if ($index === 0)
                                    <td rowspan="{{ $groupe->count() }}">
                                        <b>{{ $fil->nom }}</b>
                                    </td>
                                @endif
                                <td>
                                    <a href="{{ route('Enseignant.Modulesetudiants', 
                                    ['id' => $fil->IDMODULE,
                                        'annee' => $fil->IDANNEE
                                    ]) }}">
                                        {{ $fil->intitule }}
                                    </a>
                                </td>
                                <td>
                                    <b>{{ $fil->semestre }}</b>
                                </td>
                                <td>
                                    <b>{{ $fil->année_universetaire }}</b>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="2">
                                Aucune filière ou module trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>