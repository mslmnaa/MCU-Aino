<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HealthAnalysisService
{
    protected $labParameters = [
        'lab_hematologi' => [
            'hemoglobin', 'erytrosit', 'hematokrit', 'mcv', 'mch', 'mchc',
            'rdw', 'leukosit', 'eosinofil', 'basofil', 'neutrofil_batang',
            'neutrofil_segmen', 'limfosit', 'monosit', 'trombosit', 'laju_endap_darah'
        ],
        'lab_urine' => [
            'warna', 'kejernihan', 'berat_jenis', 'ph', 'protein', 'glukosa',
            'keton', 'bilirubin', 'darah', 'urobilinogen', 'nitrit', 'leukosit_esterase'
        ],
        'lab_fungsi_liver' => [
            'sgot_ast', 'sgpt_alt', 'gamma_gt', 'protein_total', 'albumin',
            'globulin', 'bilirubin_total', 'bilirubin_direct', 'bilirubin_indirect'
        ],
        'lab_profil_lemak' => [
            'kolesterol_total', 'kolesterol_hdl', 'kolesterol_ldl', 'trigliserida'
        ],
        'lab_fungsi_ginjal' => [
            'ureum', 'creatinine', 'asam_urat'
        ],
        'lab_glukosa_darah' => [
            'glukosa_puasa', 'glukosa_2_jam_pp', 'hba1c'
        ],
        'lab_penanda_tumor' => [
            'afp', 'cea', 'psa'
        ]
    ];

    protected $vitalParameters = [
        'tanda_vital' => [
            'tinggi_badan', 'berat_badan', 'bmi', 'tekanan_darah_sistole',
            'tekanan_darah_diastole', 'nadi', 'suhu', 'respirasi'
        ],
        'pemeriksaan_vital' => [
            'kesadaran', 'konjungtiva', 'sklera'
        ]
    ];

    public function compareHealthData(Patient $patient, array $identifiers, bool $useOrderIds = false): array
    {
        $comparisonData = [
            'patient' => $patient,
            'identifiers' => $identifiers,
            'use_order_ids' => $useOrderIds,
            'lab_comparisons' => [],
            'vital_comparisons' => [],
            'trends' => [],
            'health_scores' => [],
            'alerts' => []
        ];

        if ($useOrderIds) {
            // New method: compare by specific order IDs
            foreach ($identifiers as $orderId) {
                $orderData = $this->getOrderDataById($patient, $orderId);

                if ($orderData) {
                    $key = $orderData->mcu_display_text; // Use display text as key
                    $comparisonData['lab_comparisons'][$key] = $this->extractLabData($orderData);
                    $comparisonData['vital_comparisons'][$key] = $this->extractVitalData($orderData);
                    $comparisonData['health_scores'][$key] = $this->calculateHealthScore($orderData);
                }
            }
        } else {
            // Legacy method: compare by years
            foreach ($identifiers as $year) {
                $orderData = $this->getOrderDataByYear($patient, $year);

                if ($orderData) {
                    $comparisonData['lab_comparisons'][$year] = $this->extractLabData($orderData);
                    $comparisonData['vital_comparisons'][$year] = $this->extractVitalData($orderData);
                    $comparisonData['health_scores'][$year] = $this->calculateHealthScore($orderData);
                }
            }
        }

        $comparisonData['trends'] = $this->calculateTrends($comparisonData['lab_comparisons']);
        $comparisonData['alerts'] = $this->generateAlerts($comparisonData);

        return $comparisonData;
    }

    public function getTrendData(Patient $patient, string $parameter): array
    {
        $orders = $patient->orders()->with([
            'labHematologi', 'labUrine', 'labFungsiLiver', 'labProfilLemak',
            'labFungsiGinjal', 'labGlukosaDarah', 'labPenandaTumor',
            'tandaVital', 'pemeriksaanVital'
        ])->orderBy('tgl_order')->get();

        $trendData = [];

        foreach ($orders as $order) {
            $year = Carbon::parse($order->tgl_order)->year;
            $value = $this->getParameterValue($order, $parameter);

            if ($value !== null) {
                $trendData[] = [
                    'year' => $year,
                    'date' => $order->tgl_order,
                    'value' => $value
                ];
            }
        }

        return $trendData;
    }

    protected function getOrderDataByYear(Patient $patient, int $year): ?Order
    {
        return $patient->orders()
            ->with([
                'labHematologi', 'labUrine', 'labFungsiLiver', 'labProfilLemak',
                'labFungsiGinjal', 'labGlukosaDarah', 'labPenandaTumor',
                'tandaVital', 'pemeriksaanVital', 'statusGizi', 'pemeriksaanMata',
                'pemeriksaanGigi', 'tesFisik', 'radiologi'
            ])
            ->whereYear('tgl_order', $year)
            ->latest('tgl_order')
            ->first();
    }

    protected function getOrderDataById(Patient $patient, int $orderId): ?Order
    {
        return $patient->orders()
            ->with([
                'labHematologi', 'labUrine', 'labFungsiLiver', 'labProfilLemak',
                'labFungsiGinjal', 'labGlukosaDarah', 'labPenandaTumor',
                'tandaVital', 'pemeriksaanVital', 'statusGizi', 'pemeriksaanMata',
                'pemeriksaanGigi', 'tesFisik', 'radiologi'
            ])
            ->where('id', $orderId)
            ->first();
    }

    protected function extractLabData(Order $order): array
    {
        $labData = [];

        foreach ($this->labParameters as $relation => $parameters) {
            if ($order->$relation) {
                $labData[$relation] = [];
                foreach ($parameters as $param) {
                    $labData[$relation][$param] = $order->$relation->$param;
                }
            }
        }

        return $labData;
    }

    protected function extractVitalData(Order $order): array
    {
        $vitalData = [];

        foreach ($this->vitalParameters as $relation => $parameters) {
            if ($order->$relation) {
                $vitalData[$relation] = [];
                foreach ($parameters as $param) {
                    $vitalData[$relation][$param] = $order->$relation->$param;
                }
            }
        }

        return $vitalData;
    }

    protected function calculateHealthScore(Order $order): float
    {
        $score = 100;
        $deductions = 0;

        if ($order->labHematologi) {
            if ($order->labHematologi->hemoglobin < 12) $deductions += 5;
            if ($order->labHematologi->leukosit > 11000) $deductions += 3;
        }

        if ($order->labProfilLemak) {
            if ($order->labProfilLemak->kolesterol_total > 200) $deductions += 8;
            if ($order->labProfilLemak->kolesterol_ldl > 130) $deductions += 6;
        }

        if ($order->labGlukosaDarah) {
            if ($order->labGlukosaDarah->glukosa_puasa > 100) $deductions += 10;
        }

        if ($order->tandaVital) {
            if ($order->tandaVital->bmi > 25) $deductions += 5;
            if ($order->tandaVital->tekanan_darah_sistole > 140) $deductions += 8;
        }

        return max(0, $score - $deductions);
    }

    protected function calculateTrends(array $labComparisons): array
    {
        $trends = [];

        if (count($labComparisons) < 2) {
            return $trends;
        }

        $years = array_keys($labComparisons);
        sort($years);

        for ($i = 1; $i < count($years); $i++) {
            $prevYear = $years[$i-1];
            $currentYear = $years[$i];

            foreach ($this->labParameters as $labType => $parameters) {
                if (isset($labComparisons[$prevYear][$labType]) && isset($labComparisons[$currentYear][$labType])) {
                    foreach ($parameters as $param) {
                        $prevValue = $labComparisons[$prevYear][$labType][$param];
                        $currentValue = $labComparisons[$currentYear][$labType][$param];

                        if ($prevValue !== null && $currentValue !== null && is_numeric($prevValue) && is_numeric($currentValue)) {
                            $change = $currentValue - $prevValue;
                            $percentChange = $prevValue != 0 ? ($change / $prevValue) * 100 : 0;

                            $trends["{$labType}.{$param}"] = [
                                'change' => $change,
                                'percent_change' => $percentChange,
                                'direction' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'stable'),
                                'years' => "{$prevYear}-{$currentYear}"
                            ];
                        }
                    }
                }
            }
        }

        return $trends;
    }

    protected function generateAlerts(array $comparisonData): array
    {
        $alerts = [];

        foreach ($comparisonData['trends'] as $parameter => $trend) {
            if (abs($trend['percent_change']) > 20) {
                $alerts[] = [
                    'type' => 'significant_change',
                    'parameter' => $parameter,
                    'message' => "Perubahan signifikan pada {$parameter}: " . number_format($trend['percent_change'], 1) . "%",
                    'severity' => abs($trend['percent_change']) > 50 ? 'high' : 'medium'
                ];
            }
        }

        $latestYear = max(array_keys($comparisonData['health_scores']));
        $latestScore = $comparisonData['health_scores'][$latestYear];

        if ($latestScore < 70) {
            $alerts[] = [
                'type' => 'low_health_score',
                'message' => "Skor kesehatan rendah: {$latestScore}/100",
                'severity' => $latestScore < 50 ? 'high' : 'medium'
            ];
        }

        return $alerts;
    }

    protected function getParameterValue(Order $order, string $parameter): ?float
    {
        $parts = explode('.', $parameter);
        if (count($parts) !== 2) {
            return null;
        }

        [$relation, $field] = $parts;

        if ($order->$relation && isset($order->$relation->$field)) {
            $value = $order->$relation->$field;
            return is_numeric($value) ? (float) $value : null;
        }

        return null;
    }
}