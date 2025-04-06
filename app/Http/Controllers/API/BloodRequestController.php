<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Personne;
use App\Events\BloodRequestEvent;

class BloodRequestController extends Controller
{
    public function findNearbyDonors(Request $request)
    {
        $request->validate([
            'groupage' => 'required|string',
            'currentUserPosition' => 'required|string'
        ]);

        [$latitude, $longitude] = explode(',', $request->currentUserPosition);

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
