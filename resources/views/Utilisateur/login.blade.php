{{-- resources/views/login.blade.php --}}
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion — UniGest</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    {{-- Applique le thème avant l'affichage (sans defer) --}}
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
<div class="auth">

    {{-- ===== Panneau d'accueil (grands écrans) ===== --}}
    <aside class="auth-aside">
        <a href="{{ url('/') }}" class="brand">
            <span class="brand-mark">
                <svg viewBox="0 0 24 24"><path d="M12 3l10 5-10 5L2 8l10-5zM6 12v4c0 1.5 2.7 3 6 3s6-1.5 6-3v-4"/></svg>
            </span>
            UniGest
        </a>

        <div>
            <h2>Retrouvez tout ce qui vous concerne, au même endroit.</h2>
            <ul class="auth-points">
                <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Notes, convocations et cours</li>
                <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Sujets et candidatures de PFE</li>
                <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Annonces et réclamations</li>
            </ul>
        </div>

        <small>© {{ date('Y') }} UniGest — Plateforme Intégrée de Gestion Universitaire</small>
    </aside>

    {{-- ===== Formulaire ===== --}}
    <div class="auth-main">
        <div class="auth-top">
            <a href="{{ url('/') }}" class="brand brand-mobile">
                <span class="brand-mark">
                    <svg viewBox="0 0 24 24"><path d="M12 3l10 5-10 5L2 8l10-5zM6 12v4c0 1.5 2.7 3 6 3s6-1.5 6-3v-4"/></svg>
                </span>
                UniGest
            </a>
            <a href="{{ url('/') }}" class="link back-link">Retour à l'accueil</a>
            <button class="icon-btn" id="theme-toggle" type="button" aria-label="Changer de thème">
                <svg class="moon" viewBox="0 0 24 24"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                <svg class="sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4l1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4m11.4-11.4l1.4-1.4"/></svg>
            </button>
        </div>

        <div class="auth-box">
            <h1>Se connecter</h1>
            <p class="auth-sub">Utilisez le compte fourni par l'université.</p>

            @if (session('status'))
                <div class="alert alert-ok" role="status">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error" role="alert">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ url('/login') }}" novalidate>
                @csrf

                <div class="field">
                    <label for="email">Adresse e-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           autocomplete="username" required autofocus
                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                </div>

                <div class="field">
                    <label for="password">Mot de passe</label>
                    <div class="input-wrap">
                        <input id="password" type="password" name="password"
                               autocomplete="current-password" required
                               class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                        <button type="button" id="toggle-password" class="toggle-pass"
                                aria-label="Afficher le mot de passe">Afficher</button>
                    </div>
                </div>

                <div class="auth-row">
                    <label class="check">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Rester connecté
                    </label>
                    <a href="{{ url('/mot-de-passe-oublie') }}" class="link">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn-login block">Se connecter</button>
                <div class="inscription-menu">
                    <button type="button" class="btn-inscription">
                        <i class="bi bi-person-plus"></i>
                        Inscription
                    </button>

                    <div class="inscription-dropdown">
                        <a href="{{ route('enseignant.create') }}">
                            S'inscrire en tant qu'enseignant
                        </a>

                        <a href="{{ route('etudiant.create') }}">
                            S'inscrire en tant qu'étudiant
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
