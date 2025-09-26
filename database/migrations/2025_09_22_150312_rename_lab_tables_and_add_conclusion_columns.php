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
        // Rename tables and add conclusion columns

        // 1. lab_hematologi -> hasil_hematologi
        Schema::rename('lab_hematologi', 'hasil_hematologi');
        Schema::table('hasil_hematologi', function (Blueprint $table) {
            $table->text('kesimpulan_hematologi')->nullable()->after('laju_endap_darah');
        });

        // 2. lab_urine -> hasil_urine
        Schema::rename('lab_urine', 'hasil_urine');
        Schema::table('hasil_urine', function (Blueprint $table) {
            $table->text('kesimpulan_urine')->nullable()->after('lain_lain_sedimen');
        });

        // 3. lab_fungsi_liver -> hasil_fungsi_hati
        Schema::rename('lab_fungsi_liver', 'hasil_fungsi_hati');
        Schema::table('hasil_fungsi_hati', function (Blueprint $table) {
            $table->text('kesimpulan_fungsi_hati')->nullable()->after('sgpt');
        });

        // 4. lab_profil_lemak -> hasil_profil_lemak
        Schema::rename('lab_profil_lemak', 'hasil_profil_lemak');
        Schema::table('hasil_profil_lemak', function (Blueprint $table) {
            $table->text('kesimpulan_profil_lemak')->nullable()->after('ldl_cholesterol');
        });

        // 5. lab_fungsi_ginjal -> hasil_fungsi_ginjal
        Schema::rename('lab_fungsi_ginjal', 'hasil_fungsi_ginjal');
        Schema::table('hasil_fungsi_ginjal', function (Blueprint $table) {
            $table->text('kesimpulan_fungsi_ginjal')->nullable()->after('asam_urat');
        });

        // 6. lab_glukosa_darah -> hasil_glukosa
        Schema::rename('lab_glukosa_darah', 'hasil_glukosa');
        Schema::table('hasil_glukosa', function (Blueprint $table) {
            $table->text('kesimpulan_glukosa')->nullable()->after('hba1c');
        });

        // 7. lab_penanda_tumor -> hasil_penanda_tumor
        Schema::rename('lab_penanda_tumor', 'hasil_penanda_tumor');
        Schema::table('hasil_penanda_tumor', function (Blueprint $table) {
            $table->text('kesimpulan_penanda_tumor')->nullable()->after('cea');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove conclusion columns and rename tables back

        // 7. hasil_penanda_tumor -> lab_penanda_tumor
        Schema::table('hasil_penanda_tumor', function (Blueprint $table) {
            $table->dropColumn('kesimpulan_penanda_tumor');
        });
        Schema::rename('hasil_penanda_tumor', 'lab_penanda_tumor');

        // 6. hasil_glukosa -> lab_glukosa_darah
        Schema::table('hasil_glukosa', function (Blueprint $table) {
            $table->dropColumn('kesimpulan_glukosa');
        });
        Schema::rename('hasil_glukosa', 'lab_glukosa_darah');

        // 5. hasil_fungsi_ginjal -> lab_fungsi_ginjal
        Schema::table('hasil_fungsi_ginjal', function (Blueprint $table) {
            $table->dropColumn('kesimpulan_fungsi_ginjal');
        });
        Schema::rename('hasil_fungsi_ginjal', 'lab_fungsi_ginjal');

        // 4. hasil_profil_lemak -> lab_profil_lemak
        Schema::table('hasil_profil_lemak', function (Blueprint $table) {
            $table->dropColumn('kesimpulan_profil_lemak');
        });
        Schema::rename('hasil_profil_lemak', 'lab_profil_lemak');

        // 3. hasil_fungsi_hati -> lab_fungsi_liver
        Schema::table('hasil_fungsi_hati', function (Blueprint $table) {
            $table->dropColumn('kesimpulan_fungsi_hati');
        });
        Schema::rename('hasil_fungsi_hati', 'lab_fungsi_liver');

        // 2. hasil_urine -> lab_urine
        Schema::table('hasil_urine', function (Blueprint $table) {
            $table->dropColumn('kesimpulan_urine');
        });
        Schema::rename('hasil_urine', 'lab_urine');

        // 1. hasil_hematologi -> lab_hematologi
        Schema::table('hasil_hematologi', function (Blueprint $table) {
            $table->dropColumn('kesimpulan_hematologi');
        });
        Schema::rename('hasil_hematologi', 'lab_hematologi');
    }
};
