<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin;
use App\Models\PersonneMedicale;
use App\Models\Groupage;

class Demande extends Model
{
    use HasFactory;

    protected $table = 'demandes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'dateDemande',
        'nbreDonneursEnvoyes',
        'lieuDemande',
        'serviceMedical',
        'groupageDemande',
        'quantiteDemande',
        "typeDemande",
        'typeMaladie',
        'idDemandeur',
        'numeroDossierMedical',
        'notes',
    ];

    public function demandeur()
    {

        //get personneType of  idDemandeur depuis la table personnes( user or admin or personnel medical)
        //dd($this->idDemandeur)
        //TODO
        $personneType="";
    if ($user = User::find($this->idDemandeur)) {
        $personneType= "user";
    }

    if ($admin = Admin::find($this->idDemandeur)) {
        $personneType= "admin";
    }

    if ($medecin = PersonneMedicale::find($this->idDemandeur)) {
        $personneType= "PersonneMedicale";
    }

    switch ($personneType) {
        case 'user':
            return $this->belongsTo(User::class, 'idDemandeur');
        case 'admin':
            return $this->belongsTo(Admin::class, 'idDemandeur');
        case 'PersonneMedicale':
            return $this->belongsTo(PersonneMedicale::class, 'idDemandeur');
      
    } 
  
    }
   
    public function demandeurUser()
    {
        return $this->belongsTo(User::class, 'idDemandeur');
    }
    
   
      public function groupage()
      {
          return $this->belongsTo(Groupage::class, 'groupageDemande', 'id');
      }
      public function personnes()
        {
            return $this->belongsToMany(Personne::class, 'demande_personne', 'demande_id', 'personne_id')
            ->withPivot('date_acceptation')
            ->withTimestamps();
        }
}
