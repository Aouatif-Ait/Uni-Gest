{{-- resources/views/accueil.blade.php — page d'accueil commune à tous les visiteurs --}}
@php
    $stats = $stats ?? ['etudiants' => 200, 'enseignants' => 20, 'filieres' => 8];

    $annonces = $annonces ?? [
        ['titre' => 'Dépôt des sujets de PFE',            'date' => '10 Nov', 'auteur' => 'Responsable PFE'],
        ['titre' => 'Convocations de la session normale', 'date' => '08 Nov', 'auteur' => 'Administration'],
        ['titre' => 'Réunion du département',             'date' => '05 Nov', 'auteur' => 'Administration'],
    ];

    $profils = [
        ['Étudiant',        'Consulter ses notes, ses convocations et ses cours, candidater aux sujets de PFE et envoyer une réclamation.',
            'M12 3l10 5-10 5L2 8l10-5zm-6 9v4c0 1.5 2.7 3 6 3s6-1.5 6-3v-4'],
        ['Enseignant',      'Gérer ses cours, TD et TP, saisir les notes, surveiller les examens et encadrer des projets de fin d\'études.',
            'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm-8 9a8 8 0 0 1 16 0'],
        ['Responsable PFE', 'Proposer des sujets, suivre les candidatures et contrôler l\'affectation des encadrants.',
            'M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z'],
        ['Administrateur',  'Administrer les comptes, les filières, les modules, les examens et les annonces du département.',
            'M12 2l8 3v6c0 5-3.5 9-8 11-4.5-2-8-6-8-11V5l8-3z'],
    ];

    $modules = [
        ['Comptes et sécurité',      'Accès par profil et protection des données.',        'M12 2l8 3v6c0 5-3.5 9-8 11-4.5-2-8-6-8-11V5l8-3z'],
        ['Parcours académique',      'Cycles, filières, semestres et inscriptions.',        'M4 5h16M4 12h16M4 19h16'],
        ['Cours et modules',         'Affectations, cours, TD, TP et salles.',              'M5 4h11a3 3 0 0 1 3 3v13H8a3 3 0 0 1-3-3V4z'],
        ['Examens et notes',         'Sessions, convocations, surveillance et résultats.',  'M7 3h10v4H7zM5 7h14v14H5z'],
        ['Projets de fin d\'études', 'Sujets, candidatures et affectation des encadrants.', 'M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z'],
        ['Annonces et réclamations', 'Communication entre l\'administration et les étudiants.', 'M3 11v2a1 1 0 0 0 1 1h3l6 4V6L7 10H4a1 1 0 0 0-1 1zm14-2a4 4 0 0 1 0 6'],
    ];
@endphp
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniGest — Plateforme de gestion universitaire</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/unigest.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vitrine.css') }}">

    {{-- Applique le thème avant l'affichage (sans defer) --}}
    <script src="{{ asset('js/unigest.js') }}"></script>
</head>
<body>

{{-- ===== En-tête ===== --}}
<header class="v-header">
    <div class="v-wrap">
        <a href="{{ url('/') }}" class="brand">
            <span class="brand-mark">
                <svg viewBox="0 0 24 24"><path d="M12 3l10 5-10 5L2 8l10-5zM6 12v4c0 1.5 2.7 3 6 3s6-1.5 6-3v-4"/></svg>
            </span>
            UniGest
        </a>

        <nav class="v-nav" aria-label="Sections de la page">
            <a href="#profils">Profils</a>
            <a href="#modules">Fonctionnalités</a>
            <a href="#annonces">Annonces</a>
        </nav>

        <div class="v-actions">
            <button class="icon-btn" id="theme-toggle" type="button" aria-label="Changer de thème">
                <svg class="moon" viewBox="0 0 24 24"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                <svg class="sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4l1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4m11.4-11.4l1.4-1.4"/></svg>
            </button>
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Mon espace</a>
            @else
                <a href="{{ url('/login') }}" class="btn btn-primary">Se connecter</a>
            @endauth
        </div>
    </div>
</header>

<main>
    {{-- ===== Hero ===== --}}
    <section class="v-hero">
        <div class="v-wrap">
            <div>
                <h1>Tout le département universitaire, dans une seule plateforme.</h1>
                <p>Inscriptions, cours, examens, notes et projets de fin d'études : étudiants, enseignants et administration consultent la même information, à jour.</p>

                <div class="v-cta">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Accéder à mon espace</a>
                    @else
                        <a href="{{ url('/login') }}" class="btn btn-primary">Se connecter</a>
                    @endauth
                    <a href="#profils" class="btn btn-ghost">Découvrir les espaces</a>
                </div>

                <div class="v-figures">
                    <div><b>{{ number_format($stats['etudiants'], 0, ',', ' ') }}</b><span>Étudiants</span></div>
                    <div><b>{{ number_format($stats['enseignants'], 0, ',', ' ') }}</b><span>Enseignants</span></div>
                    <div><b>{{ number_format($stats['filieres'], 0, ',', ' ') }}</b><span>Filières</span></div>
                </div>
            </div>

            <div class="v-preview" aria-hidden="true">
                <div class="v-mini">
                    <span class="tile"><svg viewBox="0 0 24 24"><path d="M7 3h10v4H7zM5 7h14v14H5z"/></svg></span>
                    <div><b>Bases de données</b><small>Amphi A · 12 Nov, 09:00</small></div>
                    <span class="val">Convoqué</span>
                </div>
                <div class="v-mini">
                    <span class="tile"><svg viewBox="0 0 24 24"><path d="M4 20V10m6 10V4m6 16v-7m4 7H2"/></svg></span>
                    <div><b>Moyenne du semestre</b><small>Licence IA</small></div>
                    <span class="val">16,90 / 20</span>
                </div>
                <div class="v-mini">
                    <span class="tile"><svg viewBox="0 0 24 24"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg></span>
                    <div><b>Candidature PFE</b><small>Sujet validé par l'encadrant</small></div>
                    <span class="val">Acceptée</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Profils ===== --}}
    <section class="v-section" id="profils">
        <div class="v-wrap">
            <div class="v-title">
                <h2>Un espace adapté à chaque profil</h2>
                <p>Chaque utilisateur retrouve uniquement ce qui le concerne après sa connexion.</p>
            </div>
            <div class="v-grid four">
                @foreach ($profils as [$titre, $texte, $icone])
                    <article class="v-card">
                        <span class="tile"><svg viewBox="0 0 24 24"><path d="{{ $icone }}"/></svg></span>
                        <h3>{{ $titre }}</h3>
                        <p>{{ $texte }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Fonctionnalités ===== --}}
    <section class="v-section alt" id="modules">
        <div class="v-wrap">
            <div class="v-title">
                <h2>Les modules de la plateforme</h2>
                <p>Toute la vie du département, de l'inscription au projet de fin d'études.</p>
            </div>
            <div class="v-grid three">
                @foreach ($modules as [$titre, $texte, $icone])
                    <div class="v-flat">
                        <span class="tile"><svg viewBox="0 0 24 24"><path d="{{ $icone }}"/></svg></span>
                        <div><h3>{{ $titre }}</h3><p>{{ $texte }}</p></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Annonces ===== --}}
    <section class="v-section" id="annonces">
        <div class="v-wrap">
            <div class="v-title">
                <h2>Dernières annonces</h2>
                <p>Les informations officielles du département.</p>
            </div>
            <ul class="list">
                @forelse ($annonces as $a)
                    <li>
                        <span class="tile"><svg viewBox="0 0 24 24"><path d="M3 11v2a1 1 0 0 0 1 1h3l6 4V6L7 10H4a1 1 0 0 0-1 1zm14-2a4 4 0 0 1 0 6"/></svg></span>
                        <div class="txt"><b>{{ $a['titre'] }}</b><small>{{ $a['auteur'] }}</small></div>
                        <time>{{ $a['date'] }}</time>
                    </li>
                @empty
                    <li><div class="txt"><b>Aucune annonce pour le moment.</b></div></li>
                @endforelse
            </ul>
        </div>
    </section>

    {{-- ===== Bandeau final ===== --}}
    <section class="v-wrap">
        <div class="v-band">
            <h2>Accédez à votre espace UniGest</h2>
            <p>Connectez-vous avec votre compte universitaire pour consulter vos informations.</p>
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-light">Mon espace</a>
            @else
                <a href="{{ url('/login') }}" class="btn btn-light">Se connecter</a>
            @endauth
        </div>
    </section>
</main>

<footer class="v-footer">
    <div class="v-wrap">
        <span>© {{ date('Y') }} UniGest — Plateforme Intégrée de Gestion Universitaire</span>
        <span>Laravel · MySQL</span>
    </div>
</footer>

</body>
</html>
