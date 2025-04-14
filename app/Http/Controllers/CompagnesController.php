<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compagne;
use App\Models\Wilaya;
use App\Models\Commune;
use App\Models\Centre;

class CompagnesController extends Controller
{
   public function index(){
      $compagnes = Compagne::all() ;
      return view('compagnes.list-compagnes',compact("compagnes"));
   }
     
    public function add()
    {
      $listeWilayas= Wilaya::all();
      $listeCommune= Commune::all();
      $listeCentres= Centre::all();
      return view('compagnes.add',compact( 'listeWilayas','listeCommune',"listeCentres"));
     
    }

    /**
     * Enregistrer une nouvelle compagne.
     */
    public function store(Request $request)
    {
       
        $request->validate([
            'idCentre' => 'required',
            'address' => 'required|string|max:255',
            'wilaya' => 'required|integer',
            'commune' => 'required|integer',
            'etablissement' => 'required|string|max:255',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'heureDebut' => 'required',
            'heureFin' => 'required|after:heureDebut',
        ]);

        Compagne::create($request->all());

        return redirect()->route('compagnes')->with('success', 'Compagne ajoutée avec succès.');
    }
    public function edit($id)
    {
      $compagne = Compagne::findOrFail($id);
      $listeWilayas= Wilaya::all();
      $listeCommunes= Commune::all();
      $listeCentres= Centre::all();

      return view('compagnes.edit', compact('compagne', 'listeWilayas', 'listeCommunes','listeCentres'));
    }

    public function update(Request $request, $id)
    {
      
        $request->validate([
            'idCentre' => 'required ',
            'address' => 'required|string|max:255',
            'wilaya' => 'required|integer',
            'commune' => 'required|integer',
            'etablissement' => 'required|string|max:255',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'heureDebut' => 'required',
            'heureFin' => 'required|after:heureDebut',
        ]);

        $compagne = Compagne::findOrFail($id);
        $compagne->update($request->all());

        return redirect()->route('compagnes')->with('success', 'Compagne mise à jour avec succès.');
    }

    public function destroy($id)
    {
        try{     
            $compagne = Compagne::findOrFail($id);
            $compagne->delete();
            
            return redirect()->route('compagnes')->with('success', 'Compagne supprimé avec succès !');
        } catch (\Exception $e) {
           return redirect()->route('compagnes')->with('error', $e->getMessage());
        }
    }
}
