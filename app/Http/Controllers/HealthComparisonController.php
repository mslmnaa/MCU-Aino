<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Order;
use App\Services\HealthAnalysisService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class HealthComparisonController extends Controller
{
    protected $healthAnalysisService;

    public function __construct(HealthAnalysisService $healthAnalysisService)
    {
        $this->healthAnalysisService = $healthAnalysisService;
    }

    public function index(Request $request)
    {
        $patients = Patient::with('orders')->get();

        $selectedPatientId = $request->get('patient_id');
        $selectedYears = $request->get('years', []);

        $comparisonData = null;
        $availableYears = [];

        if ($selectedPatientId) {
            $patient = Patient::findOrFail($selectedPatientId);
            $orders = $patient->orders()->orderBy('tgl_order')->get();

            $availableYears = $orders->groupBy(function($order) {
                return Carbon::parse($order->tgl_order)->year;
            })->keys()->toArray();

            if (count($selectedYears) >= 2) {
                $comparisonData = $this->healthAnalysisService->compareHealthData($patient, $selectedYears);
            }
        }

        return view('health-comparison.index', compact(
            'patients',
            'selectedPatientId',
            'selectedYears',
            'availableYears',
            'comparisonData'
        ));
    }

    public function compare(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'years' => 'required|array|min:2',
            'years.*' => 'required|integer'
        ]);

        $patient = Patient::findOrFail($request->patient_id);
        $years = $request->years;

        $comparisonData = $this->healthAnalysisService->compareHealthData($patient, $years);

        return response()->json($comparisonData);
    }

    public function trends(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'parameter' => 'required|string'
        ]);

        $patient = Patient::findOrFail($request->patient_id);
        $parameter = $request->parameter;

        $trendData = $this->healthAnalysisService->getTrendData($patient, $parameter);

        return response()->json($trendData);
    }

    public function exportComparison(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'years' => 'required|array|min:2'
        ]);

        $patient = Patient::findOrFail($request->patient_id);
        $years = $request->years;

        $comparisonData = $this->healthAnalysisService->compareHealthData($patient, $years);

        // Generate a proper PDF using DomPDF
        $fileName = 'Health_Comparison_' . str_replace(' ', '_', $patient->name) . '_' . collect($years)->join('-') . '_' . date('Y-m-d') . '.pdf';
        $pdf = Pdf::loadView('health-comparison.export-pdf', [
            'patient' => $patient,
            'years' => $years,
            'comparisonData' => $comparisonData,
        ]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($fileName);
    }
}