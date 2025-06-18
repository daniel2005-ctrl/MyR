<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('subsidios_creditos', function (Blueprint $table) {
            $table->boolean('activo')->default(1);
        });
    }
    public function down() {
        Schema::table('subsidios_creditos', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
};

return new class extends Migration {
    public function up() {
        Schema::table('subsidios_creditos', function (Blueprint $table) {
            $table->boolean('activo')->default(1);
        });
    }
    public function down() {
        Schema::table('subsidios_creditos', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
};