{{-- resources/views/layouts/haut.blade.php : <head>, barre latérale, barre du haut --}}
@php
    $role = auth()->user()->role ?? null;

    $menu = [
        ['Tableau de bord', '/', 'M3 11l9-8 9 8v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V11z'],
        ['Étudiants', '/etudiants', 'M12 3l10 5-10 5L2 8l10-5zm-6 9v4c0 1.5 2.7 3 6 3s6-1.5 6-3v-4'],
        ['Enseignants', '/enseignants', 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm-8 9a8 8 0 0 1 16 0'],
        ['Filières', '/filieres', 'M4 5h16M4 12h16M4 19h16'],
        ['Modules & cours', '/modules', 'M5 4h11a3 3 0 0 1 3 3v13H8a3 3 0 0 1-3-3V4z'],
        ['Examens', '/examens', 'M7 3h10v4H7zM5 7h14v14H5z'],

        [
            'Notes',
            match ($role) {
                'Enseignant' => 'enseignant/moyennes',
                'Etudiant' => 'etudiant/notes',
                default => 'notes',
            },
            'M4 20V10m6 10V4m6 16v-7m4 7H2'
        ],

        ['PFE', '/pfe', 'M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z'],
        ['Annonces', '/annonces', 'M3 11v2a1 1 0 0 0 1 1h3l6 4V6L7 10H4a1 1 0 0 0-1 1zm14-2a4 4 0 0 1 0 6'],
        ['Réclamations', '/reclamations', 'M12 9v4m0 4h.01M10.3 3.9L2.4 18a2 2 0 0 0 1.7 3h15.8a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z'],
        ['Sécurité', '/securite', 'M12 2l8 3v6c0 5-3.5 9-8 11-4.5-2-8-6-8-11V5l8-3z'],
    ];
@endphp
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titre ?? 'UniGest' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Chargé dans le <head> (sans defer) pour appliquer le thème avant l'affichage --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
<div class="app">

    {{-- ===== Barre latérale ===== --}}
    <aside class="sidebar" id="sidebar" aria-label="Navigation principale">
        <a href="{{ url('/') }}" class="brand">
            <span class="brand-mark">
                <svg viewBox="0 0 24 24"><path d="M12 3l10 5-10 5L2 8l10-5zM6 12v4c0 1.5 2.7 3 6 3s6-1.5 6-3v-4"/></svg>
            </span>
            UniGest
        </a>

        <nav class="nav">
            @foreach ($menu as [$label, $href, $icon])
                <a href="{{ url($href) }}"
                class="{{ request()->is(trim($href, '/')) ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <path d="{{ $icon }}"/>
                    </svg>

                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="sidebar-foot">
            <strong>Besoin d'aide ?</strong>
            Consultez le guide d'utilisation de la plateforme.
            <a href="{{ url('/aide') }}">Ouvrir le guide</a>
        </div>
    </aside>

    {{-- ===== Zone principale ===== --}}
    <div class="main">
        <header class="topbar">
            <button class="burger" id="burger" type="button" aria-label="Ouvrir le menu">
                <svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <form class="search" action="{{ url('/recherche') }}" method="GET" role="search">
                <input type="search" name="q" placeholder="Rechercher un étudiant, un module…" aria-label="Rechercher">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
            </form>

            <div class="top-actions">
                <button class="icon-btn" id="theme-toggle" type="button" aria-label="Changer de thème">
                    <svg class="moon" viewBox="0 0 24 24"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                    <svg class="sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4l1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4m11.4-11.4l1.4-1.4"/></svg>
                </button>
                

@auth
    <div class="user-menu" id="user-menu">
        <button class="user-btn" id="user-btn" type="button"
                aria-haspopup="true" aria-expanded="false">

            <span class="avatar">
                {{ strtoupper(mb_substr(auth()->user()->nom ?? 'U', 0, 1)) }}
            </span>

            <span class="user-info">
                <span class="user-name">
                    <span class="user-name">
                        {{ auth()->user()->nom }}&nbsp; {{ auth()->user()->prenom }}
                    </span>
                </span>
                <br>
                <span class="user-role">
                    {{ auth()->user()->role }}
                </span>
            </span>

            <svg viewBox="0 0 24 24">
                <path d="M6 9l6 6 6-6"/>
            </svg>
        </button>

        <div class="user-dropdown" role="menu">
            <a href="{{ url('/profil') }}" role="menuitem">
                Mon profil
            </a>

            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit" role="menuitem">
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
@endauth

            </div>
        </header>

        <main class="content">
