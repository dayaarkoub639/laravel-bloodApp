<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleMapController extends Controller
{
    public function resolveUrl(Request $request)
    {
        // Récupérer l'URL Google Maps envoyée depuis le frontend
        $url = $request->input('url');

        // Faire une requête HTTP à l'URL raccourcie pour suivre la redirection
        $response = Http::withOptions(['allow_redirects' => true])->get($url);

        // Obtenir l'URL finale (après redirection)
        $resolvedUrl = $response->effectiveUri();

        // Extraire la latitude et la longitude de l'URL complète
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $resolvedUrl, $matches)) {
            return response()->json([
                'latitude' => $matches[1],
                'longitude' => $matches[2],
            ]);
        }

        return response()->json(['error' => 'Coordonnées non trouvées.'], 400);
    }
}
