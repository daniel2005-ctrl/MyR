<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionesTable extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();

            // Si la tabla usuarios tiene PK 'id' y proyectos tiene PK 'id_pro'
            $table->unsignedBigInteger('id_usuarios');
            $table->unsignedBigInteger('id_pro');

            // Definimos claves foráneas con columnas personalizadas
            $table->foreign('id_usuarios')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_pro')->references('id_pro')->on('proyectos')->onDelete('cascade');

            // ahorro y meses elegidos
            $table->decimal('ahorro', 12, 2)->default(0);
            $table->integer('meses');

            // valores finales en pesos
            $table->decimal('valor_apartamento', 12, 2);
            $table->decimal('valor_parqueadero', 12, 2);

            // resultados del cálculo
            $table->decimal('cuota_inicial', 12, 2);
            $table->decimal('cuota_separacion', 12, 2);
            $table->decimal('cuota_inicial_parqueadero', 12, 2);
            $table->decimal('cuota_separacion_parqueadero', 12, 2);
            $table->decimal('cuota_mensual', 12, 2);

            $table->boolean('con_subsidio')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
}
