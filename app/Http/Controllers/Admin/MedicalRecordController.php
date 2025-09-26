<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Patient;
use App\Models\NormalValue;

class MedicalRecordController extends Controller
{
    public function edit(Request $request, $patientId, $orderId = null)
    {
        $patient = Patient::findOrFail($patientId);

        // Support for MCU selection via query parameter
        $selectedOrderId = $request->get('order_id', $orderId);

        // If no specific order selected, use latest MCU
        if (!$selectedOrderId && $patient->latest_mcu) {
            $selectedOrderId = $patient->latest_mcu->id;
        }

        $order = Order::with([
            'labHematologi',
            'labUrine',
            'labFungsiLiver',
            'labProfilLemak',
            'labFungsiGinjal',
            'labGlukosaDarah',
            'labPenandaTumor',
            'radiologi',
            'pemeriksaanVital',
            'tandaVital',
            'pemeriksaanMata',
            'pemeriksaanGigi',
            'tesFisik',
            'statusGizi',
            'riwayatKebiasaanHidup'
        ])->findOrFail($selectedOrderId);

        return view('admin.medical-records.edit', compact('patient', 'order'));
    }

    public function update(Request $request, $patientId, $orderId)
    {
        $patient = Patient::findOrFail($patientId);
        $order = Order::with([
            'labHematologi',
            'labUrine',
            'labFungsiLiver',
            'labProfilLemak',
            'labFungsiGinjal',
            'labGlukosaDarah',
            'labPenandaTumor',
            'radiologi',
            'pemeriksaanVital',
            'tandaVital',
            'pemeriksaanMata',
            'pemeriksaanGigi',
            'tesFisik',
            'statusGizi',
            'riwayatKebiasaanHidup'
        ])->findOrFail($orderId);

        // Helper function to add units back to values
        $addUnits = function($data, $fieldUnits) {
            foreach ($data as $field => $value) {
                if ($value !== null && $value !== '' && isset($fieldUnits[$field])) {
                    $unit = $fieldUnits[$field];
                    if ($unit !== '-' && !empty($unit)) {
                        // Only add unit if it's not already present
                        if (!str_contains($value, $unit)) {
                            $data[$field] = $value . ' ' . $unit;
                        }
                    }
                }
            }
            return $data;
        };

        // Update lab results and medical data with proper units
        if ($request->has('hematologi')) {
            $hematologiUnits = [
                'hemoglobin' => 'g/dL',
                'erytrosit' => 'juta/μL',
                'hematokrit' => '%',
                'mcv' => 'fL',
                'mch' => 'pg',
                'mchc' => 'g/dL',
                'rdw' => '%',
                'leukosit' => '/μL',
                'eosinofil' => '%',
                'basofil' => '%',
                'neutrofil_batang' => '%',
                'neutrofil_segmen' => '%',
                'limfosit' => '%',
                'monosit' => '%',
                'trombosit' => '/μL',
                'laju_endap_darah' => 'mm/jam'
            ];

            $hematologiData = $addUnits($request->hematologi, $hematologiUnits);
            $order->labHematologi()->updateOrCreate(
                ['order_id' => $order->id],
                $hematologiData
            );
        }

        if ($request->has('urine')) {
            $order->labUrine()->updateOrCreate(
                ['order_id' => $order->id],
                $request->urine
            );
        }

        if ($request->has('fungsi_liver')) {
            $fungsiLiverUnits = [
                'sgot' => 'U/L',
                'sgpt' => 'U/L'
            ];

            $fungsiLiverData = $addUnits($request->fungsi_liver, $fungsiLiverUnits);
            $order->labFungsiLiver()->updateOrCreate(
                ['order_id' => $order->id],
                $fungsiLiverData
            );
        }

        if ($request->has('profil_lemak')) {
            $profilLemakUnits = [
                'cholesterol' => 'mg/dL',
                'trigliserida' => 'mg/dL',
                'hdl_cholesterol' => 'mg/dL',
                'ldl_cholesterol' => 'mg/dL'
            ];

            $profilLemakData = $addUnits($request->profil_lemak, $profilLemakUnits);
            $order->labProfilLemak()->updateOrCreate(
                ['order_id' => $order->id],
                $profilLemakData
            );
        }

        if ($request->has('fungsi_ginjal')) {
            $fungsiGinjalUnits = [
                'ureum' => 'mg/dL',
                'creatinin' => 'mg/dL',
                'asam_urat' => 'mg/dL'
            ];

            $fungsiGinjalData = $addUnits($request->fungsi_ginjal, $fungsiGinjalUnits);
            $order->labFungsiGinjal()->updateOrCreate(
                ['order_id' => $order->id],
                $fungsiGinjalData
            );
        }

        if ($request->has('glukosa_darah')) {
            $glukosaDarahUnits = [
                'glukosa_puasa' => 'mg/dL',
                'glukosa_2jam_pp' => 'mg/dL',
                'hba1c' => '%'
            ];

            $glukosaDarahData = $addUnits($request->glukosa_darah, $glukosaDarahUnits);
            $order->labGlukosaDarah()->updateOrCreate(
                ['order_id' => $order->id],
                $glukosaDarahData
            );
        }

        if ($request->has('penanda_tumor')) {
            $order->labPenandaTumor()->updateOrCreate(
                ['order_id' => $order->id],
                $request->penanda_tumor
            );
        }

        if ($request->has('tanda_vital')) {
            $tandaVitalUnits = [
                'tekanan_darah' => 'mmHg',
                'nadi' => 'x/menit',
                'pernapasan' => 'x/menit',
                'suhu_tubuh' => '°C'
            ];

            $tandaVitalData = $addUnits($request->tanda_vital, $tandaVitalUnits);
            $order->tandaVital()->updateOrCreate(
                ['order_id' => $order->id],
                $tandaVitalData
            );
        }

        if ($request->has('pemeriksaan_vital')) {
            $pemeriksaanVitalUnits = [
                'berat_badan' => 'kg',
                'tinggi_badan' => 'cm',
                'lingkar_perut' => 'cm'
            ];

            $pemeriksaanVitalData = $addUnits($request->pemeriksaan_vital, $pemeriksaanVitalUnits);
            $order->pemeriksaanVital()->updateOrCreate(
                ['order_id' => $order->id],
                $pemeriksaanVitalData
            );
        }

        return redirect()
            ->route('patients.show', $patient->id)
            ->with('success', 'Medical record updated successfully');
    }

