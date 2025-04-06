<?php

namespace App\Http\Controllers\API\Auth;

use App\Constants\AuthConstants;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Personne;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
class LoginController extends Controller
{
    use HttpResponses;

   
    public function login(Request $request): JsonResponse
    {
        try {
            // Valider les champs requis
            $validated = $request->validate([
                'numeroTlp1' => 'required|string',
                'motDePasse' => 'required|string',
            ]);

            // Rechercher la personne par numeroTlp1
            $personne = Personne::where('numeroTlp1', $validated['numeroTlp1'])->first();

            // Vérifier si la personne existe et si le mot de passe est correct
            if (!$personne || !Hash::check($validated['motDePasse'], $personne->motDePasse)) {
                return response()->json([
                    'message' => 'Identifiants invalides.',
                ], 401); // Unauthorized
            }

            // Générer un token d'authentification
            $token = $personne->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Connexion réussie.',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'idUser' => $personne->idUser,
                    'nom' => $personne->nom,
                    'prenom' => $personne->prenom,
                    'numeroTlp1' => $personne->numeroTlp1,
                    'typePersonne' => $personne->typePersonne,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gestion des erreurs de validation
            return response()->json([
                'message' => 'Erreur de validation.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Gestion des autres erreurs
            return response()->json([
                'message' => 'Une erreur est survenue lors de la connexion.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = auth()->user();

        $user->tokens()->delete();

        return $this->success([], AuthConstants::LOGOUT);
    }

    /**
     * @return JsonResponse
     */
    public function details(): JsonResponse
    {
        $user = auth()->user();

        return $this->success($user, '');
    }
}
