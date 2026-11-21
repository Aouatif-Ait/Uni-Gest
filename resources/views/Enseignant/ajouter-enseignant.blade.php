{{-- resources/views/Enseignant/ajouter-enseignant.blade.php --}}

@include('layouts.haut')

<div class="register-container">

    <div class="register-card">

        <div class="register-header">
            <div class="register-icon">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="10" cy="7" r="4"/>
                    <path d="M19 8v6m-3-3h6"/>
                </svg>
            </div>
            <h2>Créer un compte Enseignant</h2>

            <p>
                Rejoignez la plateforme UniGest
            </p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('enseignant.store') }}"
              method="POST">

            @csrf

            {{-- Informations personnelles --}}
            <h5 class="register-section-title">
                <svg viewBox="0 0 24 24" width="20" height="20"
                    fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                </svg>
                Informations personnelles
            </h5>

            <div class="register-grid">

                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text"
                           id="nom"
                           name="nom"
                           value="{{ old('nom') }}"
                           placeholder="Votre nom"
                           required>
                </div>

                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text"
                           id="prenom"
                           name="prenom"
                           value="{{ old('prenom') }}"
                           placeholder="Votre prénom"
                           required>
                </div>

                <div class="form-group full-width">
                    <label for="email">Adresse Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="exemple@unigest.ma"
                           required>
                </div>

            </div>

            {{-- Informations professionnelles --}}
            <h5 class="register-section-title">
                <svg viewBox="0 0 24 24" width="20" height="20"
                    fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 10l10-7 10 7"/>
                    <path d="M4 10v8m5-8v8m6-8v8m5-8v8M2 21h20"/>
                </svg>
                Informations professionnelles
            </h5>

            <div class="register-grid">

                <div class="form-group">
                    <label for="specialite">Spécialité</label>
                    <input type="text"
                           id="specialite"
                           name="specialite"
                           value="{{ old('specialite') }}"
                           placeholder="Ex: Informatique"
                           required>
                </div>

                <div class="form-group">
                    <label for="grade">Grade</label>
                    <input type="text"
                           id="grade"
                           name="grade"
                           value="{{ old('grade') }}"
                           placeholder="Ex: Professeur"
                           required>
                </div>

            </div>

            {{-- Sécurité --}}
            <h5 class="register-section-title">
                <svg viewBox="0 0 24 24" width="20" height="20"
                    fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
                Sécurité du compte
            </h5>

            <div class="register-grid">

                <div class="form-group">
                    <label for="password">Mot de passe</label>

                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Minimum 8 caractères"
                           required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        Confirmer le mot de passe
                    </label>

                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Confirmer le mot de passe"
                           required>
                </div>

            </div>

            <button type="submit" class="register-submit">
                <svg viewBox="0 0 24 24" width="20" height="20"
                    fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="10" cy="7" r="4"/>
                    <path d="m19 8 0 6m-3-3h6"/>
                </svg>
                Créer mon compte
            </button>

        </form>

        <div class="register-footer">
            Vous avez déjà un compte ?
            <a href="{{ route('login') }}">Se connecter</a>
        </div>

    </div>

</div>

@include('layouts.bas')