    public function normalValues()
    {
        // Try to get from database first, if empty seed with default values
        $normalValues = NormalValue::getValuesByCategory();

        if ($normalValues->isEmpty()) {
            $this->seedDefaultNormalValues();
            $normalValues = NormalValue::getValuesByCategory();
        }

        return view('admin.normal-values.index', compact('normalValues'));
    }

    private function seedDefaultNormalValues()
    {
        $defaultValues = [
            'hematologi' => [
                'hemoglobin' => '12-16 g/dL',
                'erytrosit' => '4.2-5.4 juta/μL',
                'hematokrit' => '37-48%',
                'mcv' => '82-98 fL',
                'mch' => '27-31 pg',
                'mchc' => '32-36 g/dL',
                'rdw' => '11.5-14.5%',
                'leukosit' => '4.000-11.000/μL',
                'eosinofil' => '1-3%',
                'basofil' => '0-1%',
                'neutrofil_batang' => '2-6%',
                'neutrofil_segmen' => '50-70%',
                'limfosit' => '20-40%',
                'monosit' => '2-8%',
                'trombosit' => '150.000-450.000/μL',
                'laju_endap_darah' => '0-15 mm/jam'
            ],
            'fungsi_liver' => [
                'sgot' => '10-40 U/L',
                'sgpt' => '7-56 U/L'
            ],
            'fungsi_ginjal' => [
                'ureum' => '10-50 mg/dL',
                'creatinin' => '0.6-1.2 mg/dL',
                'asam_urat' => '3.5-7.2 mg/dL'
            ],
            'glukosa_darah' => [
                'glukosa_puasa' => '70-100 mg/dL',
                'glukosa_2jam_pp' => '< 140 mg/dL',
                'hba1c' => '< 5.7%'
            ],
            'profil_lemak' => [
                'cholesterol' => '< 200 mg/dL',
                'trigliserida' => '< 150 mg/dL',
                'hdl_cholesterol' => '> 40 mg/dL (P), > 50 mg/dL (W)',
                'ldl_cholesterol' => '< 100 mg/dL'
            ],
            'tanda_vital' => [
                'tekanan_darah' => '120/80 mmHg',
                'nadi' => '60-100 x/menit',
                'pernapasan' => '12-20 x/menit',
                'suhu_tubuh' => '36.0-37.5°C'
            ],
            'pemeriksaan_vital' => [
                'berat_badan' => '40-100 kg',
                'tinggi_badan' => '150-200 cm',
                'lingkar_perut' => '< 90 cm (P), < 80 cm (W)',
                'bmi' => '18.5-24.9'
            ]
        ];

        foreach ($defaultValues as $category => $parameters) {
            foreach ($parameters as $parameter => $value) {
                NormalValue::create([
                    'category' => $category,
                    'parameter' => $parameter,
                    'value' => $value
                ]);
            }
        }
    }

    public function updateNormalValues(Request $request)
    {
        try {
            // Get all form data except _token and _method
            $formData = $request->except(['_token', '_method']);

            // Process each category
            foreach ($formData as $category => $parameters) {
                foreach ($parameters as $parameter => $value) {
                    // Skip empty values
                    if (empty(trim($value))) {
                        continue;
                    }

                    // Update or create normal value
                    NormalValue::updateOrCreate(
                        [
                            'category' => $category,
                            'parameter' => $parameter
                        ],
                        [
                            'value' => trim($value)
                        ]
                    );
                }
            }

            return redirect()
                ->route('admin.normal-values')
                ->with('success', 'Normal values updated successfully');

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.normal-values')
                ->with('error', 'Failed to update normal values: ' . $e->getMessage());
        }
    }
}