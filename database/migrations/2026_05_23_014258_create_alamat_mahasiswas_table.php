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
        Schema::create('alamat_mahasiswas', function (Blueprint $table) {
            $table->id();
            // Menghubungkan alamat ke user/mahasiswa login
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Kolom Dropdown Wilayah
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('desa')->nullable();
            
            // Kolom Detail & Koordinat Peta Leaflet
            $table->text('detail_alamat')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamat_mahasiswas');
    }
};