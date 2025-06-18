<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
/**
* Run the migrations.
*/
public function up(): void
{
Schema::create('nosotros', function (Blueprint $table) {
$table->id();
$table->string('titulo')->nullable();
$table->text('introduccion')->nullable();
$table->text('vision')->nullable();
$table->text('mision')->nullable();
$table->text('valores')->nullable();
$table->timestamps();
});
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::dropIfExists('nosotros');
}

};