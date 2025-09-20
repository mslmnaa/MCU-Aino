<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Patient;

class MedicalRecordController extends Controller
{
    public function edit($patientId, $orderId)
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

        // Update lab results and medical data
        if ($request->has('hematologi')) {
            $order->labHematologi()->updateOrCreate(
                ['order_id' => $order->id],
                $request->hematologi
            );
        }

        if ($request->has('urine')) {
            $order->labUrine()->updateOrCreate(
                ['order_id' => $order->id],
                $request->urine
            );
        }

        if ($request->has('fungsi_liver')) {
            $order->labFungsiLiver()->updateOrCreate(
                ['order_id' => $order->id],
                $request->fungsi_liver
            );
        }

        if ($request->has('profil_lemak')) {
            $order->labProfilLemak()->updateOrCreate(
                ['order_id' => $order->id],
                $request->profil_lemak
            );
        }

        if ($request->has('fungsi_ginjal')) {
            $order->labFungsiGinjal()->updateOrCreate(
                ['order_id' => $order->id],
                $request->fungsi_ginjal
            );
        }

        if ($request->has('glukosa_darah')) {
            $order->labGlukosaDarah()->updateOrCreate(
                ['order_id' => $order->id],
                $request->glukosa_darah
            );
        }

        if ($request->has('penanda_tumor')) {
            $order->labPenandaTumor()->updateOrCreate(
                ['order_id' => $order->id],
                $request->penanda_tumor
            );
        }

        if ($request->has('tanda_vital')) {
            $order->tandaVital()->updateOrCreate(
                ['order_id' => $order->id],
                $request->tanda_vital
            );
        }

        if ($request->has('pemeriksaan_vital')) {
            $order->pemeriksaanVital()->updateOrCreate(
                ['order_id' => $order->id],
                $request->pemeriksaan_vital
            );
        }

        return redirect()
            ->route('patients.show', $patient->id)
            ->with('success', 'Medical record updated successfully');
    }

    public function normalValues()
    {
        // Get all normal values from a config file or database
        $normalValues = [
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

        return view('admin.medical-records.normal-values', compact('normalValues'));
    }

    public function updateNormalValues(Request $request)
    {
        // For now, we'll just return success
        // In a real application, you might want to store these in a database table
        return redirect()
            ->route('admin.normal-values')
            ->with('success', 'Normal values updated successfully');
    }
}