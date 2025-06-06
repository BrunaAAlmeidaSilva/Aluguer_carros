<?php

namespace App\Http\Controllers;
use App\Models\BemLocavel;
use App\Models\Localizacao;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $veiculos = BemLocavel::all();
        // Buscar filiais únicas da tabela localizacoes
        $filiais = Localizacao::select('filial')->distinct()->orderBy('filial')->pluck('filial');
        return view('Home.index', compact('veiculos', 'filiais'));
    }
}
