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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('share_id');
            $table->string('name');
            $table->date('tanggal_lahir');
            $table->integer('umur');
            $table->string('departemen');
            $table->string('jabatan');
            $table->text('riwayat_kebiasaan_hidup');
            $table->boolean('merokok');
            $table->boolean('minum_alkohol');
            $table->boolean('olahraga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
