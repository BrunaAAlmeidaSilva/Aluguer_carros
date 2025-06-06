<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->string('referencia_multibanco', 20)->nullable()->after('observacoes');
            $table->string('entidade_multibanco', 5)->default('12345')->after('referencia_multibanco');
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn(['referencia_multibanco', 'entidade_multibanco']);
        });
    }
};
