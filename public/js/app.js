
/* UniGest — JavaScript
   Organisation des fonctionnalités*/

/*1. DARK MODE*/

(function () {
    try {
        const saved = localStorage.getItem('unigest-theme');
        const prefersDark = window.matchMedia(
            '(prefers-color-scheme: dark)'
        ).matches;
        document.documentElement.setAttribute(
            'data-theme',
            saved || (prefersDark ? 'dark' : 'light')
        );
    } catch (e) {
        console.warn('Erreur Dark Mode:', e);
    }
})();
document.addEventListener('DOMContentLoaded', function () {

    /* 2. DARK MODE — Toggle */
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const root = document.documentElement;
            const next =
                root.getAttribute('data-theme') === 'dark'
                    ? 'light'
                    : 'dark';
            root.setAttribute('data-theme', next);
            try {
                localStorage.setItem('unigest-theme', next);
            } catch (e) {
                console.warn('Impossible de sauvegarder le thème.');
            }
        });
    }

    /* 3. SIDEBAR — Menu latéral*/
    const sidebar = document.getElementById('sidebar');
    const burger = document.getElementById('burger');

    if (sidebar && burger) {
        burger.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });

        // Fermer en cliquant en dehors
        document.addEventListener('click', function (e) {
            if (
                window.innerWidth <= 900 &&
                sidebar.classList.contains('open') &&
                !sidebar.contains(e.target) &&
                !e.target.closest('#burger')
            ) {
                sidebar.classList.remove('open');
            }
        });

        // Fermer avec Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                sidebar.classList.remove('open');
            }
        });
    }

    /* 4. USER MENU — Menu utilisateur*/
    const userBtn = document.getElementById('user-btn');
    const userMenu = document.getElementById('user-menu');

    if (userBtn && userMenu) {
        function closeUserMenu() {
            userMenu.classList.remove('open');
            userBtn.setAttribute('aria-expanded', 'false');
        }
        userBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = userMenu.classList.toggle('open');
            userBtn.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );
        });

        // Fermer en cliquant ailleurs
        document.addEventListener('click', function (e) {
            if (!userMenu.contains(e.target)) {
                closeUserMenu();
            }
        });

        // Fermer avec Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeUserMenu();
            }
        });
    }

    /* 5. PASSWORD TOGGLE
       Afficher / masquer le mot de passe */
    const passInput = document.getElementById('password');
    const passToggle = document.getElementById('toggle-password');

    if (passInput && passToggle) {
        passToggle.addEventListener('click', function () {
            const show = passInput.type === 'password';
            passInput.type = show ? 'text' : 'password';
            passToggle.textContent = show ? 'Masquer' : 'Afficher';
            passToggle.setAttribute(
                'aria-label',
                show
                    ? 'Masquer le mot de passe'
                    : 'Afficher le mot de passe'
            );
        });
    }

    /* 6. CHARTS — Statistiques */
    const chartCanvas = document.getElementById('etudiantsParFiliere');
    if (chartCanvas && typeof Chart !== 'undefined') {
        const labels = JSON.parse(chartCanvas.dataset.labels || '[]');
        const valeurs = JSON.parse(chartCanvas.dataset.valeurs || '[]');
        new Chart(chartCanvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nombre des étudiants',
                    data: valeurs,
                    backgroundColor: [
                        '#2563eb',
                        '#0d9488',
                        '#f59e0b',
                        '#8b5cf6',
                        '#ec4899',
                        '#64748b'
                    ],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Nombre des étudiants par filière'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
     /* 7. GESTION DES NOTES
       Sélection dynamique Filière → Semestre → Module */
       
    const filiere = document.getElementById('filiere');
    const semestre = document.getElementById('semestre');
    const module = document.getElementById('module');

    // Vérifier que les éléments existent sur la page
    if (!filiere || !semestre || !module) {
        return;
    }
    // URLs des routes Laravel
    const urlSemestres = '/enseignant/semestres/';
    const urlModules = '/enseignant/modules/';

    // Changement de filière
    filiere.addEventListener('change', function () {
        semestre.innerHTML =
            '<option value="">Choisir un semestre</option>';
        module.innerHTML = '<option value="">Choisir un module</option>';
        semestre.disabled = true;
        module.disabled = true;
        const idFiliere = this.value;
        if (!idFiliere) return;
        fetch(urlSemestres + idFiliere)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur de chargement des semestres');
                }
                return response.json();
            })
            .then(data => {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.IDSEMESTRE;
                    option.textContent = item.libelle;
                    semestre.appendChild(option);
                });
                semestre.disabled = data.length === 0;
            })
            .catch(error => console.error(error));
    });

    // Changement de semestre
    semestre.addEventListener('change', function () {
        module.innerHTML = '<option value="">Choisir un module</option>';
        module.disabled = true;
        const idSemestre = this.value;
        if (!idSemestre) return;
        fetch(urlModules + idSemestre)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur de chargement des modules');
                }
                return response.json();
            })
            .then(data => {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.IDMODULE;
                    option.textContent = item.intitule;

                    module.appendChild(option);
                });
                module.disabled = data.length === 0;
            })
            .catch(error => console.error(error));
    });

    // 8. GESTION DES NOTES PAR AJAX 
    const formNotes = document.getElementById('formNotes');
    if (formNotes) {
        const table = document.getElementById('tableEtudiants');        
        const infoExamen = document.getElementById('infoExamen');
        const message = document.getElementById('messageNotes');
        const idExamenInput = document.getElementById('idExamen');
        const btnAfficher = document.getElementById('btnAfficher');
        const btnEnregistrer = document.getElementById('btnEnregistrerNotes');

        if (!btnAfficher || !btnEnregistrer) {
            console.error('Bouton introuvable :', {
                btnAfficher,
                btnEnregistrer
            });
            return;
        }

        // AFFICHER LES ETUDIANTS
        formNotes.addEventListener('submit', function (e) {
            e.preventDefault();
            btnAfficher.disabled = true;
            btnAfficher.textContent = 'Chargement...';
            btnEnregistrer.disabled = true;
            message.textContent = '';
            fetch(formNotes.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(formNotes)
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Erreur de chargement.');
                }
                return data;
            })
            .then(data => {
                table.innerHTML = '';
                idExamenInput.value = data.examen.IDEXAMEN;
                infoExamen.textContent =
                    'Examen N° : ' + data.examen.IDEXAMEN;
                if (data.etudiants.length === 0) {
                    table.innerHTML = `<tr>
                                            <td colspan="5" class="text-center">
                                                Aucun étudiant trouvé.
                                            </td>
                                        </tr>`;
                    return;
                }
                data.etudiants.forEach(etudiant => {
                    const tr = document.createElement('tr');

                    const tdNumero = document.createElement('td');
                    tdNumero.textContent = etudiant.IDETUD;

                    const tdNom = document.createElement('td');
                    tdNom.textContent = etudiant.nom;

                    const tdPrenom = document.createElement('td');
                    tdPrenom.textContent = etudiant.prenom;

                    const tdCne = document.createElement('td');
                    tdCne.textContent = etudiant.cne;

                    const tdNote = document.createElement('td');

                    const inputNote = document.createElement('input');
                    inputNote.type = 'number';
                    inputNote.className = 'form-control';
                    inputNote.min = '0';
                    inputNote.max = '20';
                    inputNote.step = '0.25';

                    inputNote.name = `notes[${etudiant.IDETUD}]`;

                    inputNote.value = etudiant.valeur !== null
                        ? etudiant.valeur
                        : '';

                    inputNote.placeholder = 'Note /20';

                    tdNote.appendChild(inputNote);

                    tr.append(
                        tdNumero,
                        tdNom,
                        tdPrenom,
                        tdCne,
                        tdNote
                    );

                    table.appendChild(tr);
                });
                btnEnregistrer.disabled = false;
                message.textContent =
                    'Vous pouvez maintenant saisir ou modifier les notes.';
            })
            .catch(error => {
                console.error(error);
                message.textContent = error.message;
            })
            .finally(() => {
                btnAfficher.disabled = false;
                btnAfficher.textContent = 'Afficher les étudiants';
            });
        });

        // ENREGISTRER LES NOTES
        btnEnregistrer.addEventListener('click', function () {
            const inputs = table.querySelectorAll(
                'input[name^="notes["]'
            );
            let auMoinsUneNote = false;
            let notesValides = true;
            inputs.forEach(input => {
                if (input.value.trim() !== '') {
                    auMoinsUneNote = true;
                }
                if (
                    input.value.trim() !== '' &&
                    (
                        Number(input.value) < 0 ||
                        Number(input.value) > 20
                    )
                ) {
                    input.setCustomValidity(
                        'La note doit être comprise entre 0 et 20.'
                    );
                    notesValides = false;
                } else {
                    input.setCustomValidity('');
                }
            });
            if (!auMoinsUneNote) {
                message.textContent =
                    'Veuillez saisir au moins une note.';
                return;
            }
            if (!notesValides) {
                table.querySelector('input:invalid')?.reportValidity();
                return;
            }
            btnEnregistrer.disabled = true;
            btnEnregistrer.textContent = 'Enregistrement...';

            message.textContent = '';
            fetch(formNotes.dataset.saveUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(formNotes)
            })
            .then(async response => {
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.message || 'Erreur lors de l’enregistrement.'
                    );
                }

                return data;
            })
            .then(data => {
                message.textContent = data.message;
                message.style.color = 'green';
            })
            .catch(error => {
                console.error(error);
                message.textContent = error.message;
                message.style.color = 'red';
            })
            .finally(() => {
                btnEnregistrer.disabled = false;
                btnEnregistrer.textContent = 'Enregistrer les notes';
            });
        });
    }

});
