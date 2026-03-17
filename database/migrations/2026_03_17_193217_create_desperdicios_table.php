<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desperdicios', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo_residuo', [
                'comida_pronta',
                'insumo_cru',
                'embalagem',
            ]);

            $table->decimal('peso', 8, 2); // kg

            $table->enum('origem', ['interno', 'cliente']);

            $table->text('observacao')->nullable();

            $table->date('data')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desperdicios');
    }
};
