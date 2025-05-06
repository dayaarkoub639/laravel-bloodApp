<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\Personne;
use App\Models\Groupage;
use App\Models\User; 
use App\Models\Wilaya;
use App\Models\Commune;

use Illuminate\Support\Carbon;
 

class DemandeController extends Controller
{
    public function searchAdvanced(Request $request)
    {
   
        $query = Demande::query() ;
       
     
        if ($request->has('periode')) { 
            $periode = $request->periode;  
            if ($periode) {
                switch ($periode) {
                    case '24h':
                        $query->whereBetween('dateDemande', [Carbon::now()->subDay(), Carbon::now()]);
                        break;
                    case '7j':
                        $query->whereBetween('dateDemande', [Carbon::now()->subDays(7), Carbon::now()]);
                        break;
                    case '15j':
                        $query->whereBetween('dateDemande', [Carbon::now()->subDays(15), Carbon::now()]);
                        break;
                    case '1m':
                        $query->whereBetween('dateDemande', [Carbon::now()->subMonth(), Carbon::now()]);
                        break;
                }
            }
        }
 
          // Groupage sanguin
          if ($request->filled('groupage')) {
            $query->where('groupageDemande', $request->input('groupage'));
        }
        
        // Wilaya et commune
     if ($request->filled('wilaya')) {
        $wilayaId = (int) $request->wilaya;
        $communeId = (int) $request->commune;
            $query 
            ->join('users', 'demandes.idDemandeur', '=', 'users.id')
            ->join('personnes', 'users.keyIdUser', '=', 'personnes.idUser')
            ->when($request->filled('wilaya'), function ($q) use ($request,$communeId) {
                $wilayaId = $request->wilaya;
                $q->where(function ($subQuery) use ($wilayaId,$communeId) {
                    $subQuery->where('personnes.wilaya_domicile_id', $wilayaId)
                            ->orWhere('personnes.wilaya_prof_id', $wilayaId);
                         
                            if ($communeId) {
                                $subQuery->orWhere('personnes.commune_domicile_id', $communeId)
                                         ->orWhere('personnes.commune_prof_id', $communeId);
                            }
                });
            })
            ->select('demandes.*');        
    }
 
       

        $demandes = $query->get() ;
        
        $listeWilayas = Wilaya::all();
        $listeCommune = Commune::all();
        $listeGroupage= Groupage::all(); 
        return view('demandes.list-demandes',compact('demandes','listeGroupage','listeWilayas','listeCommune'));
    }



    public function demande(){
        $demandes = Demande::with('personnes')->get() ;
        $listeWilayas = Wilaya::all();
        $listeCommune = Commune::all();
        $listeGroupage= Groupage::all(); 
        return view('demandes.list-demandes',compact('demandes','listeGroupage','listeWilayas','listeCommune'));
    }
    public function add(){
        $listeGroupage= Groupage::all(); 
        $users=User::all();
        return view('demandes.add',compact('users','listeGroupage'));
     }

   
    /**
     * Enregistre une nouvelle demande.
     */
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'dateDemande' => 'required|date',
           
            'lieuDemande' => 'required|string|max:255',
            'serviceMedical' => 'required|string|max:255',
            'groupageDemande' => 'required|exists:groupage,id',
            'quantiteDemande' => 'required|integer|min:1',
           
            'typeMaladie' => 'required|string|max:255',
            'idDemandeur' => 'required',
            'numeroDossierMedical' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Création de la demande
        Demande::create([
            'dateDemande' => $request->dateDemande,
            'lieuDemande' => $request->lieuDemande,
            'serviceMedical' => $request->serviceMedical,
            'groupageDemande' => $request->groupageDemande,
            'quantiteDemande' => $request->quantiteDemande,
            'typeMaladie' => $request->typeMaladie,
            'idDemandeur' => $request->idDemandeur,
            'numeroDossierMedical' => $request->numeroDossierMedical,
            'notes' => $request->notes,
            'typeDemande' => "rendez vous",
            'nbreDonneursEnvoyes' => $request->nbreDonneursEnvoyes,

            
        ]);

        return redirect()->route('demandes.liste')->with('success', 'Demande ajoutée avec succès.');
    }
       
        public function edit($id)
        {
            $demande = Demande::findOrFail($id);
            $users = User::all(); // Liste des utilisateurs
            $listeGroupage = Groupage::all(); // Liste des groupages sanguins
            return view('demandes.edit', compact('demande', 'users', 'listeGroupage'));
        }
        public function acceptesView($id)
        { 
    
        //$donneurs = Personne::with('user')->get() ;
       // $donneurs = DemandePersonne::get();
      //  $donneurs = Personne::with('demandes')->findOrFail($id);
      $donneurs = Demande::with('personnes')->findOrFail($id);
 
            return view('demandes.acceptes',compact("donneurs"));
        }

       
        public function update(Request $request, $id)
        {
            try{    
            // Validation des données
            $request->validate([
                'dateDemande' => 'required|date',
                 
               /* 'lieuDemande' => 'string|max:255',
                'serviceMedical' => 'required|string|max:255',
                'groupageDemande' => 'required|exists:groupage,id',
                'quantiteDemande' => 'required|integer|min:1',
               
                'typeMaladie' => 'required|string|max:255',
                'idDemandeur' => 'required',
                'numeroDossierMedical' => 'nullable|string|max:255',
                'notes' => 'nullable|string',*/
            ]);

            // Récupération et mise à jour
            $demande = Demande::findOrFail($id);
            $demande->update($request->all());

            return redirect()->route('demandes.liste')->with('success', 'Demande mise à jour avec succès.');
            } catch (\Exception $e) {
                return redirect()->route('demandes.liste')->with('error', $e->getMessage());
            }
        }

        public function destroy($id)
        {
            try{     
                $demande = Demande::findOrFail($id);
                $demande->delete();
                
                return redirect()->route('demandes.liste')->with('success', 'Demande supprimé avec succès !');
            } catch (\Exception $e) {
                return redirect()->route('demandes.liste')->with('error', $e->getMessage());
            }
        }
}
