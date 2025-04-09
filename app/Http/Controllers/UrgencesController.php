<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Groupage;
use App\Models\Personne;
use App\Models\Don;
use App\Models\Demande;
use Carbon\Carbon;

class UrgencesController extends Controller
{
   public function index(){
    $listeGroupage= Groupage::all(); 
    $donneurs = Personne:: whereHas('dons')
    ->with(['groupage', 'dons' => function ($query) {
        $query->orderBy('date', 'desc');
    }])
    ->get();
    $totalDons = count($donneurs);
    $totalDemandes = Demande::count();
    return view('urgences.urgence',compact("listeGroupage","donneurs","totalDons","totalDemandes"));
   }
   public function searchByGroupage(Request $request)
   {
       $request->validate([
           'idGroupage' => 'required|integer|exists:groupage,id',
       ]);
       $listeGroupage= Groupage::all(); 
       $totalDons = Don::count();
       $totalDemandes = Demande::count();
       // Récupérer les donneurs ayant l'idGroupage donné avec leurs dons
       $dateLimite = Carbon::now()->subMonths(3);
           
            // Requête pour trouver les utilisateurs donneurs compatibles
            $donneurs = Personne::where('idGroupage', $request->idGroupage)
            
                ->whereHas('dons', function ($query) use ($dateLimite) {
                    $query->where('serologie', 0);
                })
               ->get();
               
              
                   // Si aucun donneur trouvé
            if ($donneurs->isEmpty()) {
                return response()->json(['message' => 'Aucun donneur trouvé à proximité'], 404);
            }
        
     
        
            foreach ($donneurs as $donneur) {
                //TODO la distance et le temps

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
     $donneurs = $donneursFiltres??[];
      /* $donneurs = Personne::where('idGroupage', $request->idGroupage)
            ->with(['groupage', 'dons' => function ($query) {
               $query->orderBy('date', 'desc'); 
             }])
             -> whereHas('dons', function ($query) {
                // Critère 1 : Sérologie négative
                $query->where('serologie', 0);  
            })
            
           ->get();*/

           return view('urgences.urgence',compact("listeGroupage","donneurs","totalDons","totalDemandes"));
   }
 
}
