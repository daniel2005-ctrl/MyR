<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
{
    Schema::table('proyectos', function (Blueprint $table) {
        $table->string('ubicacion_pro', 255)->change(); // Limita a 255 caracteres
        $table->string('mapa')->nullable()->after('ubicacion_pro'); // Añade la columna `mapa` como nullable
    });
}

public function down()
{
    Schema::table('proyectos', function (Blueprint $table) {
        $table->text('ubicacion_pro')->change(); // Reversión a tipo `text`
        $table->dropColumn('mapa');
    });
}
};  
