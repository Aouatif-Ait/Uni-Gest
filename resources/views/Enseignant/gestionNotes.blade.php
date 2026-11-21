
@include('layouts.haut', [
    'titre' => 'UniGest — Gestion des notes'
])
<form id="formNotes"
      action="{{ route('Enseignant.afficherEtudiants') }}"
      data-save-url="{{ route('Enseignant.enregistrerNotes') }}"
      method="POST">
    @csrf
    <input type="hidden" id="idExamen" name="idExamen">

    <section class="welcome">
        <h1>Saisie des notes</h1>
        <div id="resultatsEtudiants">
            <p class="text-center">
                Sélectionnez une filière, un semestre et un module.
            </p>
        </div>
        <br>
    </section>
    {{-- Informations du module --}}
    <section class="card">
        <h2>Informations du module</h2>
        <br>
        {{-- Filière --}}
        <div class="form-group">
            <label for="filiere">Filière</label>
            <select id="filiere" name="idFiliere" required>
                <option value="">Choisir une filière</option>
                @foreach($filieres as $fil)
                    <option value="{{ $fil->IDFILIERE }}" >
                        @selected(old('idFiliere') == $fil->IDFILIERE)
                        {{ $fil->nom }}
                    </option>
                @endforeach
            </select>
        </div>
        <br>
        {{-- Semestre --}}
        <div class="form-group">
            <label for="semestre">Semestre</label>
            <select id="semestre" name="idSemestre" disabled required>
                <option value="">Choisir un semestre</option>
            </select>
        </div>
        <br>
        {{-- Module --}}
        <div class="form-group">
            <label for="module">Module</label>
            <select id="module" name="idModule" disabled required>
                <option value="">Choisir un module</option>
            </select>
        </div>
        <br>
        <button type="submit" id="btnAfficher">
            Afficher les étudiants
        </button>
    </section>
    <br>
    {{-- Liste des étudiants --}}
    <section class="card">
        <h2>Liste des étudiants</h2>
        <p id="infoExamen"></p>
        <p id="messageNotes"></p>
        <br>
        @if(isset($examen))
            <p>
                <strong>Examen :</strong>
                {{ $examen->IDEXAMEN }}
            </p>
        @endif
        <div class="table-container">
            <table id="tableEtudiants">
                <thead>
                    <tr>
                        <th>CNE</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Note /20</th>
                    </tr>
                </thead>
                <tbody id="tbodyEtudiants">
                    <tr>
                        <td colspan="5" class="text-center">
                            Sélectionnez un module.
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <button type="button" id="btnEnregistrerNotes" disabled>
                Enregistrer les notes
            </button>
        </div>
    </section>
</form>

