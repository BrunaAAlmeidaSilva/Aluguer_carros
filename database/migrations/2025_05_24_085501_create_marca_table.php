<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_bem_id')->constrained('tipo_bens')->onDelete('cascade');
            $table->string('nome', 100);
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marca');
    }
};
