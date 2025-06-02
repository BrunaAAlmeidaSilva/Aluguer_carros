<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bens_locaveis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marca_id')->constrained('marca')->onDelete('cascade');
            $table->string('modelo', 100)->nullable();
            $table->string('registo_unico_publico', 20)->nullable();
            $table->string('cor', 20)->nullable();
            $table->integer('numero_passageiros')->nullable();
            $table->enum('combustivel', ['gasolina', 'diesel', 'elétrico', 'híbrido', 'outro']);
            $table->integer('numero_portas')->nullable();
            $table->enum('transmissao', ['manual', 'automática'])->nullable();
            $table->integer('ano')->nullable();
            $table->boolean('manutencao')->default(true);
            $table->decimal('preco_diario', 10, 2)->nullable();
            $table->string('observacao', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bens_locaveis');
    }
};
