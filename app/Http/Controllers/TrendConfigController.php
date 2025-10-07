<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\PatientTrendConfig;
use App\Models\TrendTemplate;

class TrendConfigController extends Controller
{
    /**
     * Get trend configuration for a patient
     */
    public function getConfig($patientId)
    {
        $configs = PatientTrendConfig::where('patient_id', $patientId)->get();
        $templates = TrendTemplate::all();

        return response()->json([
            'configs' => $configs,
            'templates' => $templates,
        ]);
    }

    /**
     * Save trend configuration for a patient
     */
    public function saveConfig(Request $request, $patientId)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*.parameter_name' => 'required|string',
            'configs.*.exam_type' => 'required|string',
            'configs.*.trend_above_normal' => 'required|in:red,green',
            'configs.*.trend_below_normal' => 'required|in:red,green',
            'configs.*.notes' => 'nullable|string',
        ]);

        // Delete existing configs
        PatientTrendConfig::where('patient_id', $patientId)->delete();

        // Insert new configs
        foreach ($validated['configs'] as $config) {
            PatientTrendConfig::create([
                'patient_id' => $patientId,
                'parameter_name' => $config['parameter_name'],
                'exam_type' => $config['exam_type'],
                'trend_above_normal' => $config['trend_above_normal'],
                'trend_below_normal' => $config['trend_below_normal'],
                'notes' => $config['notes'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Trend configuration saved successfully',
        ]);
    }

    /**
     * Apply template to patient
     */
    public function applyTemplate(Request $request, $patientId)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:trend_templates,id',
        ]);

        $template = TrendTemplate::findOrFail($validated['template_id']);
        $templateConfig = $template->config;

        // Delete existing configs
        PatientTrendConfig::where('patient_id', $patientId)->delete();

        // Apply template config
        foreach ($templateConfig as $paramName => $paramConfig) {
            PatientTrendConfig::create([
                'patient_id' => $patientId,
                'parameter_name' => $paramName,
                'exam_type' => $paramConfig['exam_type'],
                'trend_above_normal' => $paramConfig['above'],
                'trend_below_normal' => $paramConfig['below'],
                'notes' => "Applied from template: {$template->name}",
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Template '{$template->name}' applied successfully",
        ]);
    }

    /**
     * Copy config from another patient
     */
    public function copyFromPatient(Request $request, $patientId)
    {
        $validated = $request->validate([
            'source_patient_id' => 'required|exists:patients,id',
        ]);

        $sourceConfigs = PatientTrendConfig::where('patient_id', $validated['source_patient_id'])->get();

        if ($sourceConfigs->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Source patient has no trend configuration',
            ], 400);
        }

        // Delete existing configs
        PatientTrendConfig::where('patient_id', $patientId)->delete();

        // Copy configs
        foreach ($sourceConfigs as $config) {
            PatientTrendConfig::create([
                'patient_id' => $patientId,
                'parameter_name' => $config->parameter_name,
                'exam_type' => $config->exam_type,
                'trend_above_normal' => $config->trend_above_normal,
                'trend_below_normal' => $config->trend_below_normal,
                'notes' => $config->notes,
            ]);
        }

        $sourcePatient = Patient::find($validated['source_patient_id']);

        return response()->json([
            'success' => true,
            'message' => "Configuration copied from {$sourcePatient->name}",
        ]);
    }

    /**
     * Get all patients for copy function
     */
    public function getPatients()
    {
        $patients = Patient::select('id', 'name', 'share_id')
            ->whereHas('trendConfigs')
            ->get();

        return response()->json($patients);
    }

    /**
     * Show the trend configuration page
     */
    public function index($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $configs = PatientTrendConfig::where('patient_id', $patientId)->get();
        $patientsWithConfig = Patient::select('id', 'name', 'share_id')
            ->whereHas('trendConfigs')
            ->where('id', '!=', $patientId)
            ->get();

        return view('admin.trend-config.index', compact('patient', 'configs', 'patientsWithConfig'));
    }
}
