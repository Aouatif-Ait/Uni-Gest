<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Etudiant;
use App\Models\Utilisateur;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnseignantController extends Controller{

    public function create(){
        // Afficher le formulaire d'inscription Enseignant
        return view('Enseignant.ajouter-enseignant');
    }

    public function store(Request $request){

        // 1. Validation des données
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:50'],
            'prenom' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:150', 'unique:Utilisateur,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'specialite' => ['required', 'string', 'max:100'],
            'grade' => ['required', 'string', 'max:50'],
        ]);

        // 2. Création des deux enregistrements
        $utilisateur = DB::transaction(function () use ($validated) {

            // Création du compte utilisateur
            $utilisateur = new Utilisateur();

            $utilisateur->nom = $validated['nom'];
            $utilisateur->prenom = $validated['prenom'];
            $utilisateur->email = $validated['email'];
            $utilisateur->mtpasse = $validated['password'];
            $utilisateur->role = 'Enseignant';

            $utilisateur->save();

            // Création du profil enseignant
            $enseignant = new Enseignant();

            $enseignant->specialite = $validated['specialite'];
            $enseignant->grade = $validated['grade'];
            $enseignant->encadrant_pfe = 'non';
            $enseignant->IDUSER = $utilisateur->IDUSER;

            $enseignant->save();

            return $utilisateur;
        });

        // 3. Connexion automatique
        Auth::login($utilisateur);

        $request->session()->regenerate();

        // 4. Redirection vers le dashboard
        return redirect()->route('enseignant');
    }

    // Récupérer l'enseignant connecté
    public function recupererenseignant(){
        // Utilisateur connecté
        $utilisateur = auth()->user();

        // Profil enseignant
        $enseignant = $utilisateur->enseignant;

        abort_if( !$enseignant, 403, 'Profil enseignant introuvable.' );

        return $enseignant;
    }

    public function index(){

        $enseignant = $this->recupererenseignant();
        // Cours de l'enseignant
        $cours = $enseignant->cours()
            ->orderByDesc('date_pub')
            ->get();

        //les 5 dérirnrs cours
         $cours5 = $enseignant->cours()
            ->orderByDesc('date_pub')
            ->limit(5)
            ->get();

        // PFE encadrés par l'enseignant
        $pfes = $enseignant->pfes()
            ->with('sujet')
            ->orderByDesc('date_affec')
            ->get();

        // Etudiants encadrés par cet enseignant
        $etudiants = Etudiant::with([
            'utilisateur',
            'pfe.sujet',
            'inscriptions.filiere',
            'inscriptions.annee',
        ])
        ->whereHas('pfe', function ($query) use ($enseignant) {
            $query->where('IDENSG', $enseignant->IDENSG);
        })
        ->get();

        // Statistiques par filière
        $statsParFiliere = DB::table('Inscription')
            ->join('Etudiant', 'Inscription.IDETUD', '=', 'Etudiant.IDETUD')
            ->join('Filiere', 'Inscription.IDFILIERE', '=', 'Filiere.IDFILIERE')
            ->join('Pfe', 'Etudiant.IDPFE', '=', 'Pfe.IDPFE')
            ->where('Pfe.IDENSG', $enseignant->IDENSG)
            ->select(
                'Filiere.nom',
                DB::raw('COUNT(DISTINCT Etudiant.IDETUD) as total')
            )
            ->groupBy('Filiere.IDFILIERE', 'Filiere.nom')
            ->orderBy('Filiere.nom')
            ->get();

        // Statistiques générales
        $stats = [
            'cours' => $cours->count(),
            'pfe' => $pfes->count(),
            'documents' => $cours
                ->whereNotNull('chemin_fichier')
                ->count(),
            'etudiants' => $etudiants->count(),
        ];

        return view(
            'Enseignant.enseignant',
            compact(
                'enseignant',
                'cours',
                'cours5',
                'pfes',
                'etudiants',
                'stats',
                'statsParFiliere'
            )
        );   
    }

    public function mesModules(){
        $enseignant = $this->recupererenseignant();

        // Les modules affectés à l'enseignant
        $modules = DB::table('Affectation')
            ->join('Module', 'Affectation.IDMODULE', '=', 'Module.IDMODULE')
            ->join('Anneeuniv', 'Affectation.IDANNEE', '=', 'Anneeuniv.IDANNEE')
            ->join('Semestre', 'Module.IDSEMESTRE', '=', 'Semestre.IDSEMESTRE')
            ->join('Filiere', 'Semestre.IDFILIERE', '=', 'Filiere.IDFILIERE')
            ->where('Affectation.IDENSG', $enseignant->IDENSG)
            ->select(
                'Module.IDMODULE',
                'Module.code_mdl',
                'Module.intitule',
                'Module.volume_horaire',
                'Semestre.libelle as semestre',
                'Filiere.nom as filiere',
                'Anneeuniv.libelle as annee'
            )
            ->orderByDesc('Anneeuniv.IDANNEE')
            ->get();

        return view('Enseignant.modules', compact('modules'));
    }

    public function mesfilieresmodules(){
        $enseignant = $this->recupererenseignant();

        $filieres = DB::table('Affectation')
            ->join('Module', 'Affectation.IDMODULE', '=', 'Module.IDMODULE')
            ->join('Anneeuniv', 'Affectation.IDANNEE', '=', 'Anneeuniv.IDANNEE')
            ->join('Semestre', 'Module.IDSEMESTRE', '=', 'Semestre.IDSEMESTRE')
            ->join('Filiere', 'Semestre.IDFILIERE', '=', 'Filiere.IDFILIERE')

            ->select(
                'Filiere.nom',
                'Module.IDMODULE',
                'Module.intitule',
                'Semestre.libelle as semestre',
                'Anneeuniv.IDANNEE',
                'Anneeuniv.libelle as année_universetaire'
            )

            ->where('Affectation.IDENSG', $enseignant->IDENSG)
            ->orderByDesc('Anneeuniv.IDANNEE')
            ->orderBy('Filiere.nom')
            ->get()
            ->groupBy('Filiere.nom');

        return view('Enseignant.Filieresmodules', compact('filieres'));
    }
    
