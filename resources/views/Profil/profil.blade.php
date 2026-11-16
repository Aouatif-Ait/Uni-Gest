
@include('layouts.haut', ['titre' => 'UniGest — Mon profil'])

<section class="page-header">
    <h1>Mon profil</h1>
    <p>Consultez et modifiez vos informations personnelles.</p>
</section>

<section class="card profile-card">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="profile-header">
        <div class="profile-avatar">
            {{ strtoupper(mb_substr($utilisateur->nom, 0, 1)) }}
        </div>

        <div>
            <h2>
                {{ $utilisateur->nom }} {{ $utilisateur->prenom }}
            </h2>
            <p>{{ $utilisateur->role }}</p>
        </div>
    </div>

    <hr>

    <form action="{{ route('profil.update') }}" method="POST">

        @csrf
        @method('PUT')

        <h3>Informations personnelles</h3>

        <div class="form-group">
            <label for="nom">Nom</label>
            <input
                type="text"
                id="nom"
                name="nom"
                value="{{ old('nom', $utilisateur->nom) }}"
                required
            >
        </div>

        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input
                type="text"
                id="prenom"
                name="prenom"
                value="{{ old('prenom', $utilisateur->prenom) }}"
                required
            >
        </div>

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $utilisateur->email) }}"
                required
            >
        </div>

        <div class="form-group">
            <label>Rôle</label>
            <input
                type="text"
                value="{{ $utilisateur->role }}"
                disabled
            >
        </div>

        <hr>

        <h3>Modifier le mot de passe</h3>

        <p>
            Laissez ces champs vides si vous ne souhaitez pas
            modifier votre mot de passe.
        </p>

        <div class="form-group">
            <label for="current_password">
                Ancien mot de passe
            </label>

            <input
                type="password"
                id="current_password"
                name="current_password"
                autocomplete="current-password"
            >
        </div>

        <div class="form-group">
            <label for="nouveau_mot_de_passe">
                Nouveau mot de passe
            </label>

            <input
                type="password"
                id="nouveau_mot_de_passe"
                name="nouveau_mot_de_passe"
                autocomplete="new-password"
            >
        </div>

        <div class="form-group">
            <label for="nouveau_mot_de_passe_confirmation">
                Confirmer le nouveau mot de passe
            </label>

            <input
                type="password"
                id="nouveau_mot_de_passe_confirmation"
                name="nouveau_mot_de_passe_confirmation"
                autocomplete="new-password"
            >
        </div>

        <button type="submit" class="btn-primary">
            Enregistrer les modifications
        </button>

    </form>

</section>

@include('layouts.bas')
