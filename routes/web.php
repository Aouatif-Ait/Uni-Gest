<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EtudiantController;

// Inscription libre Enseignant
Route::get('/register/enseignant', [
    EnseignantController::class,
    'create'
])->name('enseignant.create');

Route::post('/register/enseignant', [
    EnseignantController::class,
    'store'
])->name('enseignant.store');

// Inscription libre Etudiant
Route::get('/register/etudiant', [EtudiantController::class, 'create'])
    ->name('etudiant.create');

Route::post('/register/etudiant', [EtudiantController::class, 'store'])
    ->name('etudiant.store');


Route::get('/', function () {

    // si l'utilisateur pas authentifié
    if (!Auth::check()) {
        return view('accueil');
    }

    // l'utilisateur enregistré
    $utilisateur = Auth::user();

    // redirection selon le role
    switch (trim($utilisateur->role)) {

        case 'Administrateur':
            return redirect('/admin');

        case 'Enseignant':
            return redirect('/enseignant');

        case 'Etudiant':
            return redirect('/etudiant');

        default:
            Auth::logout();

            return redirect('/login');
    }
})->name('accueil');

// Authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Espace Administrateur
Route::middleware(['auth', 'role:Administrateur'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/', function () {
            return view('Administrateur.administrateur');
        })->name('admin');

    });

// Espace Enseignant
Route::middleware(['auth', 'role:Enseignant'])
    ->prefix('enseignant')
    ->group(function () {
        Route::get('/', [EnseignantController::class, 'index'])
            ->name('enseignant');
    });

// Espace Etudiant
Route::middleware(['auth', 'role:Etudiant'])
    ->prefix('etudiant')
    ->group(function () {

        Route::get('/', function () {
            return view('Etudiant.etudiant');
        })->name('etudiant');

    });

Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/profil', [ProfileController::class, 'profil'])
        ->name('profil');

    Route::put('/profil', [ProfileController::class, 'update'])
        ->name('profil.update');

    // Modules affectés à l'enseignant
    Route::get('/enseignant/mes-modules', [
        EnseignantController::class,
        'mesModules'
    ])->name('Enseignant.modules');

    //Les modules de chaque filière
    Route::get('/enseignant/Mes_filieresModules', [
        EnseignantController::class,
        'mesfilieresmodules'
    ])->name('Enseignant.Filieresmodules');

    //La listes des étudiants pour chaque modules
    Route::get('/enseignant/Mes_etudiantsModule/{id}/{annee}', [
        EnseignantController::class,
        'mesetudiantsModule'
    ])->name('Enseignant.Modulesetudiants');
    
    //Les modules de chaque filière
    Route::get('/enseignant/notes', [
        EnseignantController::class,
        'getFilieres'
    ])->name('Enseignant.gestionNotes');

    Route::get(
    '/enseignant/semestres/{idFiliere}',[
        EnseignantController::class, 
        'getSemestres'
    ])->name('Enseignant.semestres');

    Route::get('/enseignant/modules/{idSemestre}', [
        EnseignantController::class,
        'getModules'
    ])->name('Enseignant.getModules');

    Route::post('/enseignant/notes/etudiants', [
        EnseignantController::class,
    'afficherEtudiants'
    ])->name('Enseignant.afficherEtudiants');

    Route::post('/enseignant/notes/enregistrer', [
        EnseignantController::class,
    'enregistrerNotes'
    ])->name('Enseignant.enregistrerNotes');

    Route::get('/enseignant/moyennes', [
        EnseignantController::class,
        'moyennes'
    ])->name('Enseignant.moyennes');

    Route::post('/enseignant/moyennes/rechercher', [
        EnseignantController::class,
        'rechercherMoyennes'
    ])->name('Enseignant.rechercherMoyennes');
});

/*use Illuminate\Http\Request;

Route::get('/logout', function (Request $request) {

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');

});*/