public function mesetudiantsModule(int $id, int $annee){
    $enseignant = $this->recupererenseignant();

    // Récupérer les étudiants du module
    // pour une année universitaire précise
    $modules = DB::table('Inscription')
        ->join('Etudiant', 'Inscription.IDETUD', '=', 'Etudiant.IDETUD')
        ->join('Utilisateur', 'Etudiant.IDUSER', '=', 'Utilisateur.IDUSER')
        ->join('Anneeuniv', 'Inscription.IDANNEE', '=', 'Anneeuniv.IDANNEE')
        ->join('Filiere', 'Inscription.IDFILIERE', '=', 'Filiere.IDFILIERE')
        ->join('Semestre', 'Filiere.IDFILIERE', '=', 'Semestre.IDFILIERE')
        ->join('Module', 'Semestre.IDSEMESTRE', '=', 'Module.IDSEMESTRE')

        ->join('Affectation', function ($join) {
            $join->on('Affectation.IDMODULE', '=', 'Module.IDMODULE')
                 ->on('Affectation.IDANNEE', '=', 'Inscription.IDANNEE');
        })
        ->select(
            'Utilisateur.nom',
            'Utilisateur.prenom',
            'Etudiant.cne',
            'Anneeuniv.libelle as annee'
        )
        ->where('Module.IDMODULE', $id)
        ->where('Inscription.IDANNEE', $annee)
        ->where('Affectation.IDENSG', $enseignant->IDENSG)

        ->orderBy('Utilisateur.nom')
        ->distinct()
        ->get();

    return view('Enseignant.Modulesetudiants', compact('modules'));
}

    public function gestionDesNotes(){
        $enseignant = $this->recupererenseignant();

        $filieres = DB::table('Affectation')
            ->join('Module', 'Affectation.IDMODULE', '=', 'Module.IDMODULE')
            ->join('Anneeuniv', 'Affectation.IDANNEE', '=', 'Anneeuniv.IDANNEE')
            ->join('Semestre', 'Module.IDSEMESTRE', '=', 'Semestre.IDSEMESTRE')
            ->join('Filiere', 'Semestre.IDFILIERE', '=', 'Filiere.IDFILIERE')

            ->select(
                'Filiere.nom',
                'Module.IDMODULE',
                'Module.intitule',
                'Semestre.libelle as semestre',
                'Anneeuniv.IDANNEE',
                'Anneeuniv.libelle as année_universetaire'
            )

            ->where('Affectation.IDENSG', $enseignant->IDENSG)
            ->orderByDesc('Anneeuniv.IDANNEE')
            ->orderBy('Filiere.nom')
            ->get()
            ->groupBy('Filiere.nom');

        return view('Enseignant.FilieresmodulesNotes', compact('filieres'));
    }
    
}
