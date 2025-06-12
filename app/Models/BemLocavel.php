<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Models\Marca;
use App\Models\Localizacao;
use App\Models\Caracteristica;
use App\Models\Reserva;


class BemLocavel extends Model
{
    use HasFactory;

    protected $table = 'bens_locaveis';

    protected $fillable = [
        'marca_id',
        'modelo',
        'registo_unico_publico',
        'cor',
        'numero_passageiros',
        'combustivel',
        'numero_portas',
        'transmissao',
        'ano',
        'manutencao',
        'preco_diario',
        'observacao'
    ];

    protected $casts = [
        'preco_diario' => 'decimal:2',
        'manutencao' => 'boolean',
        'numero_passageiros' => 'integer',
        'numero_portas' => 'integer',
        'ano' => 'integer'
    ];

    // Relacionamentos
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function localizacoes()
    {
        return $this->hasMany(Localizacao::class);
    }

    public function caracteristicas()
    {
        return $this->belongsToMany(Caracteristica::class, 'bem_caracteristicas');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    // Scopes para filtros
    public function scopeDisponivel($query, $dataInicio = null, $dataFim = null)
    {
        if ($dataInicio && $dataFim) {
            return $query->whereDoesntHave('reservas', function ($q) use ($dataInicio, $dataFim) {
                $q->where(function ($subQ) use ($dataInicio, $dataFim) {
                    $subQ->where('data_inicio', '<=', $dataFim)
                         ->where('data_fim', '>=', $dataInicio)
                         ->whereIn('status', ['confirmada', 'ativa']);
                });
            });
        }
        return $query;
    }

    
    public function scopeEmManutencao($query, $incluirManutencao = true)
    {
        if (!$incluirManutencao) {
            return $query->where('manutencao', false);
        }
        return $query;
    }

    // Métodos auxiliares
    public function getNomeCompletoAttribute()
    {
        return $this->marca->nome . ' ' . $this->modelo;
    }

    public function getPrecoFormatadoAttribute()
    {
        return '€' . number_format($this->preco_diario, 2, ',', '.');
    }

    public function estaDisponivel($dataInicio, $dataFim)
    {
        if ($this->manutencao) {
            return false;
        }

        return !$this->reservas()
            ->where(function ($query) use ($dataInicio, $dataFim) {
                $query->where('data_inicio', '<=', $dataFim)
                      ->where('data_fim', '>=', $dataInicio)
                      ->whereIn('status', ['confirmada', 'ativa']);
            })
            ->exists();
    }

    public function calcularPrecoTotal($dataInicio, $dataFim)
    {
        $inicio = Carbon::parse($dataInicio);
        $fim = Carbon::parse($dataFim);
        $dias = $fim->diffInDays($inicio);
        
        // Mínimo de 1 dia
        $dias = max(1, $dias);
        
        return $this->preco_diario * $dias;
    }

    public function getImagemAttribute()
    {
        // Lista de imagens disponíveis
        $imagens = [
            'https://images.unsplash.com/photo-1691994877641-36e673ad4236?q=80&w=2072&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1621773968728-994a68ea47b6?q=80&w=1974&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1611580568467-a8e2bb344bbf?q=80&w=1964&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1596429916858-6f97b5b9cf48?q=80&w=1950&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1617469767053-d3b523a0b982?q=80&w=2131&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1597799980291-19f88b1cf1c1?w=600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1711226876715-53a1882660e9?q=80&w=2070&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1696580998112-ebea6a6fad63?w=600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1662981535849-b65888e3ec45?q=80&w=1974&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1691994877641-36e673ad4236?q=80&w=2072&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1621773968728-994a68ea47b6?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1611580568467-a8e2bb344bbf?q=80&w=1964&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1596429916858-6f97b5b9cf48?q=80&w=1950&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1617469767053-d3b523a0b982?q=80&w=2131&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1597799980291-19f88b1cf1c1?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Y2Fycm9zJTIwUkFWNHxlbnwwfHwwfHx8MA%3D%3D',
            'https://images.unsplash.com/photo-1711226876715-53a1882660e9?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1696580998112-ebea6a6fad63?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fGNhcnJvcyUyMENpdmljfGVufDB8fDB8fHww',
            'https://images.unsplash.com/photo-1662981535849-b65888e3ec45?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1609676671207-d021525a635d?q=80&w=1992&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1542046272227-d247df21628a?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1631056846232-46db2e1facec?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1671342352273-4efbc00047ac?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1698267011298-db52f41a0241?q=80&w=2127&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1728060838590-1a2bf3a26ed8?q=80&w=2126&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1666335009171-3ddc17937d6d?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1693260783686-1965e5be2070?q=80&w=1970&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1669881241759-613659cd3c54?q=80&w=2128&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1603233740324-f83f85fa0f08?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1502877338535-766e1452684a?q=80&w=2072&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1550355291-bbee04a92027?q=80&w=1936&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1560009320-c01920eef9f8?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1612390729739-9115a36a7045?q=80&w=1964&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1748215210939-ad8b6c8c086d?q=80&w=2071&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1675782427507-d7cb0cb0868f?q=80&w=1854&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1710593003996-587053ea42f5?q=80&w=1870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1711226876715-53a1882660e9?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1542046272227-d247df21628a?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1666335009171-3ddc17937d6d?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1671342352273-35bf118111b8?q=80&w=1887&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        ];

       
        if (!empty($this->imagem)) {
            return $this->imagem;
        }
        // Associa imagens aos veículos pelo id
        return $imagens[$this->id % count($imagens)];
    }

    // Método estático para busca avançada
    public static function buscarComFiltros($filtros = [])
    {
        $query = self::with(['marca', 'localizacoes', 'caracteristicas']);

        // Filtro por disponibilidade
        if (isset($filtros['data_inicio']) && isset($filtros['data_fim'])) {
            $query->disponivel($filtros['data_inicio'], $filtros['data_fim']);
        }

        // Filtro por localização
        if (isset($filtros['cidade']) || isset($filtros['filial'])) {
            $query->porLocalizacao($filtros['cidade'] ?? null, $filtros['filial'] ?? null);
        }

        // Filtro por preço
        if (isset($filtros['preco_min']) || isset($filtros['preco_max'])) {
            $query->porPreco($filtros['preco_min'] ?? null, $filtros['preco_max'] ?? null);
        }

        // Por padrão, não incluir carros em manutenção
        $incluirManutencao = $filtros['incluir_manutencao'] ?? false;
        $query->emManutencao($incluirManutencao);

        // Ordenação
        $ordenacao = $filtros['ordenar_por'] ?? 'preco_diario';
        $direcao = $filtros['direcao'] ?? 'asc';
        
        if ($ordenacao === 'marca') {
            $query->join('marca', 'bens_locaveis.marca_id', '=', 'marca.id')
                  ->orderBy('marca.nome', $direcao)
                  ->select('bens_locaveis.*');
        } else {
            $query->orderBy($ordenacao, $direcao);
        }

        return $query;
    }
}
