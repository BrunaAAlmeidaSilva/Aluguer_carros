<?php
namespace App\Http\Controllers;

use App\Models\BemLocavel;
use App\Models\Marca;
use App\Models\Localizacao;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Http\Requests\BemLocavelRequest;
use App\Http\Requests\BuscarBemLocavelRequest;
use App\Http\Requests\VerificarDisponibilidadeRequest;
use App\Http\Requests\ListarPorLocalizacaoRequest;
use App\Http\Requests\EstatisticasRequest;
use App\Http\Requests\ProcessarFiltrosRequest;
use App\Http\Requests\DetalhesBemLocavelRequest;


class BemLocavelController extends Controller
{
    // Exibir listagem de bens locáveis
    public function index(Request $request): View
    {
        $filtros = $this->processarFiltros($request);
        $bensLocaveis = BemLocavel::buscarComFiltros($filtros)
            ->paginate(12)
            ->appends($request->query());

        // Dados para filtros
        $marcas = Marca::orderBy('nome')->get();
        $localizacoes = Localizacao::select('cidade', 'filial')
            ->distinct()
            ->orderBy('cidade')
            ->orderBy('filial')
            ->get();
        $precoMinimo = BemLocavel::min('preco_diario') ?? 0;
        $precoMaximo = BemLocavel::max('preco_diario') ?? 1000;

        return view('bemLocavel.index', compact(
            'bensLocaveis', 'marcas', 'localizacoes', 
            'precoMinimo', 'precoMaximo', 'filtros'
        ));
    }

    // Buscar veículos com filtros
    public function search(Request $request): View
    {
        $filtros = $this->processarFiltros($request);
        $bensLocaveis = BemLocavel::buscarComFiltros($filtros)->paginate(32);

        return view('bemLocavel.resultados', compact('bensLocaveis', 'filtros'));
    }

    // Verificar disponibilidade de um veículo
    public function verificarDisponibilidade(Request $request, $id): View
    {
        $request->validate([
            'data_inicio' => 'required|date|after_or_equal:today',
            'data_fim' => 'required|date|after:data_inicio'
        ]);

        $bemLocavel = BemLocavel::findOrFail($id);
        $disponivel = $bemLocavel->estaDisponivel($request->data_inicio, $request->data_fim);
        $precoTotal = $disponivel ? $bemLocavel->calcularPrecoTotal($request->data_inicio, $request->data_fim) : null;

        return view('bemLocavel.disponibilidade', compact(
            'bemLocavel', 'disponivel', 'precoTotal'
        ));
    }

    // Detalhes do veículo
    public function show($id): View
    {
        $bemLocavel = BemLocavel::with(['marca', 'localizacoes', 'caracteristicas'])
            ->findOrFail($id);

        return view('bemLocavel.show', compact('bemLocavel'));
    }

    // Listar veículos por localização
    public function porLocalizacao($cidade, $filial = null): View
    {
        $veiculos = BemLocavel::porLocalizacao($cidade, $filial)
            ->emManutencao(false)
            ->with(['marca', 'localizacoes'])
            ->get();

        return view('bemLocavel.localizacao', compact('veiculos', 'cidade', 'filial'));
    }

    // Estatísticas do catálogo
    // public function estatisticas(): View
    // {
    //     $stats = [
    //         'total_veiculos' => BemLocavel::count(),
    //         'disponiveis' => BemLocavel::emManutencao(false)->count(),
    //         'em_manutencao' => BemLocavel::where('manutencao', true)->count(),
    //         'preco_medio' => BemLocavel::avg('preco_diario'),
    //     ];

    //     return view('bemLocavel.estatisticas', compact('stats'));
    // }

    public function carrosEscolha(Request $request)
    {
        $marcas = \App\Models\Marca::orderBy('nome')->get();
        $precoMinimo = \App\Models\BemLocavel::min('preco_diario') ?? 0;
        $precoMaximo = \App\Models\BemLocavel::max('preco_diario') ?? 1000;

        $dataInicio = $request->input('data_hora_levantamento') ? substr($request->input('data_hora_levantamento'), 0, 10) : null;
        $dataFim = $request->input('data_hora_devolucao') ? substr($request->input('data_hora_devolucao'), 0, 10) : null;

        $query = \App\Models\BemLocavel::with('marca')->emManutencao(false);
        if ($dataInicio && $dataFim) {
            $query->disponivel($dataInicio, $dataFim);
        }
        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->input('marca_id'));
        }
        if ($request->filled('preco_min')) {
            $query->where('preco_diario', '>=', $request->input('preco_min'));
        }
        if ($request->filled('preco_max')) {
            $query->where('preco_diario', '<=', $request->input('preco_max'));
        }
        $bensLocaveis = $query->get();

        return view('CarrosEscolha.index', compact('bensLocaveis', 'marcas', 'precoMinimo', 'precoMaximo'));
    }
}
