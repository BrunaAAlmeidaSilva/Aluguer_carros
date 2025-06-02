<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->constrained('reservas')->onDelete('cascade');
            $table->enum('metodo_pagamento', ['cartao_credito', 'mbway', 'paypal', 'transferencia', 'dinheiro']);
            $table->decimal('valor', 10, 2);
            $table->enum('status', ['pendente', 'pago', 'falhado', 'reembolsado'])->default('pendente');
            $table->string('transaction_id', 100)->nullable();
            $table->timestamp('data_pagamento')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};