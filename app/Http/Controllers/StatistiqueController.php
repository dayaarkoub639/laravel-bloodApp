<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    public function statistique(){
        return view('stats.statistiques');
    }
}
