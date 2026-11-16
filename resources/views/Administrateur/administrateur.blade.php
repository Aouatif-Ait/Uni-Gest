{{-- resources/views/admin/dashboard.blade.php --}}
@php
    // Données par défaut : remplacées par celles envoyées depuis le contrôleur.
    $stats = $stats ?? ['etudiants' => 200, 'enseignants' => 20, 'filieres' => 8, 'pfe' => 60];

    $filieres = $filieres ?? [
        ['nom' => 'Licence',  'total' => 90],
        ['nom' => 'Master',   'total' => 80],
        ['nom' => 'Doctorat', 'total' => 30],
    ];

    $examens = $examens ?? [
        ['module' => 'Bases de données',   'salle' => 'Amphi A',  'date' => '12 Nov', 'heure' => '09:00', 'session' => 'Normale'],
        ['module' => 'Systèmes embarqués', 'salle' => 'Salle 14', 'date' => '14 Nov', 'heure' => '14:00', 'session' => 'Normale'],
        ['module' => 'Réseaux',            'salle' => 'Salle 07', 'date' => '18 Nov', 'heure' => '10:30', 'session' => 'Rattrapage'],
    ];

    $meilleurs = $meilleurs ?? [
        ['nom' => 'Salma Benali',   'cne' => 'R130045123', 'filiere' => 'Master IA',    'moyenne' => 17.4],
        ['nom' => 'Youssef Amrani', 'cne' => 'R130045187', 'filiere' => 'Licence IA',   'moyenne' => 16.9],
        ['nom' => 'Imane Tazi',     'cne' => 'R130045201', 'filiere' => 'Licence Info', 'moyenne' => 16.2],
    ];

    $annonces = $annonces ?? [
        ['titre' => 'Dépôt des sujets de PFE',          'date' => '10 Nov', 'auteur' => 'Responsable PFE'],
        ['titre' => 'Convocations de la session normale', 'date' => '08 Nov', 'auteur' => 'Administration'],
        ['titre' => 'Réunion du département',            'date' => '05 Nov', 'auteur' => 'Administration'],
    ];

    $pfe = $pfe ?? [
        ['label' => 'Sujets proposés', 'valeur' => 60,  'max' => 60],
        ['label' => 'Candidatures',    'valeur' => 142, 'max' => 200],
        ['label' => 'PFE affectés',    'valeur' => 38,  'max' => 60],
    ];


    // Anneau (donut) calculé à partir des filières
    $totalFilieres = max(1, collect($filieres)->sum('total'));
    $couleurs = ['var(--c-primary)', 'var(--c-accent)', 'var(--c-teal)'];
    $cumul = 0;
    $segments = [];
    foreach ($filieres as $i => $f) {
        $debut = $cumul;
        $cumul += $f['total'] / $totalFilieres * 100;
        $segments[] = $couleurs[$i % 3] . ' ' . round($debut, 2) . '% ' . round($cumul, 2) . '%';
    }
    $donut = 'conic-gradient(' . implode(', ', $segments) . ')';
