<?php

namespace App\Http\Controllers;
use App\Models\personneMedicale;
use App\Models\Personne;
use App\Models\Don;
use Illuminate\Http\Request;

class DonController extends Controller
{
    public function searchByGroupageView(Request $request)
    {
        

        $donneurs = Personne:: whereHas('dons')
            ->with(['groupage', 'dons' => function ($query) {
                $query->orderBy('date', 'desc');
            }])
            ->get();

        return view('dons.list', compact('donneurs'));
    }
    public function add($idPersonne){
        $listePersonneMedicale=personneMedicale::all();

        return view('dons.add-don',compact("idPersonne","listePersonneMedicale"));
       
    }


    public function store(Request $request)
    {
      
        
    
        try {
            $request->validate([
                'date'         => 'required|date',
                //'lieuDon'      => 'required|string|max:255',
                'Pds'          => 'nullable|numeric|min:0',
                'numeroFlacon' => 'required|string|max:50',
                'C'            => 'nullable|string|max:50',
                'TA'           => 'nullable|string|max:50',
                'serologie'    => 'required|in:0,1',
                'support'      => 'required|in:double,triple,quadruple',
                'reactions'    => 'nullable|string',
                'observations' => 'nullable|string',
                'persMedicale' => 'required|exists:personnelMedical,id',
                'persMedicaleSuperviser' => 'required|exists:personnelMedical,id',
            ]);
     
          // Créer une entrée dans la table `personne`
          $personne = Don::create([
              'numeroFlacon'=>$request->numeroFlacon,
              'idDonneur'=>$request->idDonneur,
            
              'date' => $request->date ,
              //'lieuDon' => $request->lieuDon ,
              'Pds' => (int) $request->Pds,
              'C' => $request->C,
              'TA' => $request->TA,
              'serologie' => $request->serologie,
              'obsMedicale' => $request->observations,
             
              'support' => $request->support,
              'reactions' => $request->reactions,
              'idPersonneMedicale' =>  (int) $request->persMedicale  ,
              'persMedicaleSuperviser' =>  (int) $request->persMedicaleSuperviser  
              
              ]);
  
      
          return redirect()->route('membres')->with('success', 'Don ajouté avec succès.');
 
      } catch (\Exception $e) {
        
          // Retourner une réponse d'erreur
          return response()->json([
              'message' => 'Une erreur est survenue lors de la création de don.',
              'error' => $e,
          ], 500);
      }
    }
    public function edit($id){
        $don = Don::where('idDon', $id)->first();
        $listePersonneMedicale=personneMedicale::all();
     
        return view('dons.edit-don',compact( "listePersonneMedicale","don"));
     }
  

   /**
     * Mettre à jour un don existant.
     */
    public function update(Request $request, $id)
    {
       
        try{
       
            $don = Don::findOrFail($id);
         
            $don->update([
                'date'         => $request->date,
                //'lieuDon'      => $request->lieuDon,
                'Pds' => (int) $request->Pds,
              
                'numeroFlacon' => $request->numeroFlacon,
                'C'            => $request->C,
                'TA'           => $request->TA,
                'serologie'    => $request->serologie,
                'support'      => $request->support,
                'reactions'    => $request->reactions,
                'obsMedicale' => $request->observations,
                'idPersonneMedicale' => $request->persMedicale,
                'persMedicaleSuperviser' => $request->persMedicaleSuperviser,
        
           
             
              
            ]);

            return redirect('/membres/fiche/'.$don->idDonneur)->with('success', 'Don modifié avec succès !');
        } catch (\Exception $e) {
            return redirect('/membres/fiche/'.$don->idDonneur)->with('error', $e->getMessage());
        }
    }

    /**
     * Supprimer un don.
     */
    public function destroy($id)
    {
        try{     
            $don = Don::findOrFail($id);
            $don->delete();
            
            return redirect('membres/fiche/'.$don->idDonneur)->with('success', 'Don supprimé avec succès !');
        } catch (\Exception $e) {
            return redirect('membres/fiche/'.$don->idDonneur)->with('error', $e->getMessage());
        }
    }

}
