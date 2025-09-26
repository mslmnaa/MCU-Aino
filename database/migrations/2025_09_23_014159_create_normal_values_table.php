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
        Schema::create('normal_values', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // e.g., 'hematologi', 'fungsi_liver'
            $table->string('parameter'); // e.g., 'hemoglobin', 'sgot'
            $table->text('value'); // e.g., '12-16 g/dL'
            $table->string('unit')->nullable(); // e.g., 'g/dL', 'U/L'
            $table->timestamps();

            $table->unique(['category', 'parameter']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('normal_values');
    }
};
