<?php

namespace App\Http\Controllers;
use App\Models\BemLocavel;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
    //     $veiculos = BemLocavel::disponiveis()->get(); // Só os disponíveis
    // return view('Home.index', compact('veiculos'));

    $veiculos = BemLocavel::all();
    return view('Home.index', compact('veiculos'));
}
    }
