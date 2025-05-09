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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            ->where('serologie', 0)
            ->where('dernierDateDon', '>=', $threeMonthsAgo)
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
      
            // Si tu veux retourner avec distance triée, décommente et adapte ci-dessous :
        $donneurs = $donneurs->sortBy('distance')->values();
        $donneurs = $donneurs->take(3);
   
        foreach ($donneurs as $donneur) {
           
            $donneur->distance = $this->calculerDistance($latitude, $longitude, $donneur->latitude, $donneur->longitude);
            $donneur->pseudo = $donneur->user()->value("pseudo")?? "";
         
            
            $donneur->centreProche = $this->getCentreProche($donneur->latitude, $donneur->longitude);
                // done récupérer la date la plus récente, puis comparer cette date avec la date actuelle. 
            
                //if($donneur->user()->value('id')=="11"){//for test
                // Préparer les données pour l'événement
                $eventData = [
                    'groupage' => $request->groupage,
                    'position' => "{$donneur->latitude},{$donneur->longitude}",
                    'message' => "Besoin urgent de sang du groupe id {$request->groupage} !",
                    'user_id' =>  $donneur->idUser, 
                    'demandeurId' =>  $request->idDemandeur, 
                    'centreProche' =>  $donneur->centreProche, 

                ];

                
                // Diffuser l'événement 
                broadcast(new BloodRequestEvent($eventData)); 
                $donneur->dons = $donneur->dons()->get() ?? "";
        }

     
        

        return response()->json([
            'success' => true,
            'donneurs' => $donneurs

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


  
    
    private function getCentreProche($lat, $long)
    {
        // Utilise la formule de Haversine pour calculer la distance en kilomètres
        $centre = DB::table('centres')
            ->select('nom', 'id',
                DB::raw("(
                    100 * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    )
                ) AS distance"))
            ->orderBy('distance')
            ->limit(1)
            ->setBindings([$lat, $long, $lat])
            ->first();
    
        if ($centre) {
            return $centre->nom . " (id : " . $centre->id . ")";
        }
    
        return "Centre inconnu";
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
            if (!$personne) {
                return response()->json([
                    'success' => false,
                    'message' => 'Donor not found'
                ], 404);
            }
            
            $demande_id = $request->idDemande;
            $demande = Demande::find($demande_id);
            if (!$demande) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }
            
            // Record the acceptance
            $personne->demandes()->syncWithoutDetaching([
                $demande_id => ['date_acceptation' => now()]
            ]);
    
            // Get the center information
            $centreProche = $this->getCentreProche($personne->latitude, $personne->longitude);
    
            // Prepare data for the notification to the original requester
            $eventData = [
                'message' => "Your blood request has been accepted!",
                'idDemandeur' => $demande->idDemandeur, // Original requester
                'idDonateur' => $request->idUser,
                'idCentreProche' => $centreProche,
                'type' => 'acceptance'
            ];
    
            // Broadcast to the original requester
            $originalRequester = Personne::where('idUser', $demande->idDemandeur)->first();
            if ($originalRequester) {
                broadcast(new BloodRequestEvent([
                    'user_id' => $demande->idDemandeur,
                    'data' => $eventData
                ]));
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Request accepted successfully',
                'centreProche' => $centreProche,
                'demande_id' => $demande_id,
                'requesterInfo' => [
                    'id' => $demande->idDemandeur,
                    'groupageDemande' => $demande->groupageDemande,
                    'dateDemande' => $demande->dateDemande,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error accepting blood request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getRequestStatus(Request $request, $id) {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'idDemandeur' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            // Find the request
            $demande = Demande::find($id);
            
            if (!$demande) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }
            
            // Check if the requester is the owner of this request
            if ($demande->idDemandeur != $request->idDemandeur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this request'
                ], 403);
            }
            
            // Get all donors who accepted
            $acceptedDonors = DB::table('demande_personne')
                ->where('demande_id', $id)
                ->join('personnes', 'demande_personne.personne_id', '=', 'personnes.idUser')
                ->leftJoin('users', 'personnes.idUser', '=', 'users.keyIdUser')
                ->select(
                    'personnes.idUser',
                    'users.pseudo',
                    'personnes.numeroTlp1',
                    'demande_personne.date_acceptation'
                )
                ->get();
            
            // Build response
            return response()->json([
                'success' => true,
                'requestDetails' => $demande,
                'acceptedDonors' => $acceptedDonors,
                'totalAccepted' => count($acceptedDonors)
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching request status: ' . $e->getMessage());
            
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