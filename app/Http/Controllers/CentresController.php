<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Centre;
use App\Models\Wilaya;
use App\Models\Commune;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class CentresController extends Controller
{
   public function index(){
      $centres = Centre::all() ;
      return view('centres.list',compact("centres"));
   }
     
    public function add()
    {
      $listeWilayas= Wilaya::all();
      $listeCommune= Commune::all();
      return view('centres.add',compact( 'listeWilayas','listeCommune'));
     
    }

    /**
     * Enregistrer une nouvelle centre.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom'         => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', 
            'address'     => 'required|string|max:255',
            'wilaya'      => 'required|integer|exists:wilayas,id',
            'commune'     => 'required|integer|exists:communes,id',
            'localisation'=> 'nullable|string',
            'numeroTlp1'  => 'required|string|max:15|regex:/^[0-9]+$/',
            'numeroTlp2'  => 'nullable|string|max:15|regex:/^[0-9]+$/',
        ]);
       // Gérer l'upload de l'image
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public'); // Stockage
    }
        // Vérifier si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

       
        Centre::create(array_merge(
            $request->except('image'), // Exclure l'image de request->all()
            ['imageUrl' => $imagePath]  // Ajouter imageUrl manuellement
        ));
        return redirect()->route('centres')->with('success', 'Centre ajouté avec succès.');
    }
    public function edit($id)
    {
        $centre = Centre::findOrFail($id);
        $listeWilayas= Wilaya::all();
        $listeCommunes= Commune::all();
        return view('centres.edit', compact('centre', 'listeWilayas', 'listeCommunes'));
    }
 
    public function update(Request $request, $id)
    {
      
           // Validation des données
           $validator = Validator::make($request->all(), [
            'nom'         => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', 
            'address'     => 'required|string|max:255',
            'wilaya'      => 'required|integer|exists:wilayas,id',
            'commune'     => 'required|integer|exists:communes,id',
            'localisation'=> 'nullable|string',
            'numeroTlp1'  => 'required|string|max:15|regex:/^[0-9]+$/',
            'numeroTlp2'  => 'nullable|string|max:15|regex:/^[0-9]+$/',
        ]);

        // Vérifier si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $centre = Centre::findOrFail($id);
          // Vérifier si une nouvelle image a été téléchargée
    // Vérifier si une nouvelle image a été téléchargée
    if ($request->hasFile('image')) {
        // Supprimer l'ancienne image si elle existe
        if ($centre->imageUrl) {
            Storage::delete('public/' . $centre->imageUrl);
        }

        // Stocker la nouvelle image
        $imagePath = $request->file('image')->store('images', 'public');
        $centre->imageUrl = $imagePath;
    }

  
   
  // Mettre à jour les autres champs
  $centre->update([
    'nom'         => $request->nom,
    'address'     => $request->address,
    'wilaya'      => $request->wilaya,
    'commune'     => $request->commune,
    'localisation'=> $request->localisation,
    'numeroTlp1'  => $request->numeroTlp1,
    'numeroTlp2'  => $request->numeroTlp2,
]);
       
        return redirect()->route('centres')->with('success', 'Centre mis à jour avec succès.');
  
    }

    public function destroy($id)
    {
        try{     
            $centre = Centre::findOrFail($id);
            $centre->delete();
            
            return redirect()->route('centres')->with('success', 'Centre supprimé avec succès !');
        } catch (\Exception $e) {
           return redirect()->route('centres')->with('error', $e->getMessage());
        }
    }
}
