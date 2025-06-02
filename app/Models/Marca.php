<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marca';

    protected $fillable = [
        'tipo_bem_id',
        'nome',
        'observacao'
    ];

    /**
     * Uma marca pertence a um tipo de bem
     */
    public function tipoBem()
    {
        return $this->belongsTo(TipoBem::class, 'tipo_bem_id');
    }

    /**
     * Uma marca pode ter vários bens locáveis
     */
    public function bensLocaveis()
    {
        return $this->hasMany(BemLocavel::class, 'marca_id');
    }
}