<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome'
    ];

    /**
     * Uma característica pode pertencer a vários bens locáveis (many-to-many)
     */
    public function bensLocaveis()
    {
        return $this->belongsToMany(
            BemLocavel::class,
            'bem_caracteristicas',
            'caracteristica_id',
            'bem_locavel_id'
        )->withTimestamps();
    }
}