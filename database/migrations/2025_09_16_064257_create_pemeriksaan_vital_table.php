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
        Schema::create('pemeriksaan_vital', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('berat_badan')->nullable();
            $table->string('tinggi_badan')->nullable();
            $table->string('lingkar_perut')->nullable();
            $table->string('bmi')->nullable();
            $table->string('klasifikasi_tekanan_darah')->nullable();
            $table->string('klasifikasi_od')->nullable();
            $table->string('klasifikasi_os')->nullable();
            $table->string('persepsi_warna')->nullable();
            $table->text('pemeriksaan_fisik_umum')->nullable();
            $table->text('kesimpulan_fisik')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->text('saran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_vital');
    }
};
