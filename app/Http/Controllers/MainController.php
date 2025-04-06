<?php

namespace App\Http\Controllers;
use App\Models\Groupage;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(){
        $listgrp=Groupage::all();
   
        return view ('index',compact("listgrp"));
    }
}
