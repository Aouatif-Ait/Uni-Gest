{{-- resources/views/Enseignant/ajouter-enseignant.blade.php --}}

@include('layouts.haut')

<div class="register-container">

    <div class="register-card">

        <div class="register-header">
            <div class="register-icon">
                <i class="bi bi-person-plus"></i>
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
                <i class="bi bi-person"></i>
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
                <i class="bi bi-mortarboard"></i>
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
                <i class="bi bi-shield-lock"></i>
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
                <i class="bi bi-person-check"></i>
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