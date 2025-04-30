<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id('id_pro');
            $table->string('nombre_pro', 30);
            $table->enum('tipo_pro', ['vis', 'no vis', 'vip']);
            $table->string('tamano_pro', 30);
            $table->text('descripcion');
            $table->tinyInteger('terminado');
            $table->string('imagenes_pro', 255);
            $table->string('videos_pro', 255);
            $table->string('ubicacion_pro', 30);
            $table->unsignedBigInteger('id_usu');
        
            $table->foreign('id_usu')->references('id')->on('usuarios')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
