<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_xx_xx_create_footer_socials_table.php

public function up()
{
    Schema::create('footer_socials', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('icono'); // clase del ícono (ej: "bi bi-facebook")
        $table->string('url');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_socials');
    }
};
