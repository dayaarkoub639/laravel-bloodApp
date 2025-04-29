<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Pseudo;
use App\Models\Don;
use App\Models\Personne;
use Illuminate\Validation\Rule;

class RecoverPhoneNumberController extends Controller
{

   
  /**
     * Récupérer les pseudos des utilisateurs.
     */
    public function getPseudos($phone)
    {
        //get id personne  
        $idPersonne = Personne::where('numeroTlp1', $phone)->pluck('idUser')->first();
        //get id   user
        $pseudoCorrect = User::where('keyIdUser', $idPersonne)->pluck('pseudo')->first(); 
        $pseudos = Pseudo::inRandomOrder()->limit(3)->pluck('name');
        $pseudos->push($pseudoCorrect);  
        $pseudos = $pseudos->shuffle();
        return response()->json(['pseudos' => $pseudos ], 200);
    }

    /**
     * Récupérer les derniers dons effectués.
     */
    public function getLastDons($phone)
    {
        
       $randomDates = self::generateRandomDates();
          //get id personne  
          $idPersonne = Personne::where('numeroTlp1', $phone)->pluck('idUser')->first();
         //get id   user
         $userId = User::where('keyIdUser', $idPersonne)->pluck('id')->first(); 
       $hasDonated = Don::where('idDonneur', $userId)->exists();
       if(!$hasDonated){
        $randomDates[] = "Jamais fait de don";

       }else{
        //genere janvier 2025 depuis bdd 
        //  step 01
       // get date last don
        $lastDon = Don::where('idDonneur', $userId)->latest('date')->first();
        //format to janvier 2025
        $formattedDate = Carbon::parse( $lastDon->date)->translatedFormat('F Y');
        $randomDates[] = $formattedDate;
 
       }
      
        $randomDates = collect($randomDates)->shuffle();
        return response()->json(['last_dons' => $randomDates], 200);
    }

   

    static function generateRandomDates() {
        $currentYear = date("Y");
        $minYear = $currentYear - 4; // Année minimale = (année actuelle - 4)
        
        $months = [
            "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", 
            "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
        ];
        
        $dates = [];
    
        for ($i = 0; $i < 3; $i++) {
            $randomYear = rand($minYear, $currentYear); // Année entre (année actuelle - 4) et (année actuelle)
            $randomMonth = $months[array_rand($months)]; // Mois aléatoire
            $dates[] = "$randomMonth $randomYear";
        }
    
        return $dates;
    }

    public function recoverValidateInfo(Request $request)
    {
        try {
            
        $personne = Personne::where('idUser',$request->idPersonne)->first();
 
        $request->validate([
            'idPersonne' => 'required',
            'nouveauNumero' => [
                'required',
                'string',
                Rule::unique('personnes', 'numeroTlp1')->ignore($personne->idUser, 'idUser'),
            ],
        ]);

        $personne->numeroTlp1 = $request->nouveauNumero;
        $personne->save();

        return response()->json([
            'message' => 'Numéro de téléphone mis à jour avec succès.',
            'data' => $personne
        ]);

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            \DB::rollBack();

            // Log l'erreur pour le débogage
            Log::error('Erreur lors de la validation de l\'utilisateur : ' . $e->getMessage());

            // Retourner une réponse d'erreur
            return response()->json([
                'message' => 'Une erreur est survenue lors de la validation de l\'utilisateur.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function validateInfoRecover(Request $request) 
    {

        try {
            // Valider les données d'entrée
            $validated = $request->validate([
                'phone' =>  'required', 
                'groupage' => 'string',
                'dateNaissance' => 'string',
                'lastDon'=>'string',
                'pseudo'=>'string',
                 
            ]);


            //verifer table personne date naiss et groupage
             
        $personne = Personne::where('numeroTlp1', $validated['phone'])->first();

        // Vérification si la personne existe
        if (!$personne) {
            return response()->json(['message' => 'Personne non trouvée', 'ok' => false], 404);
        }
        $idGroupage = \App\Models\Groupage::where('type', $validated['groupage'])->value('id');

        // Vérification du groupage et de la date de naissance
        if ($personne->idGroupage !== $idGroupage) {
            return response()->json(['message' => 'Groupage incorrect', 'ok' => false], 400);
        }

        if ($personne->dateDeNess !== $request->dateNaissance) {
            return response()->json(['message' => 'Date de naissance incorrecte', 'ok' => false], 400);
        }
            $user = User::where('keyIdUser', $personne->idUser)->first(); 
    
            // Vérifier si le pseudo correspond
            if ($user->pseudo !== $request->pseudo) {
                return response()->json(['message' => 'Pseudo incorrect', 'ok' => false], 400);
            }
            //verfiier lastdon --> table dons

             // get date last don
        $lastDon = Don::where('idDonneur', $user->id)->latest('date')->first();
        if($lastDon){
                //format to janvier 2025
                $formattedDate = Carbon::parse( $lastDon->date)->translatedFormat('F Y');
                // Vérifier si le pseudo correspond

                    if (strtolower($formattedDate) !== strtolower($request->lastDon)) {
                        return response()->json(['message' => 'lastDon incorrect', 'ok' => false,], 400);
                    }

                    return response()->json([
                        'message' => 'Utilisateur validé avec succès.',
                        'ok' => true,
                    ], 200); //format to janvier 2025
                    $formattedDate = Carbon::parse( $lastDon->date)->translatedFormat('F Y');
                    // Vérifier si le pseudo correspond

                        if (strtolower($formattedDate) !== strtolower($request->lastDon)) {
                            return response()->json(['message' => 'lastDon incorrect', 'ok' => false,], 400);
                        }

                        return response()->json([
                            'message' => 'Utilisateur validé avec succès.',
                            'ok' => true,
                        ], 200);
        }else{
            if ("jamais fait de don" !== strtolower($request->lastDon)) {
                return response()->json(['message' => 'lastDon incorrect', 'ok' => false,], 400);
                
            }else{

                return response()->json([
                    'message' => 'Utilisateur validé avec succès.',
                    'ok' => true,
                ], 200);
            }
        }
       
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            \DB::rollBack();

            // Log l'erreur pour le débogage
            Log::error('Erreur lors de la validation de l\'utilisateur : ' . $e->getMessage());

            // Retourner une réponse d'erreur
            return response()->json([
                'message' => 'Une erreur est survenue lors de la validation de l\'utilisateur.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
  
    
}
