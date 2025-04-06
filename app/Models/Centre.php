<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Commune;
use App\Models\Compagne;

class Centre extends Model
{
    use HasFactory;

    protected $table = 'centres';

    protected $fillable = [ 
        'nom',
        'address',
        'wilaya',
        'commune',
        'localisation',
        'numeroTlp1',
        'numeroTlp2',
        'imageUrl'
    ];

    public $timestamps = true;

    public function communeRelation(){
        return $this->belongsTo(Commune::class, 'commune');
    }
      /**
     * Relation : Un centre peut avoir plusieurs compagnes.
     */
    public function compagnes()
    {
        return $this->hasMany(Compagne::class, 'idCentre');
    }
    
}
