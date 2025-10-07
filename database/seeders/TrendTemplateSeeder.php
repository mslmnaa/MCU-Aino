<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrendTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Healthy Adult',
                'description' => 'Default configuration for healthy adults with no chronic conditions',
                'is_default' => true,
                'config' => [
                    // Hematologi
                    'hemoglobin' => ['exam_type' => 'labHematologi', 'above' => 'green', 'below' => 'red'],
                    'erytrosit' => ['exam_type' => 'labHematologi', 'above' => 'green', 'below' => 'red'],
                    'hematokrit' => ['exam_type' => 'labHematologi', 'above' => 'green', 'below' => 'red'],
                    'leukosit' => ['exam_type' => 'labHematologi', 'above' => 'red', 'below' => 'red'],
                    'trombosit' => ['exam_type' => 'labHematologi', 'above' => 'red', 'below' => 'red'],

                    // Profil Lemak
                    'cholesterol' => ['exam_type' => 'labProfilLemak', 'above' => 'red', 'below' => 'green'],
                    'trigliserida' => ['exam_type' => 'labProfilLemak', 'above' => 'red', 'below' => 'green'],
                    'hdl_cholesterol' => ['exam_type' => 'labProfilLemak', 'above' => 'green', 'below' => 'red'],
                    'ldl_cholesterol' => ['exam_type' => 'labProfilLemak', 'above' => 'red', 'below' => 'green'],

                    // Glukosa Darah
                    'glukosa_puasa' => ['exam_type' => 'labGlukosaDarah', 'above' => 'red', 'below' => 'green'],
                    'glukosa_2jam_pp' => ['exam_type' => 'labGlukosaDarah', 'above' => 'red', 'below' => 'green'],
                    'hba1c' => ['exam_type' => 'labGlukosaDarah', 'above' => 'red', 'below' => 'green'],

                    // Fungsi Hati
                    'sgot' => ['exam_type' => 'labFungsiLiver', 'above' => 'red', 'below' => 'green'],
                    'sgpt' => ['exam_type' => 'labFungsiLiver', 'above' => 'red', 'below' => 'green'],

                    // Fungsi Ginjal
                    'ureum' => ['exam_type' => 'labFungsiGinjal', 'above' => 'red', 'below' => 'green'],
                    'creatinin' => ['exam_type' => 'labFungsiGinjal', 'above' => 'red', 'below' => 'green'],
                    'asam_urat' => ['exam_type' => 'labFungsiGinjal', 'above' => 'red', 'below' => 'green'],

                    // Pemeriksaan Vital
                    'berat_badan' => ['exam_type' => 'pemeriksaanVital', 'above' => 'red', 'below' => 'red'],
                    'tinggi_badan' => ['exam_type' => 'pemeriksaanVital', 'above' => 'green', 'below' => 'green'],
                    'bmi' => ['exam_type' => 'pemeriksaanVital', 'above' => 'red', 'below' => 'red'],
                ]
            ],
            [
                'name' => 'Diabetes',
                'description' => 'Configuration for patients with diabetes - stricter glucose monitoring',
                'is_default' => false,
                'config' => [
                    // Hematologi - sama seperti healthy
                    'hemoglobin' => ['exam_type' => 'labHematologi', 'above' => 'green', 'below' => 'red'],
                    'erytrosit' => ['exam_type' => 'labHematologi', 'above' => 'green', 'below' => 'red'],

                    // Profil Lemak - lebih strict
                    'cholesterol' => ['exam_type' => 'labProfilLemak', 'above' => 'red', 'below' => 'green'],
                    'trigliserida' => ['exam_type' => 'labProfilLemak', 'above' => 'red', 'below' => 'green'],
                    'hdl_cholesterol' => ['exam_type' => 'labProfilLemak', 'above' => 'green', 'below' => 'red'],
                    'ldl_cholesterol' => ['exam_type' => 'labProfilLemak', 'above' => 'red', 'below' => 'green'],

                    // Glukosa Darah - SANGAT STRICT
                    'glukosa_puasa' => ['exam_type' => 'labGlukosaDarah', 'above' => 'red', 'below' => 'red'],
                    'glukosa_2jam_pp' => ['exam_type' => 'labGlukosaDarah', 'above' => 'red', 'below' => 'red'],
                    'hba1c' => ['exam_type' => 'labGlukosaDarah', 'above' => 'red', 'below' => 'green'],

                    // Fungsi Hati
                    'sgot' => ['exam_type' => 'labFungsiLiver', 'above' => 'red', 'below' => 'green'],
                    'sgpt' => ['exam_type' => 'labFungsiLiver', 'above' => 'red', 'below' => 'green'],

                    // Fungsi Ginjal - penting untuk diabetes
                    'ureum' => ['exam_type' => 'labFungsiGinjal', 'above' => 'red', 'below' => 'green'],
                    'creatinin' => ['exam_type' => 'labFungsiGinjal', 'above' => 'red', 'below' => 'green'],
                    'asam_urat' => ['exam_type' => 'labFungsiGinjal', 'above' => 'red', 'below' => 'green'],

                    // BMI penting
                    'berat_badan' => ['exam_type' => 'pemeriksaanVital', 'above' => 'red', 'below' => 'green'],
                    'bmi' => ['exam_type' => 'pemeriksaanVital', 'above' => 'red', 'below' => 'red'],
                ]
            ],
            [
                'name' => 'Hypertension',
                'description' => 'Configuration for patients with hypertension - focus on cardiovascular health',
                'is_default' => false,
                'config' => [
                    // Hematologi
                    'hemoglobin' => ['exam_type' => 'labHematologi', 'above' => 'green', 'below' => 'red'],
                    'erytrosit' => ['exam_type' => 'labHematologi', 'above' => 'green', 'below' => 'red'],
                    'hematokrit' => ['exam_type' => 'labHematologi', 'above' => 'red', 'below' => 'red'],

                    // Profil Lemak - SANGAT PENTING
                    'cholesterol' => ['exam_type' => 'labProfilLemak', 'above' => 'red', 'below' => 'green'],
                    'trigliserida' => ['exam_type' => 'labProfilLemak', 'above' => 'red', 'below' => 'green'],
                    'hdl_cholesterol' => ['exam_type' => 'labProfilLemak', 'above' => 'green', 'below' => 'red'],
                    'ldl_cholesterol' => ['exam_type' => 'labProfilLemak', 'above' => 'red', 'below' => 'green'],

                    // Glukosa Darah
                    'glukosa_puasa' => ['exam_type' => 'labGlukosaDarah', 'above' => 'red', 'below' => 'green'],
                    'glukosa_2jam_pp' => ['exam_type' => 'labGlukosaDarah', 'above' => 'red', 'below' => 'green'],
                    'hba1c' => ['exam_type' => 'labGlukosaDarah', 'above' => 'red', 'below' => 'green'],

                    // Fungsi Hati
                    'sgot' => ['exam_type' => 'labFungsiLiver', 'above' => 'red', 'below' => 'green'],
                    'sgpt' => ['exam_type' => 'labFungsiLiver', 'above' => 'red', 'below' => 'green'],

                    // Fungsi Ginjal - penting untuk hipertensi
                    'ureum' => ['exam_type' => 'labFungsiGinjal', 'above' => 'red', 'below' => 'green'],
                    'creatinin' => ['exam_type' => 'labFungsiGinjal', 'above' => 'red', 'below' => 'green'],
                    'asam_urat' => ['exam_type' => 'labFungsiGinjal', 'above' => 'red', 'below' => 'green'],

                    // Weight management
                    'berat_badan' => ['exam_type' => 'pemeriksaanVital', 'above' => 'red', 'below' => 'green'],
                    'bmi' => ['exam_type' => 'pemeriksaanVital', 'above' => 'red', 'below' => 'red'],
                ]
            ],
        ];

        foreach ($templates as $template) {
            DB::table('trend_templates')->insert([
                'name' => $template['name'],
                'description' => $template['description'],
                'is_default' => $template['is_default'],
                'config' => json_encode($template['config']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
