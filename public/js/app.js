
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
});
