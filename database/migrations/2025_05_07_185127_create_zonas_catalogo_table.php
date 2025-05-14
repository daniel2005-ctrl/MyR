<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZonasCatalogoTable extends Migration
{
    public function up()
    {
        Schema::create('zonas_catalogo', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('icono');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('zonas_catalogo');
    }
}
