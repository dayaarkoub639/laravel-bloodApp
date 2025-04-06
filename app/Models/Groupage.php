<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Personne;

class Groupage extends Model
{
    use HasFactory;

    protected $table = 'groupage';  
    
    protected $fillable = ['type', 'description'];  

    public $timestamps = true;
    // Relation one-to-many avec le modèle Personne
    public function personnes()
    {
        return $this->hasMany(Personne::class, 'idGroupage', 'id');
    }
     // Relation one-to-many avec le modèle Personne
     public function demandes()
     {
         return $this->hasMany(Personne::class, 'groupageDemande', 'id');
     }
}
