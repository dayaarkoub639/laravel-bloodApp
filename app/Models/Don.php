<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Campagne;
use App\Models\Personne;

class Don extends Model
{
    use HasFactory;

    protected $table = 'dons'; // Nom de la table

    protected $primaryKey = 'idDon'; // Clé primaire personnalisée

    public $timestamps = false; // Désactiver les timestamps (created_at, updated_at)

    protected $fillable = [
        'idCompagnes',
        'date',
        'heure',
        'support',
        'Pds',
        'C',
        'TA',
        'serologie',
        'observations',
        'groupage',
        'lieuDon',
        'eMaj',
        'cMin',
        'eMin',
        'kell',
        'obsMedicale',
        'numeroFlacon',
        'reactions', 
        'sourceDon',
        'donIsNote',
        'idDonneur',
        'idPersonneMedicale',
        'persMedicaleSuperviser'
    ];
    public function getIdDonneurAttribute($value)
    {
        return str_pad($value, 8, '0', STR_PAD_LEFT); // Ajoute des zéros devant si nécessaire
    }
    public function getNumeroFlaconAttribute($value)
    {
        return str_pad($value, 8, '0', STR_PAD_LEFT); // Ajoute des zéros devant si nécessaire
    }
  
    // Relation avec la campagne (si elle existe)
    public function campagne()
    {
        return $this->belongsTo(Campagne::class, 'idCompagnes');
    }

    // Relation avec le donneur (si elle existe)
  /*  public function user()
    {
        return $this->belongsTo(User::class, 'idDonneur', 'idUser');
    }*/
    public function donneur()
    {
        return $this->belongsTo(Personne::class, 'idDonneur', 'idUser');
    }
}
