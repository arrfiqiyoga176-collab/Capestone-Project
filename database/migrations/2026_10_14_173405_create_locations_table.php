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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
            $table->string('slug')->unique();
            $table->string('alamat_kawasan');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->text('deskripsi');
            $table->string('jam_operasional')->default('08:00 - 18:00 WIB');
            $table->string('kondisi_cuaca', 50)->default('Cerah');
            $table->integer('kecepatan_angin_knot')->default(10);
            $table->string('arah_angin', 50)->default('Barat Daya');
            $table->decimal('tinggi_gelombang_meter', 4, 2)->default(1.0);
            $table->enum('tingkat_risiko', ['Rendah', 'Sedang', 'Tinggi'])->default('Rendah');
            $table->integer('skor_fasilitas_c3')->default(80);
            $table->integer('skor_keamanan_c4')->default(85);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
