<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subsidios_creditos', function (Blueprint $table) {
            $table->enum('tipo', ['subsidio', 'credito'])->default('subsidio');
            $table->string('nombre');
            $table->string('url')->nullable();
            $table->integer('orden')->default(0);
        });
    }

    public function down()
    {
        Schema::table('subsidios_creditos', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'nombre', 'url', 'orden']);
        });
    }
};
