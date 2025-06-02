<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bem_locavel_id')->constrained('bens_locaveis')->onDelete('cascade');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->decimal('preco_total', 10, 2);
            $table->enum('status', ['pendente', 'confirmada', 'ativa', 'finalizada', 'cancelada'])->default('pendente');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};