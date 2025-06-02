<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('localizacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bem_locavel_id')->nullable()->constrained('bens_locaveis')->onDelete('cascade');
            $table->string('cidade', 100);
            $table->string('filial', 100)->nullable();
            $table->string('posicao', 100);
            $table->timestamps();
            
            $table->unique(['filial', 'posicao']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('localizacoes');
    }
};
