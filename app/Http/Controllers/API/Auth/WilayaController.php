<?php

namespace App\Http\Controllers\API\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Wilaya;

class WilayaController extends Controller
{
    public function getWilayas(): JsonResponse
    {
        $wilayas = Wilaya::all();
        return response()->json($wilayas);
    }
    // Méthode pour obtenir les communes par wilaya
    public function getCommunesByWilaya($wilayaCode)
    {
        $communes = DB::table('communes')
            ->where('wilaya_code', $wilayaCode)
            ->get(['id', 'commune_name', 'daira_name', 'wilaya_code', 'wilaya_name']);

        return response()->json($communes);
    }
   
 

}