@endphp
@include('layouts.haut', ['titre' => 'UniGest — Tableau de bord'])

            <section class="welcome">
                <h1>Bienvenue sur UniGest</h1>
                <p>Suivez l'activité du département : étudiants, examens, notes et PFE.</p>
            </section>

            {{-- Statistiques --}}
            <section class="grid stats" aria-label="Chiffres clés">
                @foreach ([
                    ['Étudiants',     $stats['etudiants'],   'M12 3l10 5-10 5L2 8l10-5zm-6 9v4c0 1.5 2.7 3 6 3s6-1.5 6-3v-4'],
                    ['Enseignants',   $stats['enseignants'], 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm-8 9a8 8 0 0 1 16 0'],
                    ['Filières',      $stats['filieres'],    'M4 5h16M4 12h16M4 19h16'],
                    ['Sujets de PFE', $stats['pfe'],         'M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z'],
                ] as [$label, $value, $icon])
                    <article class="stat">
                        <div>
                            <span>{{ $label }}</span>
                            <strong>{{ number_format($value, 0, ',', ' ') }}</strong>
                        </div>
                        <span class="ico"><svg viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg></span>
                    </article>
                @endforeach
            </section>

            <section class="grid cols">

                {{-- Prochains examens --}}
                <div class="card span-2">
                    <div class="card-head">
                        <h2>Prochains examens</h2>
                        <a href="{{ url('/examens') }}" class="link">Voir tout</a>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr><th>Module</th><th>Salle</th><th>Date</th><th>Heure</th><th>Session</th></tr>
                            </thead>
                            <tbody>
                            @forelse ($examens as $e)
                                <tr>
                                    <td><b>{{ $e['module'] }}</b></td>
                                    <td>{{ $e['salle'] }}</td>
                                    <td>{{ $e['date'] }}</td>
                                    <td>{{ $e['heure'] }}</td>
                                    <td><span class="badge {{ $e['session'] === 'Normale' ? '' : 'alt' }}">{{ $e['session'] }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5">Aucun examen planifié. Ajoutez-en depuis la page Examens.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Répartition par cycle --}}
                <div class="card">
                    <div class="card-head"><h2>Étudiants par cycle</h2></div>
                    <div class="donut-wrap">
                        <div class="donut" style="background: {{ $donut }};" role="img"
                             aria-label="Répartition des étudiants par cycle">
                            <div class="donut-center"><small>Total</small><b>{{ $totalFilieres }}</b></div>
                        </div>
                        <div class="legend">
                            @foreach ($filieres as $i => $f)
                                <span><i style="background: {{ $couleurs[$i % 3] }}"></i>{{ $f['nom'] }} ({{ $f['total'] }})</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Meilleurs étudiants --}}
                <div class="card span-2">
                    <div class="card-head">
                        <h2>Meilleurs étudiants</h2>
                        <a href="{{ url('/notes') }}" class="link">Voir les notes</a>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>Nom</th><th>CNE</th><th>Filière</th><th>Moyenne</th></tr></thead>
                            <tbody>
                            @foreach ($meilleurs as $m)
                                <tr>
                                    <td>
                                        <div class="who">
                                            <span class="avatar">{{ strtoupper(mb_substr($m['nom'], 0, 1)) }}</span>
                                            {{ $m['nom'] }}
                                        </div>
                                    </td>
                                    <td>{{ $m['cne'] }}</td>
                                    <td>{{ $m['filiere'] }}</td>
                                    <td class="score">{{ number_format($m['moyenne'], 2, ',', '') }} / 20</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Suivi des PFE --}}
                <div class="card">
                    <div class="card-head">
                        <h2>Suivi des PFE</h2>
                        <a href="{{ url('/pfe') }}" class="link">Détails</a>
                    </div>
                    @foreach ($pfe as $p)
                        <div class="bar-row">
                            <div class="bar-top"><span>{{ $p['label'] }}</span><span>{{ $p['valeur'] }}</span></div>
                            <div class="bar"><div style="width: {{ min(100, round($p['valeur'] / max(1, $p['max']) * 100)) }}%"></div></div>
                        </div>
                    @endforeach
                </div>

                {{-- Dernières annonces --}}
                <div class="card span-2">
                    <div class="card-head">
                        <h2>Dernières annonces</h2>
                        <a href="{{ url('/annonces') }}" class="link">Voir tout</a>
                    </div>
                    <ul class="list">
                        @foreach ($annonces as $a)
                            <li>
                                <span class="tile"><svg viewBox="0 0 24 24"><path d="M3 11v2a1 1 0 0 0 1 1h3l6 4V6L7 10H4a1 1 0 0 0-1 1zm14-2a4 4 0 0 1 0 6"/></svg></span>
                                <div class="txt"><b>{{ $a['titre'] }}</b><small>{{ $a['auteur'] }}</small></div>
                                <time>{{ $a['date'] }}</time>
                            </li>
                        @endforeach
                    </ul>
                </div>


            </section>

@include('layouts.bas')
