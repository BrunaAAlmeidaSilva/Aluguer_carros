<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bem_caracteristicas', function (Blueprint $table) {
            $table->foreignId('bem_locavel_id')->constrained('bens_locaveis')->onDelete('cascade');
            $table->foreignId('caracteristica_id')->constrained('caracteristicas')->onDelete('cascade');
            $table->primary(['bem_locavel_id', 'caracteristica_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bem_caracteristicas');
    }
};
