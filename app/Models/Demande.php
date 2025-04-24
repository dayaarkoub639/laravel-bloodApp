<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
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

        //if user or admin or personnel medical
        //dd($this->idDemandeur)

        if(true){
            return $this->belongsTo(User::class, 'idDemandeur');
        }else{
            return $this->belongsTo(Admin::class, 'idDemandeur');
        }
      
  
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
