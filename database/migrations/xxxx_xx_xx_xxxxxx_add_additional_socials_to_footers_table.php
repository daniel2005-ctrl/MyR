<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('footers', function (Blueprint $table) {
            $table->json('additional_socials')->nullable()->after('facebook_icon');
        });
    }

    public function down()
    {
        Schema::table('footers', function (Blueprint $table) {
            $table->dropColumn('additional_socials');
        });
    }
};