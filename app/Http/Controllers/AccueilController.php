<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AccueilController extends Controller
{
    public function index()
    {
        return view('accueil', [
    'stats' => [
        'etudiants'   => DB::table('Etudiant')->count(),
        'enseignants' => DB::table('Enseignant')->count(),
        'filieres'    => DB::table('Filiere')->count(),
        'pfe'         => DB::table('Sujetpfe')->count(),
    ],
]);
    }
}
