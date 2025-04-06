<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Groupage;
use App\Models\Personne;
use App\Models\Don;
use App\Models\Demande;


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
       $donneurs = Personne::where('idGroupage', $request->idGroupage)
            ->with(['groupage', 'dons' => function ($query) {
               $query->orderBy('date', 'desc'); 
             }])
           ->whereHas('dons') 
           ->get();

           return view('urgences.urgence',compact("listeGroupage","donneurs","totalDons","totalDemandes"));
   }
 
}
