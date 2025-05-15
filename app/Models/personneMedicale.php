<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Personne;
 
class PersonneMedicale extends Model{
  
    public $timestamps = true; 
    protected $table = 'personnel_medical'; 

    protected $fillable = [
        'idPersonne', 
        "fonction",
        'role'  ,
        'idCentre','idDemandeur'
    ];

    
    public function getKeyIdPersonneAttribute($value)
    {
        return str_pad($value, 8, '0', STR_PAD_LEFT); // Ajoute des zéros devant si nécessaire
    }
   
    public function personnee()
    {
        return $this->belongsTo(Personne::class, 'idPersonne', 'idUser');
    }
    public function personne()
    {
       return $this->belongsTo(Personne::class, 'idPersonne', 'idUser');
    }
}
 
