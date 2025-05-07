<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('proyectos', function (Blueprint $table) {
        $table->id('id_pro');
        $table->string('nombre_pro');
        $table->string('tipo_pro');
        $table->text('descripcion');
        $table->boolean('terminado')->default(true);
        $table->decimal('precio_min', 10, 2)->nullable();
        $table->decimal('precio_max', 10, 2)->nullable();
        $table->decimal('area_min', 10, 2);
        $table->decimal('area_max', 10, 2)->nullable();
        $table->string('ubicacion_pro'); 
        $table->text('mapa');
        $table->json('imagenes_pro');  // Para imágenes en formato JSON
        $table->json('videos_pro')->nullable();  // Si en el futuro se añaden videos
        $table->foreignId('id_tipo_permiso')->constrained('tipo_permisos');  // Relación con tipo_permiso
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
