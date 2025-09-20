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
        Schema::create('lab_urine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('warna')->nullable();
            $table->string('kejernihan')->nullable();
            $table->string('bj')->nullable();
            $table->string('ph')->nullable();
            $table->string('protein')->nullable();
            $table->string('glukosa')->nullable();
            $table->string('keton')->nullable();
            $table->string('bilirubin')->nullable();
            $table->string('urobilinogen')->nullable();
            $table->string('nitrit')->nullable();
            $table->string('darah')->nullable();
            $table->string('lekosit_esterase')->nullable();
            $table->string('eritrosit_sedimen')->nullable();
            $table->string('lekosit_sedimen')->nullable();
            $table->string('epitel_sedimen')->nullable();
            $table->string('kristal_sedimen')->nullable();
            $table->string('silinder_sedimen')->nullable();
            $table->string('lain_lain_sedimen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_urine');
    }
};
