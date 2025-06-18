<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->text('valor')->nullable();
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
        
        // Insertar configuración por defecto
        \Illuminate\Support\Facades\DB::table('configuraciones')->insert([
            'clave' => 'imagen_fondo_index',
            'valor' => 'imagenes/Fondo 72.jpg',
            'descripcion' => 'Imagen de fondo del index principal',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('configuraciones');
    }
};