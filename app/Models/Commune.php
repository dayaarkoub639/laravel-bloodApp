<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Personne;
use App\Models\Centre;
use App\Models\Compagne;

class Commune extends Model{
  
    public $timestamps = false; // désactiver les colonnes created_at et updated_at

    protected $fillable = [
        'commune_name', 
        'daira_name', 
        'wilaya_code', 
        'wilaya_name', 
    
    ];
      // Relation one-to-many avec le modèle Personne
      public function personnes()
      {
          return $this->hasMany(Personne::class, 'commune_domicile_id', 'id');
      }

        // Relation one-to-many avec le modèle Personne
        public function compagnes()
        {
            return $this->hasMany(Compagne::class, 'commune', 'id');

        }
          // Relation one-to-many avec le modèle Personne
      public function centres()
      {
           
          return $this->hasMany(Centre::class, 'commune');
      }
}
