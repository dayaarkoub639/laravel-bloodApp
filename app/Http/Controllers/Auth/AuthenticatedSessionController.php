<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'string'],
                'password' => ['required', 'string'],
            ]);
             // Vérifier si l'utilisateur existe
            $personne = \App\Models\Personne::where('numeroTlp1', $credentials['email'])->where('typePersonne','admin')->first();

            if (!$personne) {
                return back()->withErrors(['email' => 'Ce numéro de téléphone n\'existe pas.']);
            }
        // Vérifier si le mot de passe est correct
        if (!Hash::check($credentials['password'], $personne->motDePasse)) {
            return back()->withErrors(['password' => 'Le mot de passe est incorrect.']);
        }
            // Connecter l'utilisateur manuellement
            Auth::login($personne);

            // Régénérer la session pour éviter les attaques de fixation de session
            $request->session()->regenerate();

            return redirect()->intended(route('index'));
 
         
         
        } catch (ValidationException $e) {
           
            return back()->withErrors($e->errors())->withInput();
        }
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout(); // Déconnecter l'utilisateur

    $request->session()->invalidate(); // Invalider la session
    $request->session()->regenerateToken(); // Régénérer le token CSRF

    return redirect('/login'); // Rediriger vers la page de connexion
    }
}
