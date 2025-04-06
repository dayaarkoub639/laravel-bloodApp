<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pseudo extends Model
{
    use HasFactory;

    protected $table = 'pseudos';

    protected $fillable = ['name']; // Permet l'ajout et la modification de la colonne 'name'
}
