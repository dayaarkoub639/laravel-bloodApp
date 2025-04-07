<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Commune;

class Compagne extends Model
{
    use HasFactory;

    protected $table = 'compagnes';
    public $timestamps = true;
    protected $fillable = [
        'idCentre',
        'address',
        'wilaya',
        'commune',
        'etablissement',
        'heureDebut',
        'heureFin',
        'dateDebut',
        'dateFin',
    ];


    public function communeRelation()
    {
        return $this->belongsTo(Commune::class, 'commune');
    }
      /**
     * Relation : Une compagne appartient à un seul centre.
     */
    public function centre()
    {
        return $this->belongsTo(Centre::class, 'idCentre');
    }
    
}
