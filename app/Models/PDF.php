<?php

namespace App\Models;

class PDF
{
    /**
     * Gera um array de dados relevantes para PDF de uma reserva.
     * Pode ser chamado a partir do controller para passar para a view do PDF.
     */
    public static function dadosReserva(Reserva $reserva)
    {
        $bem = $reserva->bemLocavel;
        $user = $reserva->user;
        return [
            'id' => $reserva->id,
            'nome_cliente' => $user ? $user->name : '-',
            'email_cliente' => $user ? $user->email : '-',
            'veiculo' => $bem ? ($bem->marca->nome . ' ' . $bem->modelo) : '-',
            'matricula' => $bem ? $bem->registo_unico_publico : '-',
            'cor' => $bem ? $bem->cor : '-',
            'combustivel' => $bem ? $bem->combustivel : '-',
            'transmissao' => $bem ? $bem->transmissao : '-',
            'ano' => $bem ? $bem->ano : '-',
            'data_inicio' => $reserva->data_inicio ? $reserva->data_inicio->format('d/m/Y') : '-',
            'data_fim' => $reserva->data_fim ? $reserva->data_fim->format('d/m/Y') : '-',
            'preco_total' => $reserva->preco_total,
            'entidade_multibanco' => $reserva->entidade_multibanco,
            'referencia_multibanco' => $reserva->referencia_multibanco,
            'status' => $reserva->status,
            'observacoes' => $reserva->observacoes,
        ];
    }
}
