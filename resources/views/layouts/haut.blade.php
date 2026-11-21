{{-- resources/views/layouts/haut.blade.php : <head>, barre latérale, barre du haut --}}
@php
    $role = auth()->user()->role ?? null;

    
    $menu = [
        ['Tableau de bord', '/', 'M3 3h8v8H3z M13 3h8v5h-8z M13 10h8v11h-8z M3 13h8v8H3z'],

        ['Étudiants', '/etudiants', 'M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8 M20 8v6 M23 11h-6'],

        ['Enseignants', '/enseignants', 'M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8 M20 8v6'],

        ['Filières', '/filieres', 'M4 6h16 M4 12h16 M4 18h16 M8 3v6 M16 9v6 M8 15v6'],

        ['Modules & cours', '/modules', 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20 M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z'],

        ['Examens', '/examens', 'M8 2h8 M9 2v4h6V2 M5 6h14v16H5z M9 12h6 M9 16h6'],

        [
            'Notes',
            match ($role) {
                'Enseignant' => 'enseignant/moyennes',
                'Etudiant' => 'etudiant/notes',
                default => 'notes',
            },
            'M4 19V5 M4 19h16 M8 16v-5 M13 16V7 M18 16V3'
        ],

        ['PFE', '/pfe', 'M12 3l2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3l-5.6 2.9 1.1-6.2L3 9.6l6.2-.9L12 3z'],

        ['Annonces', '/annonces', 'M3 11v2a2 2 0 0 0 2 2h2l8 5V4l-8 5H5a2 2 0 0 0-2 2z M19 9a5 5 0 0 1 0 6 M21 5a10 10 0 0 1 0 14'],

        ['Réclamations', '/reclamations', 'M12 9v4 M12 17h.01 M10.3 3.9L2.4 18a2 2 0 0 0 1.7 3h15.8a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z'],

        ['Sécurité', '/securite', 'M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z M9 12l2 2 4-4'],
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
