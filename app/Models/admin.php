<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class Admin extends Model{
  
    public $timestamps = true; 
    
    protected $table = 'admin'; // Nom de la table
    protected $primaryKey = 'id';  

    protected $fillable = [
        'pseudo', 
        "keyIdUser"
        ,"idPersonne"
        ,"acces" ,
        'idCentre'
    ];

    public function getKeyIdUserAttribute($value)
    {
        return str_pad($value, 8, '0', STR_PAD_LEFT); // Ajoute des zéros devant si nécessaire
    }
 
}
