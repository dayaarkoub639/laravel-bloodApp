<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Groupage;
use App\Models\Personne;
use App\Models\Don;
use App\Models\Demande;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Events\BloodRequestEvent;

class UrgencesController extends Controller
{
    public function index()
    {
        $listeGroupage = Groupage::all();
        $donneurs = [];
        $lastDemande =  Demande::latest()->first();

        $admin = Admin::where('idPersonne', Auth::user()->idUser)->first();
        $centre = DB::table('centres')->select('id', 'nom', 'latitude', 'longitude')->where('id', $admin->idCentre)->first();
        $pointA = ['lat' => $centre->latitude, 'lon' => $centre->longitude];
        $nomCentre =  $centre->nom;
        return view('urgences.urgence', compact("listeGroupage", "donneurs", "lastDemande", 'pointA', "nomCentre"));
    }
    public function searchByGroupage(Request $request)
    {
        $request->validate([
            'idGroupage' => 'required|integer|exists:groupage,id',
            'nbreDonneursDemande' => 'required',

        ]);
        $listeGroupage = Groupage::all();

        // Récupérer les donneurs ayant l'idGroupage donné avec leurs dons

        $threeMonthsAgo = Carbon::now()->subMonths(3);
        // Requête pour trouver les utilisateurs donneurs compatibles
        $donneurs = Personne::where('idGroupage', $request->idGroupage)
            ->where('serologie', 0)
            ->where('dernierDateDon', '>=', $threeMonthsAgo)
            /*  ->whereHas('dons', function ($query) use ($dateLimite) {
                    $query->where('serologie', 0);
                })*/
            ->get();


        // Si aucun donneur trouvé
        if ($donneurs->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun donneur trouvé à proximité');
        }


        $donneurs = $donneurs->take($request->nbreDonneursDemande);
        foreach ($donneurs as $donneur) {
            //done la distance et le temps
            $centreInfos = $this->getCentreProche($donneur->latitude, $donneur->longitude);

            if ($centreInfos) {
                $donneur->centreProche = $centreInfos['nom'];
                $donneur->distanceKm = $centreInfos['distance_km'];
                $donneur->tempsEstime = $centreInfos['temps_min'];
            }
            // Préparer les données pour l'événement
            $eventData = [
                'groupage' => $request->idGroupage,
                'position' => "{$donneur->latitude},{$donneur->longitude}",
                'message' => "Besoin urgent de sang du groupage id {$request->groupage} !",
                'user_id' =>  $donneur->id,
                'demandeurId' =>  $request->idDemandeur,
                'centreProche' =>  $donneur->centreProche,


            ];


            // Diffuser l'événement
            event(new BloodRequestEvent($eventData));
            $donneur->dons = $donneur->dons()->get() ?? "";
            // Vérifier si le donneur a des dons
            /*  if ($donneur->dons->isNotEmpty()) {
                    // Trier les dons par date décroissante et prendre le premier (le plus récent)
                    $latestDon = $donneur->dons->sortByDesc('date')->first();

                    // Convertir la date du dernier don en instance de Carbon
                    $latestDate = Carbon::parse($latestDon->date);

                    // Date actuelle
                    $currentDate = Carbon::now();

                    // Calculer la différence entre la date actuelle et la date du dernier don
                    $diffInMonths = $latestDate->diffInMonths($currentDate);

                    // Vérifier si le dernier don est dans les 3 derniers mois
                    if ($diffInMonths   <= 3) {
                        // Ajouter le donneur à la liste des donneurs filtrés
                        $donneursFiltres[] = $donneur;
                    }
                }*/
        }
        //$donneurs = $donneursFiltres??[];
        $lastDemande =  Demande::latest()->first();
        $admin = Admin::where('idPersonne', Auth::user()->idUser)->first();
        $centre = DB::table('centres')->select('id', 'nom', 'latitude', 'longitude')->where('id', $admin->idCentre)->first();
        $pointA = ['lat' => $centre->latitude, 'lon' => $centre->longitude];
        $admin = Admin::where('idPersonne', Auth::user()->idUser)->first();
        $nomCentre =  $centre->nom;
        return view('urgences.urgence', compact("listeGroupage", "donneurs", "lastDemande", 'pointA', "nomCentre"));;
    }
    private function getCentreProche($lat, $lng)
    {
        $admin = Admin::where('idPersonne', Auth::user()->idUser)->first();
        $centre = DB::table('centres')->select('id', 'nom', 'latitude', 'longitude')->where('id', $admin->idCentre)->first();

        $plusProche = null;
        $distanceMin = null;

        $distance = $this->calculerDistance($lat, $lng, $centre->latitude, $centre->longitude);

        if ($distanceMin === null || $distance < $distanceMin) {
            $distanceMin = $distance;
            $plusProche = $centre;
        }


        if ($plusProche) {
            $tempsMinutes = $this->estimerTempsTrajet($distanceMin); // estimer en minutes

            return [
                'nom' => $plusProche->nom . ' (id : ' . $plusProche->id . ')',
                'distance_km' => round($distanceMin, 2),
                'temps_min' => round($tempsMinutes),
            ];
        }

        return null;
    }

    private function estimerTempsTrajet($distanceKm)
    {
        $vitesseKmH = 60; // moyenne en voiture
        return ($distanceKm / $vitesseKmH) * 60; // en minutes
    }
    private function calculerDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function envoyerDemandes(Request $request)
    {
        //step 01 récupère les donneurs selon la recherche
        $donneursf = json_decode($request->donneursList, true);

        if (count($donneursf) == 0) {
            return redirect()->back()->with('error', 'Aucun donneur trouvé à proximité');
        }


        //step 02: creer la demande type:urgent

        $idGroupage = $donneursf[0]['idGroupage']; //apres le fltre tous les donneurs ont le mme groupage
        $idUserPersonneAuth = Auth::user()->idUser;

        $idDemandeur = DB::table('admin')
            ->where('idPersonne', $idUserPersonneAuth)
            ->value('id');

        Demande::create([
            'dateDemande' => Carbon::now(),
            'lieuDemande' => "",
            'serviceMedical' => "",
            'groupageDemande' => $idGroupage,
            'quantiteDemande' => 1,
            'typeMaladie' => "",
            'idDemandeur' => $idDemandeur,
            'numeroDossierMedical' => "",
            'notes' => "",
            'nbreDonneursEnvoyes' => count($donneursf),
            'typeDemande' => "urgent",
        ]);


        //step 03:  envoie les notifications aux donneurs trouvés

        foreach ($donneursf as $donneur) {
            $eventData = [
                'message' => "Demande de sang a été envoyé !",
                'idDemandeur' => $idDemandeur,
                'idCentreProche' => $this->getCentreProche($donneur['latitude'], $donneur['longitude']),
                'idDonneur' => $donneur['idUser']

            ];
            // Diffuser l'événement
            broadcast(new BloodRequestEvent($eventData));
        }

        return redirect()->back()->with('success', 'Demande(s) envoyée(s) avec succès.');;
    }
}
