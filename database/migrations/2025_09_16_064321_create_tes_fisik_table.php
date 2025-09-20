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
        Schema::create('tes_fisik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('smell_test')->nullable();
            $table->string('low_back_pain')->nullable();
            $table->string('laseque_test')->nullable();
            $table->string('bragard_test')->nullable();
            $table->string('patrict_test')->nullable();
            $table->string('kontra_patrict')->nullable();
            $table->string('neer_sign')->nullable();
            $table->string('range_of_motion')->nullable();
            $table->string('speed_test')->nullable();
            $table->string('straight_leg_raised_test')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tes_fisik');
    }
};
