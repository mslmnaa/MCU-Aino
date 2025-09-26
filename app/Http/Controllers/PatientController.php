<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('orders')->paginate(10);
        return view('admin.employees.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $patient->load(['orders' => function($query) {
            $query->with([
                'labHematologi',
                'labUrine',
                'labFungsiLiver',
                'labProfilLemak',
                'labFungsiGinjal',
                'labGlukosaDarah',
                'labPenandaTumor',
                'radiologi',
                'pemeriksaanVital',
                'statusGizi',
                'tandaVital',
                'pemeriksaanMata',
                'pemeriksaanGigi',
                'tesFisik'
            ])->orderBy('tgl_order');
        }]);

        // Get available years for comparison
        $availableYears = $patient->orders->groupBy(function($order) {
            return $order->tgl_order->year;
        })->keys()->sort()->values();

        // Prepare comparison data if requested
        $comparisonData = null;
        $healthAnalysisService = app(\App\Services\HealthAnalysisService::class);

        if ($availableYears->count() >= 2) {
            $comparisonData = $healthAnalysisService->compareHealthData($patient, $availableYears->toArray());
        }

        return view('admin.employees.show', compact('patient', 'availableYears', 'comparisonData'));
    }

    public function showHealthCheck($shareId)
    {
        $patient = Patient::where('share_id', $shareId)->firstOrFail();

        $patient->load(['orders' => function($query) {
            $query->with([
                'labHematologi',
                'labUrine',
                'labFungsiLiver',
                'labProfilLemak',
                'labFungsiGinjal',
                'labGlukosaDarah',
                'labPenandaTumor',
                'radiologi',
                'pemeriksaanVital',
                'statusGizi',
                'tandaVital',
                'pemeriksaanMata',
                'pemeriksaanGigi',
                'tesFisik'
            ]);
        }]);

        return view('user.health-check', compact('patient'));
    }

    public function myHealth(Request $request)
    {
        $user = auth()->user();
        $patient = $user->patient;

        if (!$patient) {
            return view('user.health-check-no-data')->with('error', 'No medical records found for your account. Please contact the administrator.');
        }

        $patient->load(['orders' => function($query) {
            $query->with([
                'labHematologi',
                'labUrine',
                'labFungsiLiver',
                'labProfilLemak',
                'labFungsiGinjal',
                'labGlukosaDarah',
                'labPenandaTumor',
                'radiologi',
                'pemeriksaanVital',
                'statusGizi',
                'tandaVital',
                'pemeriksaanMata',
                'pemeriksaanGigi',
                'tesFisik'
            ])->orderBy('tgl_order');
        }]);

        // Check if export is requested
        if ($request->get('export') == '1') {
            // Get selected years from request, or default to all years
            $selectedYears = $request->get('years');
            if ($selectedYears) {
                $selectedYears = collect(explode(',', $selectedYears))->map(function($year) {
                    return (int) trim($year);
                })->sort()->values();
            } else {
                // Default to all available years if none specified
                $selectedYears = $patient->orders->groupBy(function($order) {
                    return $order->tgl_order->year;
                })->keys()->sort()->values();
            }

            // Filter orders based on selected years
            $filteredOrders = $patient->orders->filter(function($order) use ($selectedYears) {
                return $selectedYears->contains($order->tgl_order->year);
            });
            $patient->setRelation('orders', $filteredOrders);

            $fileName = 'Medical_Checkup_' . str_replace(' ', '_', $patient->name) . '_' . $selectedYears->implode('-') . '_' . date('Y-m-d') . '.pdf';

            // Use DomPDF directly for fast PDF generation
            $pdf = Pdf::loadView('admin.employees.export-pdf', compact('patient', 'selectedYears'));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

            return $pdf->download($fileName);
        }

        return view('user.health-check', compact('patient'));
    }

    public function export(Patient $patient, Request $request)
    {
        // Get selected years from request, or default to all years
        $selectedYears = $request->get('years');
        if ($selectedYears) {
            $selectedYears = collect(explode(',', $selectedYears))->map(function($year) {
                return (int) trim($year);
            })->sort()->values();
        } else {
            // Load patient orders to get available years
            $patient->load(['orders' => function($query) {
                $query->orderBy('tgl_order');
            }]);

            $selectedYears = $patient->orders->groupBy(function($order) {
                return $order->tgl_order->year;
            })->keys()->sort()->values();
        }

        // Load patient with all necessary relationships
        $patient->load(['orders' => function($query) use ($selectedYears) {
            $query->with([
                'labHematologi',
                'labUrine',
                'labFungsiLiver',
                'labProfilLemak',
                'labFungsiGinjal',
                'labGlukosaDarah',
                'labPenandaTumor',
                'radiologi',
                'pemeriksaanVital',
                'statusGizi',
                'tandaVital',
                'pemeriksaanMata',
                'pemeriksaanGigi',
                'tesFisik'
            ])->whereYear('tgl_order', $selectedYears->toArray())
              ->orderBy('tgl_order');
        }]);

        $fileName = 'Medical_Checkup_' . str_replace(' ', '_', $patient->name) . '_' . $selectedYears->implode('-') . '_' . date('Y-m-d') . '.pdf';

        // Use DomPDF directly for fast PDF generation
        $pdf = Pdf::loadView('admin.employees.export-pdf', compact('patient', 'selectedYears'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
        ]);

        return $pdf->download($fileName);
    }

}