<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class Wilaya extends Model{
  
    public $timestamps = false; // désactiver les colonnes created_at et updated_at

    protected $fillable = [
        'name', 
    
    ];

    
 
}
