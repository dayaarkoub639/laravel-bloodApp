<?php

namespace App\Http\Controllers\API\Auth;

use App\Constants\AuthConstants;

use App\Http\Controllers\Controller;

use App\Http\Traits\HttpResponses;
use App\Models\User;
use App\Models\Personne;
use App\Models\Otp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
class RegisterController extends Controller
{
    use HttpResponses;
    public static function generateCustomId($wilaya_domicile_id, $gender)
    {

        $part3 = str_pad(self::getNextSequence() , 5, '0', STR_PAD_LEFT); // Numéro séquentiel sur 5 digits
        $part2 = str_pad($wilaya_domicile_id, 2, '0', STR_PAD_LEFT); // De 01 à 58


        return "{$gender}{$part2}{$part3}";
    }

    public static function getNextSequence()
    {
       
       // $lastRecord = Personne::orderBy('idUser', 'desc')->noCache()->first();
        // Si vous avez un champ timestamp
$lastRecord = Personne::latest('created_at')->first();
        $lastRecord= $lastRecord->fresh();
        if (!$lastRecord) {
            return 1;
        }

        $lastId = substr($lastRecord->idUser, -5); // Extraire les 5 derniers chiffres
        return (int) $lastId + 1;
    }
    public function checkPhoneExists(Request $request)
    {
        try {
            $request->validate([
                'numeroTlp1' => 'nullable|string|max:15',
            ]);

            $exists = Personne::where('numeroTlp1', $request->numeroTlp1)->exists();

            return response()->json([
                'exists' => $exists
            ]);

        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            Log::error('Erreur   : ' . $e->getMessage());

            // Retourner une réponse d'erreur
            return response()->json([
                'message' => 'Une erreur est survenue  .',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function register(Request $request): JsonResponse
    {

        try {
            // Valider les données d'entrée
            $validated = $request->validate([

                'numeroTlp1' => 'nullable|string|max:15|unique:personnes,numeroTlp1',
                'numeroTlp2' => 'nullable|string|max:15',

                'motDePasse' => 'required|string|min:6',
                'dateDeNess' => 'nullable|date',
               // 'nom' => 'nullable|string|max:255',
                //'prenom' => 'nullable|string|max:255',
                'gender' => 'required|integer',
                //'idPhenotype' => 'nullable|integer',
                'idGroupage' => 'nullable|string',
                'wilaya_prof_id' => 'nullable|integer',
                'commune_prof_id' => 'nullable|integer',
                'wilaya_domicile_id' => 'nullable|integer',
                'commune_domicile_id' => 'nullable|integer',
                'pseudo' => 'required|string|min:4',


            ]);
            $idCustom = self::generateCustomId($validated['wilaya_domicile_id'], $validated['gender']) ;
          
            // Commencer une transaction
            \DB::beginTransaction();

            $idGroupage = \App\Models\Groupage::where('type', $validated['idGroupage'])->value('id');
            $accept_sendsms=0;
            if(isset($request->accept_sendsms)){
                $accept_sendsms=$request->accept_sendsms;
            }
            // Créer une entrée dans la table `personne`
            $personne = Personne::create([
                'idUser'=>$idCustom,
                //'NIN' => $validated['NIN'],
                'numeroTlp1' => $validated['numeroTlp1'],
                'numeroTlp2' => $validated['numeroTlp2'],
                'accept_sendsms' =>  $accept_sendsms,
                'motDePasse' => Hash::make($validated['motDePasse']),
                'dateDeNess' => $validated['dateDeNess'],
                 'nom' => $request->nom ,
                'prenom' => $request->prenom ,
                'gender' => $validated['gender'],
                'typePersonne' => "user",
                //'idPhenotype' => $validated['idPhenotype'],
                'idGroupage' => $idGroupage,
                'wilaya_prof_id' => $validated['wilaya_prof_id'],
                'commune_prof_id' => $validated['commune_prof_id'],
                'wilaya_domicile_id' => $validated['wilaya_domicile_id'],
                'commune_domicile_id' => $validated['commune_domicile_id'],



            ]);

            // Créer une entrée dans la table `users`
            $user = User::create([
                'keyIdUser' => $idCustom,
                'pseudo' => $validated['pseudo'],
            ]);
              

              // Vérifier si la personne existe et si le mot de passe est correct
              if (!$personne || !Hash::check($validated['motDePasse'], $personne->motDePasse)) {
                  return response()->json([
                      'message' => 'Identifiants invalides.',
                  ], 401); // Unauthorized
              }
  
              // Générer un token d'authentification
              $token = $personne->createToken('auth_token')->plainTextToken;

            // Confirmer la transaction
            \DB::commit();

            return response()->json([
                'message' => 'Utilisateur créé avec succès.',
                'token' => $token,
                'user' => $user,
                'personne' => $personne,
            ], 201);
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            \DB::rollBack();

            // Log l'erreur pour le débogage
            Log::error('Erreur lors de la création de l\'utilisateur : ' . $e->getMessage());

            // Retourner une réponse d'erreur
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de l\'utilisateur.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
