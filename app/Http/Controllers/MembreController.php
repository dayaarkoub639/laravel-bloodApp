<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\HttpResponses;
use App\Models\User;
use App\Models\Personne;
use App\Models\Otp;
use Illuminate\Http\JsonResponse;
 
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Groupage;
use App\Models\Wilaya;
use App\Models\Commune;
use App\Models\personneMedicale;
use App\Models\admin;
use App\Models\Don;
use App\Models\Centre;

class MembreController extends Controller
{
   public function membres(){ 
     $users = Personne::with('user')->get() ;
     $listeGroupage= Groupage::all();
     $listeWilayas= Wilaya::all();
     $listeCommune= Commune::all();
     return view('membres.list-membres',compact('users','listeGroupage','listeWilayas','listeCommune'));
   }

   public function add(){
      $listeGroupage= Groupage::all();
      $listeWilayas= Wilaya::all();
      $listeCommune= Commune::all();
      $listeCentres= Centre::all();
      return view('membres.add-member',compact('listeGroupage','listeWilayas','listeCommune','listeCentres'));
   }
   public static function generateCustomId($wilaya_domicile_id, $gender)
   {
     
       $part3 = str_pad(self::getNextSequence() , 5, '0', STR_PAD_LEFT); // Numéro séquentiel sur 5 digits
       $part2 = str_pad($wilaya_domicile_id, 2, '0', STR_PAD_LEFT); // De 01 à 58
       return "{$gender}{$part2}{$part3}";
   }

   public static function getNextSequence()
   {
       $lastRecord = Personne::orderBy('idUser', 'desc')->first();

       if (!$lastRecord) {
           return 1;
       }
       
       $lastId = substr($lastRecord->idUser, -5); // Extraire les 5 derniers chiffres
       return (int) $lastId + 1;
   }
   public function store(Request $request)
   {
     
       
   
       try {
         // Définir les règles de validation
         $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'birthdaydate' => 'required|date',
            'lieuNaissance' => 'required|string|max:255',
            'gender' => 'required|in:0,1',
            'phone01' => 'required|numeric|digits:10',
            'phone02' => 'nullable|numeric|digits:10',
            'adresseDomicile' => 'required|string|max:255',
            'wilayaDomicile' => 'required|exists:wilayas,id',
            'communeDomicile' => 'required|exists:communes,id',
            'adresseProfessionnelle' => 'required|string|max:255',
            'wilayaProfessionnelle' => 'required|exists:wilayas,id',
            'communeProfessionnelle' => 'required|exists:communes,id',
            'observations' => 'nullable|string',
            'groupage' => 'nullable',
            'phenotypeCmaj' => 'nullable|in:0,1',
            'phenotypeEmaj' => 'nullable|in:0,1',
            'phenotypeCmin' => 'nullable|in:0,1',
            'phenotypeEmin' => 'nullable|in:0,1',
            'phenotypeKell' => 'nullable|in:0,1',
            'type_personne' => 'required|in:user,admin,personnelMedical',
            'motDePasse' => 'required|string|min:8|confirmed',
            'idCentre' => 'nullable', // Si applicable
        ]);

        // Si la validation échoue, on retourne les erreurs à la page précédente
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
      
  
         // Commencer une transaction
         \DB::beginTransaction();
         
         $idCustom = self::generateCustomId($request->wilayaDomicile, $request->gender) ;
       
         // Créer une entrée dans la table `personne`
         $personne = Personne::create([
             'idUser'=>$idCustom,
             //info gles
             'nom' => $request->nom ,
             'prenom' => $request->prenom ,
             'gender' => (int) $request->gender,
             'lieuNaissance' => $request->lieuNaissance,
             
             'epouseDe' => $request->epouseDe,
             'dateDeNess' => $request->birthdaydate,
             'numeroTlp1' => $request->phone01,
             'numeroTlp2' => $request->phone02,
             'observations' => $request->observations,
             //adress dom
             'adresse' => $request->adresseDomicile,
             'commune_domicile_id' => $request->communeDomicile,
             'wilaya_domicile_id' => $request->wilayaDomicile,

             //address prof
             'adressePro' => $request->adresseProfessionnelle,
             'commune_prof_id' => $request->communeProfessionnelle,
             'wilaya_prof_id' => $request->wilayaProfessionnelle,
          
             
           //groupage
            'idGroupage' => (int)$request->groupage, 
            'cMaj' =>(int) $request->phenotypeCmaj, 
            'cMin' => (int)$request->phenotypeCmin, 
            'eMaj' => (int)$request->phenotypeEmaj, 
            'eMin' => (int)$request->phenotypeEmin, 
            'kell' => (int) $request->phenotypeKell, 
            //compte
            'typePersonne' => $request->type_personne,
            'idCentre' => $request->idCentre,
            'motDePasse' => Hash::make($request->type_personne),
            'accept_sendsms' =>  0,

             
            
         ]);

