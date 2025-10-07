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
        Schema::create('patient_trend_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->string('parameter_name'); // 'cholesterol', 'hemoglobin', etc
            $table->string('exam_type'); // 'labProfilLemak', 'labHematologi', etc
            $table->enum('trend_above_normal', ['red', 'green'])->default('red');
            $table->enum('trend_below_normal', ['red', 'green'])->default('red');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure one config per patient per parameter
            $table->unique(['patient_id', 'parameter_name', 'exam_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_trend_configs');
    }
};
