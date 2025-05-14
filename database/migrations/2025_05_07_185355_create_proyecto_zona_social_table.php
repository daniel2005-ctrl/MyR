<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyectoZonaSocialTable extends Migration
{
    public function up()
    {
        Schema::create('proyecto_zona_social', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos', 'id_pro')->onDelete('cascade');
            $table->foreignId('zona_catalogo_id')->constrained('zonas_catalogo')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proyecto_zona_social');
    }
}