         if($request->type_personne=="user"){
            // Créer une entrée dans la table `users`
            $user = User::create([
               'keyIdUser' => $idCustom,
               'pseudo' => $request->pseudo,
            ]);
         }
         if($request->type_personne=="admin"){
            // Créer une entrée dans la table `users`
            $user = Admin::create([
               'idPersonne' => $idCustom,
               'acces' => $request->acces,
            ]);
         } 
         if($request->type_personne=="personnelMedical"){
            // Créer une entrée dans la table `users`
            $user = personneMedicale::create([
               'idPersonne' => $idCustom,
               'fonction' => $request->fonction,
               'role' =>  $request->role,
            ]);
         }

    
         \DB::commit();
         return redirect()->route('membres')->with('success', 'Membre ajouté avec succès.');

     } catch (\Exception $e) {
         // Annuler la transaction en cas d'erreur
         \DB::rollBack();

         // Log l'erreur pour le débogage
         Log::error('Erreur lors de la création de l\'utilisateur : ' . $e->getMessage());

         // Retourner une réponse d'erreur
       return response()->json([
             'message' => 'Une erreur est survenue lors de la création de l\'utilisateur.',
             'error' => $e->getMessage(),
         ], 500);

         //return redirect()->back()->withInput()->withErrors(['error' =>  $e->getMessage()]);
     }
   }

   public function edit($id){
      $personne = Personne::where('idUser', $id)->first();

      //selon le type de user on affiche d autres?
      $listeGroupage= Groupage::all();
      $listeWilayas= Wilaya::all();
      $listeCommune= Commune::all();
      $listeCentres= Centre::all();
      if($personne->typePersonne=="user"){ 
         $sousPersonne = User::where("keyIdUser",$id)->first();
         
      }
      if($personne->typePersonne=="admin"){
           $sousPersonne = Admin::where("idPersonne",$id)->first();
   
      } 
      if($personne->typePersonne=="personnelMedical"){
         $sousPersonne= personneMedicale::where("idPersonne",$id)->first();
       
      }
  
      return view('membres.edit-member',compact('listeGroupage','listeWilayas','listeCommune',"personne","sousPersonne","listeCentres"));
   }


   public function update(Request $request, $id){
    
      $request->validate([
         'nom' => 'required|string|max:255',
         'prenom' => 'required|string|max:255',
         'motDePasse' => 'confirmed', 
       
     ]);
 
     $personne = Personne::where('idUser', $id)->first();

     $personne->update([
 
             //info gles
             'nom' => $request->nom ,
             'prenom' => $request->prenom ,
             'gender' => (int) $request->gender,
             'dateDeNess' => $request->birthdaydate,
             'numeroTlp1' => $request->phone01,
             'numeroTlp2' => $request->phone02,
             'observations' => $request->observations,
             //adress dom
             'adresse' => $request->adresseDomicile,
             'commune_domicile_id' => $request->communeDomicile,
             'wilaya_domicile_id' => $request->wilayaDomicile,

             //address prof
             'adressePro' => $request->adresseProfessionnelle,
             'commune_prof_id' => $request->communeProfessionnelle,
             'wilaya_prof_id' => $request->wilayaProfessionnelle,
             'lieuNaissance' => $request->lieuNaissance,
             'epouseDe' => $request->epouseDe,
             'idCentre' => $request->idCentre,
           //groupage
            'idGroupage' => (int)$request->groupage, 
            'cMaj' =>(int) $request->phenotypeCmaj, 
            'cMin' => (int)$request->phenotypeCmin, 
            'eMaj' => (int)$request->phenotypeEmaj, 
            'eMin' => (int)$request->phenotypeEmin, 
            'kell' => (int)$request->phenotypeKell, 
            //compte
            'typePersonne' => $request->type_personne,
            'motDePasse' => Hash::make($request->type_personne),
           

     ]);
 
    
     return redirect()->route('membres')->with('success', 'Membre mis à jour avec succès.');
   }


   public function show($id){
      $personne = Personne::where('idUser', $id)->first();
      $dons = Don::where('idDonneur', $id)->get();
      return view('membres.fiche-identif',compact("personne","dons" ));
   }

 
    public function destroy($id)
    {
        try{ 
            $personne = Personne::findOrFail($id);
           
            if($personne->typePersonne=="user"){ 
               $user = User::where("keyIdUser",$id)->first();
               $user->delete();
            }
            if($personne->typePersonne=="admin"){
               $admin = Admin::where("idPersonne",$id)->first();
               $admin->delete();
            } 
            if($personne->typePersonne=="personnelMedical"){
               $personnelMedical = personneMedicale::where("idPersonne",$id)->first();
               $personnelMedical->delete();
            }
            $personne->delete(); 
      //supprimer aussi ces dons?
            return redirect('membres')->with('success', 'Membre supprimé avec succès !');

     } catch (\Exception $e) {
            return redirect('membres')->with('error', $e->getMessage());
    }
    }
}
