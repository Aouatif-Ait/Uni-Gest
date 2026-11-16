
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

{{-- Statistiques --}}
<section class="grid stats" aria-label="Chiffres clés">

    @foreach ([
        [
            'label' => 'Mes cours',
            'value' => $stats['cours'] ?? 0,
            'icon' => 'M4 4h16v16H4z M8 8h8 M8 12h8 M8 16h5'
        ],
        [
            'label' => 'Mes sujets PFE',
            'value' => $stats['pfe'] ?? 0,
            'icon' => 'M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z'
        ],
        [
            'label' => 'Documents déposés',
            'value' => $stats['documents'] ?? 0,
            'icon' => 'M6 2h8l5 5v15H6z M14 2v6h5 M9 13h7 M9 17h7'
        ],
    ] as $stat)
        <article class="stat">
            <div>
                <span>{{ $stat['label'] }}</span>
                <strong>
                    {{ number_format($stat['value'], 0, ',', ' ') }}
                </strong>
            </div>
            <span class="ico">
                <svg viewBox="0 0 24 24">
                    <path d="{{ $stat['icon'] }}"/>
                </svg>
            </span>
        </article>
    @endforeach
</section>

{{-- Contenu principal --}}
<section class="grid cols">

    {{-- Mes cours --}}
    <div class="card span-2">
        <div class="card-head">
            <h2>Mes cours</h2>
            <a href="{{ url('/enseignant/cours') }}" class="link">
                Voir tout
            </a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Titre du cours</th>
                        <th>Type</th>
                        <th>Date de publication</th>
                        <th>Document</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cours5 as $c)
                        <tr>
                            <td>
                                <b>{{ $c->titre }}</b>
                            </td>
                            <td>
                                {{ $c->type_cours }}
                            </td>
                            <td>
                                {{ $c->date_pub ?? 'Non définie' }}
                            </td>
                            <td>
                                @if ($c->chemin_fichier)
                                    <span>Document disponible</span>
                                @else
                                    <span>Aucun document</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                Aucun cours trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Statistiques des étudiants par filière --}}
    <section class="card">
        <h2>Étudiants encadrés par filière</h2>
        <div style="max-width: 650px; margin: 20px auto;">
            <canvas
                id="etudiantsParFiliere"
                data-labels="{{ json_encode($statsParFiliere->pluck('nom')->values()) }}"
                data-valeurs="{{ json_encode($statsParFiliere->pluck('total')->values()) }}"
            ></canvas>
        </div>
    </section>

    {{-- Mes PFE encadrés --}}
    <div class="card span-2">
        <div class="card-head">
            <h2>Mes PFE encadrés</h2>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID PFE</th>
                        <th>Sujet PFE</th>
                        <th>Date Affectation</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pfes as $pfe)
                        <tr>
                            <td>{{ $pfe->IDPFE }}</td>
                            <td>
                                {{ $pfe->sujet->titre }}
                            </td>
                            <td>
                                {{ $pfe->date_affec }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                Aucun PFE encadré pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

        {{-- Accès rapides --}}
    <div class="card">
        <div class="card-head">
            <h2>Accès rapides</h2>
        </div>

        <ul class="list">

            {{-- Modules --}}
            <li>
                <span class="tile">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 4h16v16H4z"/>
                    </svg>
                </span>
                <div class="txt">
                    <b>
                        <a href="{{ route('Enseignant.modules') }}">
                            Mes modules
                        </a>
                    </b>
                    <small>Consulter mes modules</small>
                </div>
            </li>

            {{-- Notes --}}
            <li>
                <span class="tile">
                    <svg viewBox="0 0 24 24">
                        <path d="M5 3h14v18H5z"/>
                    </svg>
                </span>
                <div class="txt">
                    <b>
                        <a href="{{ route('Enseignant.FilieresmodulesNotes') }}">
                            Gestion des notes
                        </a>
                    </b>
                    <small>Saisir et modifier les notes</small>
                </div>
            </li>

            {{-- Etudiants --}}
            <li>
                <span class="tile">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8"/>
                    </svg>
                </span>
                <div class="txt">
                    <b>
                        <a href="{{ route('Enseignant.Filieresmodules') }}">
                            Mes étudiants
                        </a>
                    </b>
                    <small>Consulter les étudiants</small>
                </div>
            </li>

            {{-- Supports de cours --}}
            <li>
                <span class="tile">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 4h16v16H4z"/>
                    </svg>
                </span>
                <div class="txt">
                    <b>
                        <a href="{{ url('/enseignant/cours') }}">
                            Supports de cours
                        </a>
                    </b>
                    <small>Déposer des documents</small>
                </div>
            </li>
        </ul>
    </div>
</section>

    {{-- Mes étudiants --}}
<div class="card span-2">

    <div class="card-head">
        <h2>Mes étudiants encadés</h2>

        <span>
            {{ $etudiants->count() }} étudiant(s)
        </span>
    </div>

    <div class="table-wrap">

        <table>
            <thead>
                <tr>
                    <th>Nom complet</th>
                    <th>CNE</th>
                    <th>Filière</th>
                    <th>Année universitaire</th>
                    <th>Sujet PFE</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($etudiants as $e)
                    <tr>
                        {{-- Nom complet --}}
                        <td>
                            <b>
                                {{ trim(($e->utilisateur->nom ?? '') . ' ' . ($e->utilisateur->prenom ?? '')) }}
                            </b>
                        </td>

                        {{-- CNE --}}
                        <td>{{ $e->cne }}</td>

                        {{-- Filière(s) --}}
                        <td>
                            @forelse ($e->inscriptions as $inscription)
                                <div>
                                    {{ $inscription->filiere->nom ?? 'Non définie' }}
                                </div>
                            @empty
                                Non inscrite
                            @endforelse
                        </td>

                        {{-- Année universitaire --}}
                        <td>
                            @forelse ($e->inscriptions as $inscription)
                                <div>
                                    {{ $inscription->annee->libelle ?? 'Non définie' }}
                                </div>
                            @empty
                                Non définie
                            @endforelse
                        </td>
                        {{-- Sujet PFE --}}
                        <td>
                            {{ $e->pfe->sujet->titre ?? 'Aucun sujet' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            Aucun étudiant encadré pour le moment.
                        </td>
                    </tr>

                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('layouts.bas')
