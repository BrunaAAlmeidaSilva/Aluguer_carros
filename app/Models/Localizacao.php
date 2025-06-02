<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localizacao extends Model
{
    use HasFactory;

    protected $table = 'localizacoes';

    protected $fillable = [
        'bem_locavel_id',
        'cidade',
        'filial',
        'posicao'
    ];

    /**
     * Uma localização pertence a um bem locável
     */
    public function bemLocavel()
    {
        return $this->belongsTo(BemLocavel::class, 'bem_locavel_id');
    }

    /**
     * Scope para filtrar por cidade
     */
    public function scopeCidade($query, $cidade)
    {
        return $query->where('cidade', 'like', '%' . $cidade . '%');
    }

    /**
     * Scope para filtrar por filial
     */
    public function scopeFilial($query, $filial)
    {
        return $query->where('filial', $filial);
    }
}