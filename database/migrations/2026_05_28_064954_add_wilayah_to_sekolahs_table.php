<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sekolahs', function (Blueprint $table) {

            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('sekolahs', function (Blueprint $table) {

            $table->dropColumn([
                'provinsi',
                'kabupaten',
                'kecamatan'
            ]);

        });
    }
};