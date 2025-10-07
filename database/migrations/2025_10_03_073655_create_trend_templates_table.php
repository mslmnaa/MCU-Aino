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
        Schema::create('trend_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 'Healthy Adult', 'Diabetes', 'Hypertension'
            $table->string('description')->nullable();
            $table->json('config'); // JSON structure of all parameter configs
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trend_templates');
    }
};
