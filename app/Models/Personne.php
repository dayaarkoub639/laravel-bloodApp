<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Models\Personne;
use App\Models\Commune;
use App\Models\Don;
use App\Models\Demande;

class Personne extends  Authenticatable
{
    use HasApiTokens, HasFactory,Notifiable;
    protected $table = 'personnes'; // Nom de la table
    protected $primaryKey = 'idUser'; // Clé primaire
    public $timestamps = true; // Active les colonnes created_at et updated_at

    protected $fillable = [
        'NIN',
        'idUser',
        'numeroTlp1',
        'numeroTlp2',
        'accept_sendsms',
        'motDePasse',
        'dateDeNess',
        'nom',
        'prenom',
        'gender',
        'typePersonne',
        'latitude',
        'longitude',
        'lieuNaissance',
        'epouseDe',

        'dernierDateDon',
        'serologie',
        'idGroupage',
        'wilaya_prof_id'  ,
        'commune_prof_id'  ,
        'wilaya_domicile_id' ,
        'commune_domicile_id' ,
        'adresse',
        'adressePro',
        'observations',
        'cMaj',
        'cMin',
        'eMaj',
        'eMin',
        'kell'
    ];


    protected $hidden = [
        'motDePasse', // Cache le mot de passe
    ];

    public function getIdUserAttribute($value)
    {
        return str_pad($value, 8, '0', STR_PAD_LEFT); // Ajoute des zéros devant si nécessaire
    }

      public function personneMedical()
    {
        return $this->hasOne(PersonneMedical::class, 'idPersonne', 'idUser');
    }
      // Relation one-to-one avec le modèle User
      public function user()
      {
          return $this->hasOne(User::class, 'keyIdUser', 'idUser');
      }
    // Relation belongsTo avec le modèle Groupage
    public function groupage()
    {
        return $this->belongsTo(Groupage::class, 'idGroupage', 'id');
    }

    public function communeDomicile()
    {
        return $this->belongsTo(Commune::class, 'commune_domicile_id', 'id');
    }
    public function communePro()
    {
        return $this->belongsTo(Commune::class, 'commune_prof_id', 'id');
    }
    public function dons()
    {
        return $this->hasMany(Don::class, 'idDonneur', 'idUser');
    }

    public function demandes()
    {
        return $this->belongsToMany(Demande::class, 'demande_personne', 'personne_id', 'demande_id')
        ->withPivot('date_acceptation')
        ->withTimestamps();
    }
}
