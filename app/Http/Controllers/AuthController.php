<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller{
    public function showLogin(){
        return view('Utilisateur.login');
    }

    public function login(Request $request){
        // Validation des données
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Saisissez votre adresse e-mail.',
            'email.email' => 'Cette adresse e-mail n\'est pas valide.',
            'password.required' => 'Saisissez votre mot de passe.',
        ]);

        // Vérification des identifiants
        if (Auth::attempt($credentials)) {

            // Régénérer la session
            $request->session()->regenerate();

            // Récupérer l'utilisateur connecté
            $utilisateur = Auth::user();

            // Redirection selon le rôle
            switch (trim($utilisateur->role)) {

                case 'Administrateur':
                    return redirect('/admin');

                case 'Enseignant':
                    return redirect('/enseignant');

                case 'Etudiant':
                    return redirect('/etudiant');

                default:
                    Auth::logout();

                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect('/login')
                        ->withErrors([
                            'email' => 'Rôle utilisateur non reconnu.'
                        ]);
            }
        }

        // Identifiants incorrects
        return back()
            ->withErrors([
                'email' => 'Adresse e-mail ou mot de passe incorrect.'
            ])
            ->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
