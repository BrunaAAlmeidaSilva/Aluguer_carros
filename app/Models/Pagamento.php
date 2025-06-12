<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserva_id',
        'metodo_pagamento',
        'valor',
        'status',
        'transaction_id',
        'data_pagamento'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_pagamento' => 'datetime'
    ];

    /**
     * Um pagamento pertence a uma reserva
     */
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }

    /**
     * Scope para pagamentos processados
     */
    public function scopeProcessados($query)
    {
        return $query->where('status', 'pago');
    }

    /**
     * Scope para pagamentos pendentes
     */
    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

}