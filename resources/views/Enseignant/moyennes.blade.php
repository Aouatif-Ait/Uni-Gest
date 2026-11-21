{{-- resources/views/Enseignant/enseignant.blade.php --}}

@include('layouts.haut', [
    'titre' => 'UniGest — Les moyennes'
])
<div class="container">
    <h2>Consultation des moyennes</h2>
    <br>
    <form <form action="{{ route('Enseignant.rechercherMoyennes') }}" method="POST">
    @csrf
        <div class="mb-3">
            <label for="annee" class="form-label">
                Année universitaire
            </label>

            <select name="idAnnee" id="annee" class="form-select champ-recherche">
                <option value="">Choisir une année universitaire</option>

                @foreach($annees as $annee)
                    <option value="{{ $annee->IDANNEE }}">
                        {{ $annee->libelle }}
                    </option>
                @endforeach
            </select>
        </div>
        <br>
        <div class="mb-3">
            <label for="cne" class="form-label">CNE de l'étudiant</label>

            <input
                type="text"
                name="cne"
                id="cne"
                class="form-control champ-recherche"
                placeholder="Saisir le CNE de l'étudiant"
                value="{{ old('cne') }}"
            >
        </div>
        <br>
        <button type="submit" class="btn btn-primary">
            Rechercher
        </button>
    </form>
    @if(isset($message) && $message)
        <div class="alert alert-warning mt-3">
            {{ $message }}
        </div>
    @endif

    @if(isset($etudiant) && $etudiant)
        <div class="card mt-4">
            <div class="card-body">
                <h5>Informations de l'étudiant</h5>

                <p><strong>CNE :</strong> {{ $etudiant->cne }}</p>
                <p><strong>Nom :</strong> {{ $etudiant->nom }}</p>
                <p><strong>Prénom :</strong> {{ $etudiant->prenom }}</p>

                <h6 class="mt-3">Filières inscrites</h6>

                @forelse($inscriptions as $inscription)
                    <p>
                        {{ $inscription->nom }}
                        ({{ $inscription->code }})
                    </p>
                @empty
                    <p>Aucune inscription trouvée.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>

@if(isset($resultats) && count($resultats) > 0)
    <hr class="my-4">
    <h4 class="mb-3">Relevé de notes</h4>
    @foreach($resultats as $resultat)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    Filière : {{ $resultat['filiere']->nom }}
                    ({{ $resultat['filiere']->code }})
                </h5>
            </div>
            <div class="card-body">
                @foreach($resultat['semestres'] as $item)
                    <h5 class="mt-3 mb-3">
                        Semestre {{ $item['semestre']->libelle }}
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-moyennes">
                            <colgroup>
                                <col style="width: 20%">
                                <col style="width: 55%">
                                <col style="width: 25%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Code Module</th>
                                    <th>Intitulé du module</th>
                                    <th>Note d'examen /20</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($item['modules'] as $module)
                                    <tr>
                                        <td>{{ $module->code_mdl }}</td>
                                        <td class="text-start">
                                            {{ $module->intitule }}
                                        </td>
                                        <td>
                                            @if($module->note !== null)
                                                {{ number_format($module->note, 2) }}/20
                                            @else
                                                <span class="text-muted">
                                                    Non saisie
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            Aucun module trouvé.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mb-4">
                        <strong>Moyenne du semestre :</strong>
                        @if($item['moyenne'] !== null)
                            <span class="badge bg-primary">
                                {{ number_format($item['moyenne'], 2) }}/20
                            </span>
                        @else
                            <span class="text-muted">
                                Non disponible
                            </span>
                        @endif
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>
    @endforeach
    <div class="card border-primary mb-4">
        <div class="card-body text-center">
            <h5>Moyenne générale de l'année universitaire</h5>
            @if($moyenneAnnuelle !== null)
                <h2 class="text-primary">
                    {{ number_format($moyenneAnnuelle, 2) }}/20
                </h2>
            @else
                <p class="text-muted mb-0">
                    Aucune note disponible pour cette année.
                </p>
            @endif
        </div>
    </div>
@endif

@include('layouts.bas')
