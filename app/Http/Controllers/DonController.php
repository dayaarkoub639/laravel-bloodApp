<?php

namespace App\Http\Controllers;
use App\Models\personneMedicale;
use App\Models\Personne;
use App\Models\Don;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            // Validation des données
            $validator = Validator::make($request->all(), [
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
     
        // Vérifier si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

          // Créer une entrée dans la table `personne`
          $don = Don::create([
              'numeroFlacon'=>$request->numeroFlacon,
              'idDonneur'=>$request->idDonneur,
            
              'date' => $request->date ,
              //'lieuDon' => $request->lieuDon ,
              'Pds' => (int) $request->Pds,
              'C' => $request->C,
              'TA' => $request->TA,
              'serologie' => $request->serologie,
              'obsMedicale' => $request->observations,
             
              
              'sourceDon' => $request->sourceDon,
              'support' => $request->support,
              'reactions' => $request->reactions,
              'idPersonneMedicale' =>  (int) $request->persMedicale  ,
              'persMedicaleSuperviser' =>  (int) $request->persMedicaleSuperviser  
              
              ]);
            //todo update table personne
            // Récupération de la personne
            $personne = Personne::where('idUser', $request->idDonneur)->firstOrFail();

            // Mise à jour
            $personne->serologie = $request->serologie;
            $personne->dernierDateDon =$request->date;
            $personne->save();

      
             //add note automatique 
        
             if ($request->sourceDon !== "Ami") {
                $user = User::where('keyIdUser', $request->idDonneur)->first();
             
                if($user->noteEtoile<5){
                    $user->noteEtoile = $user->noteEtoile + 1;
                    $user->save();
                }
               
            }

            return redirect('/membres/fiche/'.$don->idDonneur)->with('success', 'Don ajouté avec succès !');
    
 
      } catch (\Exception $e) {
        return redirect('/membres/fiche/'.$don->idDonneur)->with('error', $e->getMessage());
         
      }
    }
    public function edit($id){
        $don = Don::where('idDon', $id)->first();
        $listePersonneMedicale=personneMedicale::all();
     
        return view('dons.edit-don',compact( "listePersonneMedicale","don"));
     }
     public function noter(Request $request, $id)
    {

        //users 
        $don = Don::where('idDon', $id)->first();
        $user = User::where('keyIdUser', $don->idDonneur)->first();
       
        // Incrémenter la note
        if (!$don->donIsNote) {
            $user->noteEtoile = $user->noteEtoile + 1;
            $user->save();
            $don->donIsNote = true;
            $don->save();
        }
       
        return redirect('membres/fiche/'.$don->idDonneur)->with('success', 'Une note ajoutée avec succès !');
     }

     public function decrementerNote(Request $request, $id)
    {

        //users 
        $don = Don::where('idDon', $id)->first();
        $user = User::where('keyIdUser', $don->idDonneur)->first();
        
        // decrémenter la note
        if ($don->donIsNote) {
            $user->noteEtoile = $user->noteEtoile - 1;
            $user->save();

            $don->donIsNote = false;
            $don->save();
        }
        return redirect('membres/fiche/'.$don->idDonneur)->with('success', 'Une note supprimé avec succès !');
     }
   /**
     * Mettre à jour un don existant.
     */
    public function update(Request $request, $id)
    {
       
        try{       
          // Validation des données
          $validator = Validator::make($request->all(), [
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
 
            // Vérifier si la validation échoue
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $don = Don::findOrFail($id);
            $don->update([
                'date'         => $request->date,
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
                'sourceDon' => $request->sourceDon ]);
  
            // Récupération de la personne
            $personne = Personne::where('idUser', $don->idDonneur)->firstOrFail();

            // Mise à jour personne
            $personne->serologie = $request->serologie;
            $personne->dernierDateDon =$request->date;
            $personne->save();
 

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
