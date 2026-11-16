<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // Afficher le profil
    public function profil()
    {
        $utilisateur = Auth::user();

        return view('Profil.profil', compact('utilisateur'));
    }

    // Modifier les informations du profil
    public function update(Request $request)
    {
        $utilisateur = Auth::user();

        $validated = $request->validate([

            'nom' => ['required', 'string', 'max:50'],

            'prenom' => ['required', 'string', 'max:50'],

            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('Utilisateur', 'email')
                    ->ignore($utilisateur->IDUSER, 'IDUSER'),
            ],

            // Mot de passe : facultatif
            'current_password' => [
                'required_with:nouveau_mot_de_passe',
            ],

            'nouveau_mot_de_passe' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        // Vérifier l'ancien mot de passe si changement demandé
        if (!empty($validated['nouveau_mot_de_passe'])) {

            if (!Hash::check(
                $request->current_password,
                $utilisateur->mtpasse
            )) {
                return back()
                    ->withErrors([
                        'current_password' => 'Ancien mot de passe incorrect.',
                    ])
                    ->withInput();
            }

            $utilisateur->mtpasse = $validated['nouveau_mot_de_passe'];
        }

        // Modifier les informations personnelles
        $utilisateur->nom = $validated['nom'];
        $utilisateur->prenom = $validated['prenom'];
        $utilisateur->email = $validated['email'];

        $utilisateur->save();

        return back()->with(
            'success',
            'Votre profil a été modifié avec succès.'
        );
    }
}

