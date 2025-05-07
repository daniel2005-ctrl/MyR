<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('redes_sociales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('url');
            $table->string('icono'); // Ej: 'fab fa-facebook-f'
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('redes_sociales');
    }
};
