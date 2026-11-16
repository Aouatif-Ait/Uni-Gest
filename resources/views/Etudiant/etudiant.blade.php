
{{-- resources/views/Etudiant/etudiant.blade.php --}}

@include('layouts.haut', [
    'titre' => 'UniGest — Espace Étudiant'
])
{{-- Bienvenue --}}
<section class="welcome">
    <h1>Bienvenue sur votre espace Étudiant</h1>
    <p>
        Consultez vos modules, vos cours, vos notes
        et votre emploi du temps.
    </p>
</section>
{{-- Statistiques --}}
<section class="grid stats" aria-label="Chiffres clés">

    @foreach ([
        [
            'label' => 'Mes modules',
            'value' => 6,
            'icon' => 'M4 4h16v16H4z M8 8h8 M8 12h8 M8 16h5'
        ],
        [
            'label' => 'Mes cours',
            'value' => 12,
            'icon' => 'M6 2h8l5 5v15H6z M14 2v6h5 M9 13h7 M9 17h7'
        ],
        [
            'label' => 'Mes notes',
            'value' => 8,
            'icon' => 'M5 3h14v18H5z M8 8h8 M8 12h8 M8 16h5'
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
    {{-- Mes modules --}}
    <div class="card span-2">
        <div class="card-head">
            <h2>Mes modules</h2>
            <a href="{{ url('/etudiant/modules') }}" class="link">
                Voir tout
            </a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Semestre</th>
                        <th>Filière</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Programmation Java</b></td>
                        <td>Semestre 5</td>
                        <td>Informatique Appliquée</td>
                    </tr>
                    <tr>
                        <td><b>Base de données avancée</b></td>
                        <td>Semestre 5</td>
                        <td>Informatique Appliquée</td>
                    </tr>
                    <tr>
                        <td><b>Sciences des données</b></td>
                        <td>Semestre 5</td>
                        <td>Informatique Appliquée</td>
                    </tr>
                    <tr>
                        <td><b>Réseaux informatiques</b></td>
                        <td>Semestre 5</td>
                        <td>Informatique Appliquée</td>
                    </tr>
                    <tr>
                        <td><b>Développement Web</b></td>
                        <td>Semestre 5</td>
                        <td>Informatique Appliquée</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
    {{-- Mes informations --}}
    <div class="card">
        <div class="card-head">
            <h2>Mes informations</h2>
        </div>
        <ul class="list">
            {{-- Nom --}}
            <li>
                <span class="tile">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8 M4 21a8 8 0 0 1 16 0"/>
                    </svg>
                </span>
                <div class="txt">
                    <b>Nom complet</b>
                    <small>Ghita Sebti</small>
                </div>
            </li>
            {{-- Email --}}
            <li>
                <span class="tile">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 5h16v14H4z M4 7l8 6 8-6"/>
                    </svg>
                </span>
                <div class="txt">
                    <b>Email</b>
                    <small>ghita.sebti@example.com</small>
                </div>
            </li>
            {{-- CNE --}}
            <li>
                <span class="tile">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 4h16v16H4z M8 8h8 M8 12h8"/>
                    </svg>
                </span>

                <div class="txt">
                    <b>CNE</b>
                    <small>G123456789</small>
                </div>
            </li>
            {{-- Filière --}}
            <li>
                <span class="tile">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 9l9-6 9 6-9 6z M7 12v5l5 4 5-4v-5"/>
                    </svg>
                </span>

                <div class="txt">
                    <b>Filière</b>
                    <small>Informatique Appliquée</small>
                </div>
            </li>
        </ul>
    </div>
    {{-- Mes cours --}}
    <div class="card span-2">
        <div class="card-head">
            <h2>Mes cours récents</h2>
            <a href="{{ url('/etudiant/cours') }}" class="link">
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
                    <tr>
                        <td><b>Introduction à Java</b></td>
                        <td>PDF</td>
                        <td>10/11/2026</td>
                        <td>
                            <span>Document disponible</span>
                        </td>
                    </tr>
                    <tr>
                        <td><b>SQL avancé</b></td>
                        <td>PDF</td>
                        <td>08/11/2026</td>
                        <td>
                            <span>Document disponible</span>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Introduction au Machine Learning</b></td>
                        <td>Support de cours</td>
                        <td>05/11/2026</td>
                        <td>
                            <span>Document disponible</span>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Réseaux informatiques</b></td>
                        <td>PDF</td>
                        <td>02/11/2026</td>
                        <td>
                            <span>Document disponible</span>
                        </td>
                    </tr>
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
                        <a href="{{ url('/etudiant/modules') }}">
                            Mes modules
                        </a>
                    </b>
                    <small>Consulter mes modules</small>
                </div>
            </li>
            {{-- Emploi du temps --}}
            <li>
                <span class="tile">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 5h16v16H4z M8 3v4 M16 3v4 M4 10h16"/>
                    </svg>
                </span>
                <div class="txt">
                    <b>
                        <a href="{{ url('/etudiant/emploi-du-temps') }}">
                            Mon emploi du temps
                        </a>
                    </b>
                    <small>Consulter les horaires des cours</small>
                </div>
            </li>
            {{-- Notes --}}
            <li>
                <span class="tile">
                    <svg viewBox="0 0 24 24">
                        <path d="M5 3h14v18H5z M8 8h8 M8 12h8"/>
                    </svg>
                </span>

                <div class="txt">
                    <b>
                        <a href="{{ url('/etudiant/notes') }}">
                            Mes notes
                        </a>
                    </b>
                    <small>Consulter mes résultats</small>
                </div>
            </li>
            {{-- Supports de cours --}}
            <li>
                <span class="tile">
                    <svg viewBox="0 0 24 24">
                        <path d="M6 2h8l5 5v15H6z M14 2v6h5"/>
                    </svg>
                </span>
                <div class="txt">
                    <b>
                        <a href="{{ url('/etudiant/cours') }}">
                            Supports de cours
                        </a>
                    </b>
                    <small>Consulter les documents pédagogiques</small>
                </div>
            </li>
            {{-- Profil --}}
            <li>
                <span class="tile">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8 M4 21a8 8 0 0 1 16 0"/>
                    </svg>
                </span>
                <div class="txt">
                    <b>
                        <a href="{{ url('/profil') }}">
                            Mon profil
                        </a>
                    </b>
                    <small>Gérer mes informations personnelles</small>
                </div>
            </li>
        </ul>
    </div>
</section>

    {{-- Mes notes --}}
    <div class="card span-2">
        <div class="card-head">
            <h2>Mes résultats</h2>
            <a href="{{ url('/etudiant/notes') }}" class="link">
                Voir tout
            </a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Type d'évaluation</th>
                        <th>Note</th>
                        <th>Résultat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Programmation Java</b></td>
                        <td>Examen final</td>
                        <td>15/20</td>
                        <td>Validé</td>
                    </tr>
                    <tr>
                        <td><b>Base de données avancée</b></td>
                        <td>Contrôle continu</td>
                        <td>14/20</td>
                        <td>Validé</td>
                    </tr>
                    <tr>
                        <td><b>Sciences des données</b></td>
                        <td>Examen final</td>
                        <td>16/20</td>
                        <td>Validé</td>
                    </tr>
                    <tr>
                        <td><b>Réseaux informatiques</b></td>
                        <td>Contrôle continu</td>
                        <td>13/20</td>
                        <td>Validé</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@include('layouts.bas')
