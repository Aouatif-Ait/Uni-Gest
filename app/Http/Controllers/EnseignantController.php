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
        // Récupérer l'enseignant connecté
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
        // Récupérer l'enseignant connecté
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
        // Récupérer l'enseignant connecté
        $enseignant = $this->recupererenseignant();

        //les filières affectées à l'enseignant
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
        // Récupérer l'enseignant connecté
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

    public function recupererannee(){
        //récupirer l'annèe universitaire actuelle
        $annee = DB::table('Anneeuniv')
            ->whereDate('date_debut', '<=', now())
            ->whereDate('date_fin', '>=', now())
            ->first();

        // Vérifier l'existence de l'année
        abort_if(!$annee, 404, 'Aucune année universitaire active.');

        return $annee;
    }

    public function getFilieres(){
        // Récupérer l'enseignant connecté
        $enseignant = $this->recupererenseignant();
        
        //récupirer l'annèe universitaire actuelle
        $annee = $this->recupererannee();

        //récupirer les filères
        $filieres = DB::table('Affectation')
            ->join('Module', 'Affectation.IDMODULE', '=', 'Module.IDMODULE')
            ->join('Semestre', 'Module.IDSEMESTRE', '=', 'Semestre.IDSEMESTRE')
            ->join('Filiere', 'Semestre.IDFILIERE', '=', 'Filiere.IDFILIERE')
            ->where('Affectation.IDENSG', $enseignant->IDENSG)
            ->where('Affectation.IDANNEE', $annee->IDANNEE)
            ->select(
                'Filiere.IDFILIERE',
                'Filiere.nom'
            )
            ->distinct()
            ->orderBy('Filiere.nom')
            ->get();

        return view('Enseignant.gestionNotes', compact('filieres'));
    }
    
    public function getSemestres(int $idFiliere){
        // Récupérer l'enseignant connecté
        $enseignant = $this->recupererenseignant();

        //récupirer l'annèe universitaire actuelle
        $annee = $this->recupererannee();

        //Récupérer les semestres
        $semestres = DB::table('Affectation')
            ->join('Module', 'Affectation.IDMODULE', '=', 'Module.IDMODULE')
            ->join('Semestre', 'Module.IDSEMESTRE', '=', 'Semestre.IDSEMESTRE')
            ->where('Affectation.IDENSG', $enseignant->IDENSG)
            ->where('Affectation.IDANNEE', $annee->IDANNEE)
            ->where('Semestre.IDFILIERE', $idFiliere)
            ->select('Semestre.IDSEMESTRE', 'Semestre.libelle')
            ->distinct()
            ->orderBy('Semestre.libelle')
            ->get();

        return response()->json($semestres);
    }

    public function getModules(int $idSemestre){
        // Récupérer l'enseignant connecté
        $enseignant = $this->recupererenseignant();

        //récupirer l'annèe universitaire actuelle
        $annee = $this->recupererannee();

        // Récupérer les modules du semestre choisi
        $modules = DB::table('Affectation')
            ->join('Module', 'Affectation.IDMODULE', '=', 'Module.IDMODULE')
            ->where('Affectation.IDENSG', $enseignant->IDENSG)
            ->where('Affectation.IDANNEE', $annee->IDANNEE)
            ->where('Module.IDSEMESTRE', $idSemestre)
            ->select(
                'Module.IDMODULE',
                'Module.intitule'
            )
            ->distinct()
            ->orderBy('Module.intitule')
            ->get();

        return response()->json($modules);
    }

    public function afficherEtudiants(Request $request){
        // Récupérer l'enseignant connecté
        $enseignant = $this->recupererenseignant();

        // 2. Valider les sélections
        $request->validate([
            'idFiliere' => 'required|integer',
            'idSemestre' => 'required|integer',
            'idModule' => 'required|integer',
        ]);

        //récupirer l'annèe universitaire actuelle
        $annee = $this->recupererannee();

        // 4. Vérifier que le module appartient à l'enseignant et récupérer son examen
        $examen = DB::table('Affectation as A')
            ->join('Module as M', 'A.IDMODULE', '=', 'M.IDMODULE')
            ->join('Semestre as S', 'M.IDSEMESTRE', '=', 'S.IDSEMESTRE')
            ->join('Examen as E', 'M.IDEXAMEN', '=', 'E.IDEXAMEN')
            ->join('Sessionexamen as SX', 'E.IDSX', '=', 'SX.IDSX')
            ->where('A.IDENSG', $enseignant->IDENSG)
            ->where('A.IDANNEE', $annee->IDANNEE)
            ->where('M.IDMODULE', $request->idModule)
            ->where('S.IDSEMESTRE', $request->idSemestre)
            ->where('S.IDFILIERE', $request->idFiliere)
            ->where('SX.IDANNEE', $annee->IDANNEE)
            ->where('SX.IDSEMESTRE', $request->idSemestre)
            ->select('E.IDEXAMEN')
            ->first();

        if (!$examen) {
            return back()
                ->withInput()
                ->with('error', 'Aucun examen trouvé pour ce module.');
        }

        // 5. Récupérer les étudiants convoqués à cet examen et inscrits dans la filière pendant l'année actuelle
        $etudiants = DB::table('Convocation as C')
                ->join('Etudiant as ET', 'C.IDETUD', '=', 'ET.IDETUD')
                ->join('Utilisateur as U', 'ET.IDUSER', '=', 'U.IDUSER')
                ->join('Inscription as I', function ($join) use ($request, $annee) {
                    $join->on('I.IDETUD', '=', 'ET.IDETUD')
                        ->where('I.IDFILIERE', '=', $request->idFiliere)
                        ->where('I.IDANNEE', '=', $annee->IDANNEE);
                })
                ->leftJoin('NoteEtudiant as NE', function ($join) use ($examen) {
                    $join->on('NE.IDETUD', '=', 'ET.IDETUD')
                        ->where('NE.IDEXAMEN', '=', $examen->IDEXAMEN);
                })
                ->leftJoin('Note as N', 'NE.IDNOTE', '=', 'N.IDNOTE')
                ->where('C.IDEXAMEN', $examen->IDEXAMEN)
                ->select(
                    'ET.IDETUD as IDETUD',
                    'ET.cne as cne',
                    'U.nom as nom',
                    'U.prenom as prenom',
                    'NE.IDNOTE as IDNOTE',
                    'N.valeur as valeur'
                )
                ->orderBy('U.nom')
                ->orderBy('U.prenom')
                ->get();

        // 6. Retourner vers la même page
            $filieres = DB::table('Affectation')
                ->join('Module', 'Affectation.IDMODULE', '=', 'Module.IDMODULE')
                ->join('Semestre', 'Module.IDSEMESTRE', '=', 'Semestre.IDSEMESTRE')
                ->join('Filiere', 'Semestre.IDFILIERE', '=', 'Filiere.IDFILIERE')
                ->where('Affectation.IDENSG', $enseignant->IDENSG)
                ->where('Affectation.IDANNEE', $annee->IDANNEE)
                ->select('Filiere.IDFILIERE', 'Filiere.nom')
                ->distinct()
                ->orderBy('Filiere.nom')
                ->get();

            return response()->json([
                'etudiants' => $etudiants,
                'examen' => $examen
            ]);
    }

    public function enregistrerNotes(Request $request){
        // Récupérer l'enseignant connecté
        $enseignant = $this->recupererenseignant();

        //validation des données
        $validated = $request->validate([
            'idFiliere' => 'required|integer',
            'idSemestre' => 'required|integer',
            'idModule' => 'required|integer',
            'idExamen' => 'required|integer',
            'notes' => 'required|array',
            'notes.*' => 'nullable|numeric|min:0|max:20',
        ]);

        //récuperer l'annèe universitaire actuelle
        $annee = $this->recupererannee();

        if (!$annee) {
            return response()->json([
                'message' => 'Aucune année universitaire active.'
            ], 422);
        }

        //vérifier que l'examen appartient au module et que l'enseignant est affecté à ce module
        $examen = DB::table('Affectation as A')
            ->join('Module as M', 'A.IDMODULE', '=', 'M.IDMODULE')
            ->join('Semestre as S', 'M.IDSEMESTRE', '=', 'S.IDSEMESTRE')
            ->join('Examen as E', 'M.IDEXAMEN', '=', 'E.IDEXAMEN')
            ->join('Sessionexamen as SX', 'E.IDSX', '=', 'SX.IDSX')
            ->where('A.IDENSG', $enseignant->IDENSG)
            ->where('A.IDANNEE', $annee->IDANNEE)
            ->where('M.IDMODULE', $validated['idModule'])
            ->where('S.IDSEMESTRE', $validated['idSemestre'])
            ->where('S.IDFILIERE', $validated['idFiliere'])
            ->where('SX.IDANNEE', $annee->IDANNEE)
            ->where('SX.IDSEMESTRE', $validated['idSemestre'])
            ->where('E.IDEXAMEN', $validated['idExamen'])
            ->select('E.IDEXAMEN')
            ->first();

        if (!$examen) {
            return response()->json([
                'message' => 'Examen invalide ou non autorisé.'
            ], 403);
        }
        //préparer les notes non vides
        $notes = collect($validated['notes'])
            ->filter(fn ($valeur) => $valeur !== null && $valeur !== '')
            ->all();

        if (empty($notes)) {
            return response()->json([
                'message' => 'Veuillez saisir au moins une note.'
            ], 422);
        }
        //enregistrer les notes dans une transaction
        DB::transaction(function () use (
            $notes,
            $examen,
            $enseignant,
            $annee,
            $validated )
        {
            foreach ($notes as $idEtud => $valeur) {
                //vérifier que l'étudiant est inscrit et convoqué à cet examen
                $etudiantAutorise = DB::table('Convocation as C')
                    ->join('Inscription as I', 'C.IDETUD', '=', 'I.IDETUD')
                    ->where('C.IDEXAMEN', $examen->IDEXAMEN)
                    ->where('C.IDETUD', $idEtud)
                    ->where('I.IDFILIERE', $validated['idFiliere'])
                    ->where('I.IDANNEE', $annee->IDANNEE)
                    ->exists();

                if (!$etudiantAutorise) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                    'notes' => "L'étudiant ID {$idEtud} n'est pas autorisé pour cet examen."
                ]); }
                //chercher une note déjà enregistrée
                $noteEtudiant = DB::table('NoteEtudiant')
                    ->where('IDETUD', $idEtud)
                    ->where('IDEXAMEN', $examen->IDEXAMEN)
                    ->first();

                if ($noteEtudiant) {
                    //modifier la note existante
                    DB::table('Note')
                        ->where('IDNOTE', $noteEtudiant->IDNOTE)
                        ->update([
                            'valeur' => $valeur,
                            'IDENSG' => $enseignant->IDENSG,
                            'date_saisie' => now()->toDateString(),
                        ]);
                } else {
                    //créer une nouvelle note
                    $idNote = DB::table('Note')->insertGetId([
                        'valeur' => $valeur,
                        'IDENSG' => $enseignant->IDENSG,
                        'date_saisie' => now()->toDateString(),
                    ]);
                    //relier la note à l'étudiant et à l'examen
                    DB::table('NoteEtudiant')->insert([
                        'IDETUD' => $idEtud,
                        'IDEXAMEN' => $examen->IDEXAMEN,
                        'IDNOTE' => $idNote,
                    ]);
                }
            }
        });
        return response()->json([
            'success' => true,
            'message' => 'Les notes ont été enregistrées avec succès.'
        ]);
    }

    public function moyennes(){
    
        //recuperer les années universetaire
        $annees = DB::table('Anneeuniv')
        ->orderByDesc('date_debut')
        ->get();

        //recuperer les cne des etudiants
        $cne = DB::table('Etudiant')
        ->orderByDesc('cne')
        ->get(); 

        return view('Enseignant.moyennes', compact('annees', 'cne'));
    }

    public function rechercherMoyennes(Request $request){
        $request->validate([
            'cne' => 'required|string',
            'idAnnee' => 'required|integer|exists:Anneeuniv,IDANNEE',
        ]);

        // Liste des années universitaires
        $annees = DB::table('Anneeuniv')
            ->orderByDesc('date_debut')
            ->get();

        // Recherche de l'étudiant par CNE
        $etudiant = DB::table('Etudiant as ET')
            ->join('Utilisateur as U', 'ET.IDUSER', '=', 'U.IDUSER')
            ->where('ET.cne', $request->cne)
            ->select(
                'ET.IDETUD',
                'ET.cne',
                'U.nom',
                'U.prenom'
            )
            ->first();
        $resultats = [];
        $moyennesSemestres = [];
        $moyenneAnnuelle = null;
        $inscriptions = collect();
        $message = null;

        if (!$etudiant) {
            $message = 'Aucun étudiant trouvé avec ce CNE.';
        } else {

            // Filières auxquelles l'étudiant est inscrit durant l'année choisie
            $inscriptions = DB::table('Inscription as I')
                ->join('Filiere as F', 'I.IDFILIERE', '=', 'F.IDFILIERE')
                ->where('I.IDETUD', $etudiant->IDETUD)
                ->where('I.IDANNEE', $request->idAnnee)
                ->select(
                    'F.IDFILIERE',
                    'F.nom',
                    'F.code'
                )
                ->get();
            if ($inscriptions->isEmpty()) {
                $message = "Cet étudiant n'est pas inscrit pendant cette année universitaire.";
            }
            foreach ($inscriptions as $inscription) {
                // Tous les semestres de la filière
                $semestres = DB::table('Semestre')
                    ->where('IDFILIERE', $inscription->IDFILIERE)
                    ->orderBy('libelle')
                    ->get();
                $semestresResultats = [];
                foreach ($semestres as $semestre) {
                    // Tous les modules du semestre, sans filtrer par enseignant
                    $modules = DB::table('Module as M')
                        ->leftJoin('Examen as EX', 'M.IDEXAMEN', '=', 'EX.IDEXAMEN')
                        ->leftJoin('Sessionexamen as SX', function ($join) use ($request, $semestre) {
                            $join->on('EX.IDSX', '=', 'SX.IDSX')
                                ->where('SX.IDSEMESTRE', '=', $semestre->IDSEMESTRE)
                                ->where('SX.IDANNEE', '=', $request->idAnnee);
                        })
                        ->leftJoin('NoteEtudiant as NE', function ($join) use ($etudiant) {
                            $join->on('NE.IDEXAMEN', '=', 'EX.IDEXAMEN')
                                ->where('NE.IDETUD', '=', $etudiant->IDETUD)
                                ->whereNotNull('SX.IDSX');
                        })
                        ->leftJoin('Note as N', 'NE.IDNOTE', '=', 'N.IDNOTE')
                        ->where('M.IDSEMESTRE', $semestre->IDSEMESTRE)
                        ->select(
                            'M.IDMODULE',
                            'M.code_mdl',
                            'M.intitule',
                            'N.valeur as note'
                        )
                        ->orderBy('M.code_mdl')
                        ->get();
                    // Les modules sans note ne sont pas inclus dans le calcul
                    $notesSaisies = $modules->whereNotNull('note');

                    $moyenneSemestre = $notesSaisies->isNotEmpty()
                        ? round($notesSaisies->avg('note'), 2)
                        : null;

                    if ($moyenneSemestre !== null) {
                        $moyennesSemestres[] = $moyenneSemestre;
                    }
                    $semestresResultats[] = [
                        'semestre' => $semestre,
                        'modules' => $modules,
                        'moyenne' => $moyenneSemestre,
                    ];
                }
                $resultats[] = [
                    'filiere' => $inscription,
                    'semestres' => $semestresResultats,
                ];
            }
            // Moyenne générale = moyenne des moyennes des semestres disponibles
            if (count($moyennesSemestres) > 0) {
                $moyenneAnnuelle = round(
                    array_sum($moyennesSemestres) / count($moyennesSemestres),
                    2
                );
            }
        }
        return view('Enseignant.moyennes', compact(
            'annees',
            'etudiant',
            'inscriptions',
            'resultats',
            'moyenneAnnuelle',
            'message'
        ))->with('idAnnee', $request->idAnnee);
    }
}
