<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'morada',
        'telefone',
        'data_nascimento',
        'nif',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     /**
     * Um usuário pode ter várias reservas
     */
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    /**
     * Reservas ativas do usuário
     */
    public function reservasAtivas()
    {
        return $this->hasMany(Reserva::class)->whereIn('status', ['confirmada', 'ativa']);
    }

    /**
     * Histórico de reservas do usuário
     */
    public function historicoReservas()
    {
        return $this->hasMany(Reserva::class)->orderBy('created_at', 'desc');
    }

    /**
     * Pagamentos do usuário através das reservas
     */
    public function pagamentos()
    {
        return $this->hasManyThrough(Pagamento::class, Reserva::class);
    }
}




