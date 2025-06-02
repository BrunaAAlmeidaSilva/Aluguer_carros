<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BemLocavel extends Model
{
    use HasFactory;

    protected $table = 'bens_locaveis'; // Define a tabela no banco de dados
    
    // Campos que podem ser preenchidos
    protected $fillable = [
        'marca_id',
        'modelo',
        'registo_unico_publico',
        'numero_quartos',
        'numero_hospedes',
        'numero_casas_banho',
        'numero_camas',
        'ano',
        'manutencao',
        'preco_diario',
        'observacao'
    ]; 

    // Cast para conversão de tipos
    protected $casts = [
        'manutencao' => 'boolean',
        'preco_diario' => 'decimal:2'
    ];

    /**
     * Relação com as reservas deste bem locável.
     */
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'bem_locavel_id');
    }

    /**
     * Escopo para filtrar bens disponíveis.
     */public function scopeDisponiveis($query)
{
    return $query->where('manutencao', true);
}

    /**
     * Escopo para filtrar bens em manutenção.
     */
    public function scopeEmManutencao($query)
    {
        return $query->where('manutencao', false);
    }

    /**
     * Escopo para filtrar bens por marca.
     */
    public function scopePorMarca($query, $marcaId)
    {
        return $query->where('marca_id', $marcaId);
    }
}
