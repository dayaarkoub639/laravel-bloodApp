<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Personne;
use App\Events\BloodRequestEvent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Events\NewNotificationUserEvent;

class BloodRequestController extends Controller
{
    public function findNearbyDonors(Request $request)  {
        $request->validate([
            'groupage' => 'required|string',
            'currentUserPosition' => 'required|string',
            'idDemandeur' => 'required|integer',
        ]);

        // Extraire les coordonnées
        [$latitude, $longitude] = explode(',', $request->currentUserPosition);
        $dateLimite = Carbon::now()->subMonths(3);
        
        // Requête pour trouver les utilisateurs donneurs compatibles
        $donneurs = Personne:: where('idGroupage', $request->groupage)
            ->where('idUser', '!=', $request->idDemandeur)
            ->whereHas('dons', function ($query) use ($dateLimite) {
                $query->where('serologie', 0);
            })
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw(
                "*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * 
                cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * 
                sin( radians( latitude ) ) ) ) AS distance",
                [$latitude, $longitude, $latitude]
            )
            ->having('distance', '<', 20) // 20 km de rayon
            
            ->get();
            
            
                // Si aucun donneur trouvé
        if ($donneurs->isEmpty()) {
            return response()->json(['message' => 'Aucun donneur trouvé à proximité'], 404);
        }
        // Préparer les données pour l'événement
        $eventData = [
            'groupage' => $request->groupage,
            'position' => "{$latitude},{$longitude}",
            'message' => "Besoin urgent de sang du groupe id {$request->groupage} !",
            'donneurs' => $donneurs
        ];

    
        // Diffuser l'événement
        event(new BloodRequestEvent($eventData));
            // Si tu veux retourner avec distance triée, décommente et adapte ci-dessous :
        $donneurs = $donneurs->sortBy('distance')->values();
        foreach ($donneurs as $donneur) {
            $donneur->distance = $this->calculerDistance($latitude, $longitude, $donneur->latitude, $donneur->longitude);
            $donneur->pseudo = $donneur->user()->value("pseudo")?? "";

                // done récupérer la date la plus récente, puis comparer cette date avec la date actuelle. 
            
                $donneur->dons = $donneur->dons()->get() ?? "";
                // Vérifier si le donneur a des dons
            if ($donneur->dons->isNotEmpty()) {
                // Trier les dons par date décroissante et prendre le premier (le plus récent)
                $latestDon = $donneur->dons->sortByDesc('date')->first();
                
                // Convertir la date du dernier don en instance de Carbon
                $latestDate = Carbon::parse($latestDon->date);
                
                // Date actuelle
                $currentDate = Carbon::now();
                
                // Calculer la différence entre la date actuelle et la date du dernier don
                $diffInMonths = $latestDate->diffInMonths($currentDate);

                // Vérifier si le dernier don est dans les 3 derniers mois
                if ($diffInMonths > 3) {
                    // Ajouter le donneur à la liste des donneurs filtrés
                    $donneursFiltres[] = $donneur;
                }
            }
        }

     
        

        return response()->json([
            'success' => true,
            'donneurs' => $donneursFiltres

        ]);
    }

    // Fonction de calcul de distance entre 2 points géographiques
    private function calculerDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }



    public function updateLocation(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'idUser' => 'required',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Recherche de l'utilisateur
            $personne = Personne::where('idUser',$request->idUser)->first();

            if (!$personne) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Mise à jour des coordonnées
            $personne->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Location updated successfully',
                'data' => $personne
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function accepterDemande(Request $request){
         // Validation des données
         $validator = Validator::make($request->all(), [
            'idUser' => 'required',
            'idDemande' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $personne = Personne::where('idUser',$request->idUser)->first();
            $demande_id = $request->idDemande;
            
            
            $personne->demandes()->syncWithoutDetaching([
                $demande_id => ['date_acceptation' => now()]
            ]);

            // Créer la notification dans la base de données
            $notification = [
                'title' => 'Demande acceptée',
                'body' => 'qq un a accpeter une demande  de sang',
                'data' => ['idDemandeur' => $demande_id,
                'idCentreProche' => "todo",
                'idPersonne' => $request->idUser] // données supplémentaires
            ];

            // Déclencher l'événement
            event(new NewNotificationUserEvent($request->idUser, $notification));
            return response()->json([
                'success' => true,
                'message' => 'Demande accepté',
                'centreProche' => "todo",
                'demande_id' => $demande_id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
    public function updatePhone(Request $request){
        

       try {
        $personne = Personne::where('idUser',$request->idPersonne)->first();
 
        $request->validate([
            'idPersonne' => 'required',
            'numeroTlp1' => [
                'required',
                'string',
                Rule::unique('personnes', 'numeroTlp1')->ignore($personne->idUser, 'idUser'),
            ],
        ]);

        $personne->numeroTlp1 = $request->numeroTlp1;
        $personne->save();

        return response()->json([
            'message' => 'Numéro de téléphone mis à jour avec succès.',
            'data' => $personne
        ]);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Server error',
               'error' => $e->getMessage()
           ], 500);
       }
   }
}

       /* [$latitude, $longitude] = explode(',', $request->currentUserPosition);

        $donors = Personne::where('idGroupage', $request->groupage)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw(
                "*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * 
                cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * 
                sin( radians( latitude ) ) ) ) AS distance",
                [$latitude, $longitude, $latitude]
            )
            ->having('distance', '<', 20) // 10 km de rayon
            ->orderBy('distance')
            ->get();

       // Si aucun donneur trouvé
       if ($donors->isEmpty()) {
        return response()->json(['message' => 'Aucun donneur trouvé à proximité'], 404);
    }

    // Préparer les données pour l'événement
    $eventData = [
        'groupage' => $request->groupage,
        'position' => "{$latitude},{$longitude}",
        'message' => "Besoin urgent de sang du groupe id {$request->groupage} !",
        'donors' => $donors
    ];

    // Diffuser l'événement en temps réel
    //broadcast(new BloodRequestEvent($eventData))->toOthers();
 // Diffuser l'événement
 event(new BloodRequestEvent($eventData));

    // Retourner la liste des donneurs
    return response()->json([
        'message' => 'Demande envoyée avec succès',
        'donors' => $donors
    ]);
    }
}
*/