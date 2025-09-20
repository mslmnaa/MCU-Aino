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
        Schema::create('lab_hematologi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('hematologi')->nullable();
            $table->string('hemoglobin')->nullable();
            $table->string('erytrosit')->nullable();
            $table->string('hematokrit')->nullable();
            $table->string('mcv')->nullable();
            $table->string('mch')->nullable();
            $table->string('mchc')->nullable();
            $table->string('rdw')->nullable();
            $table->string('leukosit')->nullable();
            $table->string('eosinofil')->nullable();
            $table->string('basofil')->nullable();
            $table->string('neutrofil_batang')->nullable();
            $table->string('neutrofil_segmen')->nullable();
            $table->string('limfosit')->nullable();
            $table->string('monosit')->nullable();
            $table->string('trombosit')->nullable();
            $table->string('laju_endap_darah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_hematologi');
    }
};
