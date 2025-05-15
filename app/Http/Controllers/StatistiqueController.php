<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Groupage;
use App\Models\Personne;
use App\Models\Demande;
use App\Models\User;
use App\Models\Don;

class StatistiqueController extends Controller
{
    public function statistique(){
        $groupes = Groupage::withCount('personnes')->get();
        $nbrUtilisateurs = User::count();
        $nbrDonneurs = Personne::whereHas('dons')->count();
        $nbrDemandes = Demande::count();
        $nbrDons = Don::count();
        return view('stats.statistiques',compact('groupes','nbrUtilisateurs','nbrDonneurs','nbrDemandes','nbrDons'));
    }
}
