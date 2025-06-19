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
            $table->json('descripcion')->nullable();  // Cambiar a json para varios párrafos
            $table->boolean('terminado')->default(true);
            $table->json('precio')->nullable();  // Agrupar precio min y max en un solo campo
            $table->json('area')->nullable();  // Agrupar area min y max en un solo campo
            $table->string('ubicacion_pro');
            $table->json('imagenes_pro');
            $table->json('videos_pro')->nullable();
            $table->json('imagenes_header')->nullable();  // Campo para las imágenes del header
            $table->json('apartamentos')->nullable();  // Campo para almacenar imágenes de apartamentos
            $table->foreignId('id_tipo_permiso')->constrained('tipo_permisos');
            $table->unsignedBigInteger('visitas')->default(0);
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
