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
        
        'lieuDemande',
        'serviceMedical',
        'groupageDemande',
        'quantiteDemande',
        
        'typeMaladie',
        'idDemandeur',
        'numeroDossierMedical',
        'notes',
    ];

    public function demandeur()
    {
        return $this->belongsTo(User::class, 'idDemandeur');
    }
   
      public function groupage()
      {
          return $this->belongsTo(Groupage::class, 'groupageDemande', 'id');
      }
}
