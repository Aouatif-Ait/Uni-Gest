
@include('layouts.haut', [
    'titre' => 'UniGest — Saisie des notes'
])

<main class="container">

    <section class="welcome">
        <h1>Saisie des notes</h1>
        <p>
            Gérez et saisissez les notes des étudiants
            par filière et par module.
        </p>
        <br>
    </section>

    <!-- Sélection du module -->
    <section class="card">

        <h2>Informations du module</h2>
        <br>
        <div class="form-group">
            <label for="filiere">Filière</label>
            <select id="filiere" name="filiere">
                <option value="">Choisir une filière</option>
                <option>Informatique Appliquée</option>
                <option>Génie Informatique</option>
            </select>
        </div>
        <br>
        <div class="form-group">
            <label for="semestre">Semestre</label>
            <select id="semestre" name="semestre">
                <option value="">Choisir un semestre</option>
                <option>Semestre 1</option>
                <option>Semestre 2</option>
                <option>Semestre 3</option>
                <option>Semestre 4</option>
            </select>
        </div>
        <br>
        <div class="form-group">
            <label for="module">Module</label>
            <select id="module" name="module">
                <option value="">Choisir un module</option>
                <option>Programmation PHP</option>
                <option>Base de données</option>
                <option>Java</option>
            </select>
        </div>

        <button type="button">
            Afficher les étudiants
        </button>

    </section>
    <br>
    <!-- Liste des étudiants -->
    <section class="card">

        <h2>Liste des étudiants</h2>
        <br>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>CNE</th>
                        <th>Note /20</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>1</td>
                        <td>El Amrani</td>
                        <td>Ahmed</td>
                        <td>R123456</td>
                        <td>
                            <input type="number"
                                   min="0"
                                   max="20"
                                   step="0.01"
                                   placeholder="Note">
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Benali</td>
                        <td>Salma</td>
                        <td>R234567</td>
                        <td>
                            <input type="number"
                                   min="0"
                                   max="20"
                                   step="0.01"
                                   placeholder="Note">
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>Idrissi</td>
                        <td>Youssef</td>
                        <td>R345678</td>
                        <td>
                            <input type="number"
                                   min="0"
                                   max="20"
                                   step="0.01"
                                   placeholder="Note">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="actions">
            <button type="button">
                Enregistrer les notes
            </button>
        </div>

    </section>

</main>
