<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoBem extends Model
{
    use HasFactory;

    protected $table = 'tipo_bens';

    protected $fillable = [
        'nome'
    ];

    /**
     * Uma tipo de bem pode ter várias marcas
     */
    public function marcas()
    {
        return $this->hasMany(Marca::class, 'tipo_bem_id');
    }

    /**
     * Obter todos os bens locáveis através das marcas
     */
    public function bensLocaveis()
    {
        return $this->hasManyThrough(BemLocavel::class, Marca::class, 'tipo_bem_id', 'marca_id');
    }
}