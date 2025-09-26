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
        $selectedMcuRecords = $request->get('mcu_records', []);

        $comparisonData = null;
        $availableYears = [];

        if ($selectedPatientId) {
            $patient = Patient::findOrFail($selectedPatientId);
            $orders = $patient->orders()->orderBy('tgl_order')->get();

            // Create available MCU records for selection (not grouped by year)
            $availableMcuRecords = $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'date' => $order->tgl_order,
                    'year' => $order->tgl_order->year,
                    'display_text' => $order->mcu_display_text,
                    'type' => $order->mcu_type,
                    'lab_no' => $order->no_lab,
                    'tooltip_info' => [
                        'date' => $order->tgl_order->format('M d, Y'),
                        'type' => ucfirst(str_replace('-', ' ', $order->mcu_type)),
                        'lab' => $order->no_lab,
                        'branch' => $order->cabang ?? 'N/A'
                    ]
                ];
            });

            // Debug log
            \Log::info('Available MCU Records for Patient ' . $selectedPatientId . ':', $availableMcuRecords->toArray());

            // For backward compatibility, still provide years
            $availableYears = $orders->groupBy(function($order) {
                return Carbon::parse($order->tgl_order)->year;
            })->keys()->toArray();

            // Check if we have MCU records selection or years selection
            if (count($selectedMcuRecords) >= 2) {
                $comparisonData = $this->healthAnalysisService->compareHealthData($patient, $selectedMcuRecords, true);
            } elseif (count($selectedYears) >= 2) {
                $comparisonData = $this->healthAnalysisService->compareHealthData($patient, $selectedYears, false);
            }
        } else {
            $availableMcuRecords = collect();
        }

        return view('health-comparison.index', compact(
            'patients',
            'selectedPatientId',
            'selectedYears',
            'selectedMcuRecords',
            'availableYears',
            'availableMcuRecords',
            'comparisonData'
        ));
    }

    public function compare(Request $request)
    {
        // Check if we're comparing MCU records or years
        if ($request->has('mcu_records')) {
            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'mcu_records' => 'required|array|min:2',
                'mcu_records.*' => 'required|integer'
            ]);

            $patient = Patient::findOrFail($request->patient_id);
            $mcuRecords = $request->mcu_records;

            $comparisonData = $this->healthAnalysisService->compareHealthData($patient, $mcuRecords, true);
        } else {
            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'years' => 'required|array|min:2',
                'years.*' => 'required|integer'
            ]);

            $patient = Patient::findOrFail($request->patient_id);
            $years = $request->years;

            $comparisonData = $this->healthAnalysisService->compareHealthData($patient, $years, false);
        }

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