<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('proyectos', function (Blueprint $table) {
        $table->foreign('id_estado')->references('id_estado')->on('estados');
    });
}

public function down()
{
    Schema::table('proyectos', function (Blueprint $table) {
        $table->dropForeign(['id_estado']);
    });
}
};
