<?php

namespace App\Models;

use App\Models\Personne;

class User extends Personne
{
    protected $table = 'users'; // Nom de la table pour `users`
    protected $primaryKey = 'id'; // Clé primaire
    public $timestamps = true; // Active les colonnes created_at et updated_at

    protected $fillable = [
        'pseudo', 
        'keyIdUser',
        'noteEtoile'
    ];
    public function getKeyIdUserAttribute($value)
    {
        return str_pad($value, 8, '0', STR_PAD_LEFT); // Ajoute des zéros devant si nécessaire
    }
    public function personne()
    {
       return $this->belongsTo(Personne::class, 'keyIdUser', 'idUser');
    }
    public function hasDonated()
    {
        return $this->dons()->exists();
    }
    public function demandes()
    {
        return $this->hasMany(Demande::class, 'idDemandeur');
    }
}