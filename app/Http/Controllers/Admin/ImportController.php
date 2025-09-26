<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\McuImport;
use App\Models\Patient;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function redirectToImport()
    {
        return redirect()->route('admin.import')->with('error', 'Silakan upload file terlebih dahulu untuk melakukan preview.');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls|max:10240' // Max 10MB
        ]);

        try {
            $file = $request->file('file');
            $import = new McuImport();

            // Parse the file and get preview data
            $data = Excel::toArray($import, $file);

            if (empty($data[0])) {
                return back()->with('error', 'File kosong atau format tidak valid.');
            }

            $allRows = $data[0];

            // Check if file has data
            if (count($allRows) < 2) {
                return back()->with('error', 'File harus memiliki header dan minimal 1 baris data.');
            }

            // First row is headers
            $headers = array_shift($allRows);

            // Clean headers - remove empty values and trim, maintain index
            $cleanHeaders = [];
            foreach ($headers as $index => $header) {
                if (!is_null($header) && trim($header) !== '') {
                    $cleanHeaders[$index] = trim($header);
                }
            }
            $headers = $cleanHeaders;

            // Take only first 10 rows for preview (excluding header)
            $previewRows = array_slice($allRows, 0, 10);

            // Validate and map columns
            $columnMapping = $this->detectColumnMapping($headers);
            $validationResults = $this->validatePreviewData($previewRows, $columnMapping);

            // Add matching information for existing patients
            $matchingResults = $this->checkForExistingPatients($previewRows, $columnMapping);

            // Store file temporarily for later processing
            $tempFileName = time() . '_' . $file->getClientOriginalName();
            $storedPath = $file->storeAs('temp-imports', $tempFileName);

            \Log::info('File stored for import', [
                'temp_filename' => $tempFileName,
                'stored_path' => $storedPath,
                'full_path' => storage_path('app/temp-imports/' . $tempFileName),
                'file_exists' => file_exists(storage_path('app/temp-imports/' . $tempFileName))
            ]);

            // Store data in session as backup
            session([
                'import_headers' => $headers,
                'import_preview_rows' => $previewRows,
                'import_all_rows' => $allRows, // Store all rows for import fallback
                'import_column_mapping' => $columnMapping,
                'import_validation_results' => $validationResults,
                'import_temp_file' => $tempFileName
            ]);

            return view('admin.import.preview', compact(
                'headers',
                'previewRows',
                'columnMapping',
                'validationResults',
                'matchingResults',
                'tempFileName'
            ));

        } catch (\Exception $e) {
            return back()->with('error', 'Error reading file: ' . $e->getMessage());
        }
    }

    public function process(Request $request)
    {
        // Debug: Log incoming request
        \Log::info('Import process started', [
            'temp_file' => $request->temp_file,
            'column_mapping' => $request->column_mapping,
            'all_input' => $request->all()
        ]);

        $request->validate([
            'temp_file' => 'required|string',
            'column_mapping' => 'array'
        ]);

        try {
            $tempFilePath = storage_path('app/temp-imports/' . $request->temp_file);

            \Log::info('Checking temp file', ['path' => $tempFilePath, 'exists' => file_exists($tempFilePath)]);

            // Check if temp file exists, if not try session backup
            if (!file_exists($tempFilePath)) {
                \Log::warning('Temp file not found, checking session backup', ['path' => $tempFilePath]);

                // Try to get data from session as fallback
                $sessionHeaders = session('import_headers');
                $sessionAllRows = session('import_all_rows'); // Use all rows, not just preview

                if (!$sessionHeaders || !$sessionAllRows) {
                    \Log::error('No session backup found');
                    return back()->with('error', 'File temporary dan session backup tidak ditemukan. Silakan upload ulang file.');
                }

                // Recreate temp file from session data
                $csvContent = implode(',', array_map(function($cell) {
                    return '"' . str_replace('"', '""', $cell) . '"';
                }, $sessionHeaders)) . "\n";

                foreach ($sessionAllRows as $row) {
                    $csvContent .= implode(',', array_map(function($cell) {
                        return '"' . str_replace('"', '""', $cell) . '"';
                    }, $row)) . "\n";
                }

                // Create temp file
                $tempDir = storage_path('app/temp-imports/');
                if (!is_dir($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }

                file_put_contents($tempFilePath, $csvContent);
                \Log::info('Recreated temp file from session', ['path' => $tempFilePath]);
            }

            // Filter out empty column mappings (but keep "0" as valid index)
            $columnMapping = array_filter($request->column_mapping ?? [], function($value) {
                return $value !== null && $value !== '';
            });

            \Log::info('Filtered column mapping', ['mapping' => $columnMapping]);

            if (empty($columnMapping)) {
                return back()->with('error', 'Pilih minimal satu kolom untuk mapping.');
            }

            $import = new McuImport($columnMapping);

            DB::beginTransaction();

            Excel::import($import, $tempFilePath);

            // Get detailed import statistics
            $detailedStats = $import->getDetailedStats();

            // Save credentials to CSV file if any new patients were created
            $credentialsFile = $import->saveCredentialsToFile();

            DB::commit();

            // Clean up temp file
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }

            \Log::info('Import completed', ['detailed_stats' => $detailedStats, 'credentials_file' => $credentialsFile]);

            // Build detailed success message
            $message = "Import berhasil! ";
            $message .= "{$detailedStats['total_processed']} data diproses, ";
            $message .= "{$detailedStats['success']} berhasil, ";
            $message .= "{$detailedStats['errors']} error.<br><br>";

            // Add breakdown of new vs existing
            $message .= "<strong>📊 Detail Import:</strong><br>";
            $message .= "• {$detailedStats['new_accounts_created']} akun baru dibuat<br>";
            $message .= "• {$detailedStats['existing_accounts_updated']} akun existing (data diupdate)<br>";

            if ($credentialsFile) {
                $message .= "<br><strong>📋 Credentials File:</strong> <a href='" . asset('storage/' . $credentialsFile) . "' target='_blank' class='text-primary-600 underline'>Download Patient Credentials</a>";
            }

            return redirect()->route('admin.import')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();

            \Log::error('Import error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Error during import: ' . $e->getMessage());
        }
    }

    private function detectColumnMapping($headers)
    {
        $mapping = [];

        // Define possible column names for each field
        $fieldMappings = [
            // Patient basic info
            'name' => ['nama'],
            'tanggal_lahir' => ['tanggal lahir'],
            'jenis_kelamin' => ['jenis kelamin'],
            'umur' => ['umur'],
            'departemen' => ['departemen'],
            'jabatan' => ['jabatan'],
            'tgl_order' => ['tanggal mcu'],
            'no_lab' => ['no lab'],
            'cabang' => ['cabang'],
            'mou' => ['mou'],

            // Pemeriksaan Vital
            'berat_badan' => ['berat badan'],
            'tinggi_badan' => ['tinggi badan'],
            'lingkar_perut' => ['lingkar perut'],
            'bmi' => ['bmi'],

            // Tanda Vital
            'tekanan_darah' => ['tekanan darah'],
            'nadi' => ['nadi'],
            'suhu_tubuh' => ['suhu tubuh'],

            // Lab Hematologi
            'hemoglobin' => ['hemoglobin'],
            'erytrosit' => ['erytrosit'],
            'hematokrit' => ['hematokrit'],
            'mcv' => ['mcv'],
            'mch' => ['mch'],
            'mchc' => ['mchc'],
            'rdw' => ['rdw'],
            'leukosit' => ['leukosit'],
            'eosinofil' => ['eosinofil'],
            'basofil' => ['basofil'],
            'neutrofil_batang' => ['neutrofil batang'],
            'neutrofil_segmen' => ['neutrofil segmen'],
            'limfosit' => ['limfosit'],
            'monosit' => ['monosit'],
            'trombosit' => ['trombosit'],
            'laju_endap_darah' => ['laju endap darah'],
            'kesimpulan_hematologi' => ['kesimpulan hematologi'],

            // Lab Fungsi Liver
            'sgot' => ['sgot'],
            'sgpt' => ['sgpt'],
            'kesimpulan_fungsi_hati' => ['kesimpulan fungsi hati'],

            // Lab Profil Lemak
            'cholesterol' => ['kolesterol'],
            'trigliserida' => ['trigliserida'],
            'hdl_cholesterol' => ['hdl'],
            'ldl_cholesterol' => ['ldl'],
            'kesimpulan_profil_lemak' => ['kesimpulan profil lemak'],

            // Lab Glukosa Darah
            'glukosa_puasa' => ['glukosa puasa'],
            'glukosa_2jam_pp' => ['glukosa 2 jam pp'],
            'hba1c' => ['hba1c'],
            'kesimpulan_glukosa' => ['kesimpulan glukosa darah'],

            // Lab Fungsi Ginjal
            'ureum' => ['ureum'],
            'creatinin' => ['kreatinin'],
            'asam_urat' => ['asam urat'],
            'kesimpulan_fungsi_ginjal' => ['kesimpulan fungsi ginjal'],

            // Lab Penanda Tumor
            'hbsag' => ['hbsag'],
            'cea' => ['cea'],
            'kesimpulan_penanda_tumor' => ['kesimpulan penanda tumor'],

            // Lab Urine
            'warna' => ['warna'],
            'kejernihan' => ['kejernihan'],
            'bj' => ['bj'],
            'ph' => ['ph'],
            'protein' => ['protein'],
            'glukosa' => ['glukosa'],
            'keton' => ['keton'],
            'bilirubin' => ['bilirubin'],
            'urobilinogen' => ['urobilinogen'],
            'nitrit' => ['nitrit'],
            'darah' => ['darah'],
            'lekosit_esterase' => ['leukosit esterase'],
            'kesimpulan_urine' => ['kesimpulan urine'],

            // Lab Sedimen Urine
            'sedimen_eritrosit' => ['sedimen eritrosit'],
            'sedimen_leukosit' => ['sedimen leukosit'],
            'sedimen_epitel' => ['sedimen epitel'],
            'sedimen_silinder' => ['sedimen silinder'],
            'sedimen_kristal' => ['sedimen kristal'],
            'sedimen_bakteri' => ['sedimen bakteri'],
            'sedimen_jamur' => ['sedimen jamur'],
            'sedimen_lain_lain' => ['sedimen lain-lain'],

            // Radiologi
            'ecg' => ['ecg'],
            'kesimpulan_ecg' => ['kesimpulan ecg'],
            'thorax_pa' => ['thorax pa'],
            'kesimpulan_thorax_pa' => ['kesimpulan thorax pa'],

            // Pemeriksaan Gigi
            'kondisi_gigi' => ['kondisi gigi'],

            // Pemeriksaan Mata
            'dengan_kacamata' => ['dengan kacamata'],
            'tanpa_kacamata' => ['tanpa kacamata'],

            // Status Gizi
            'status_gizi' => ['status gizi'],

            // Lifestyle
            'merokok' => ['merokok'],
            'minum_alkohol' => ['alkohol'],
            'olahraga' => ['olahraga']
        ];

        // Use exact matching only to prevent conflicts
        foreach ($headers as $index => $header) {
            $headerLower = strtolower(trim($header));

            foreach ($fieldMappings as $field => $variations) {
                foreach ($variations as $variation) {
                    $variationLower = strtolower($variation);

                    // Exact match only
                    if ($headerLower === $variationLower) {
                        $mapping[$field] = $index;
                        break 2;
                    }
                }
            }
        }

        return $mapping;
    }

    private function validatePreviewData($rows, $columnMapping)
    {
        $results = [
            'valid_rows' => 0,
            'invalid_rows' => 0,
            'errors' => []
        ];

        // Debug: Log column mapping
        \Log::info('Column mapping for validation', [
            'mapping' => $columnMapping,
            'total_fields_mapped' => count($columnMapping)
        ]);

        // Log specific problematic fields
        if (isset($columnMapping['leukosit'])) {
            \Log::info('Leukosit mapping details', [
                'field' => 'leukosit',
                'column_index' => $columnMapping['leukosit'],
                'header_at_index' => $headers[$columnMapping['leukosit']] ?? 'NOT_FOUND'
            ]);
        }

        foreach ($rows as $rowIndex => $row) {
            $errors = [];

            // Debug: Log row data for first row
            if ($rowIndex === 0) {
                \Log::info('First row data for validation', ['row' => $row]);
            }

            // Check required fields
            if (isset($columnMapping['name']) && empty($row[$columnMapping['name']])) {
                $errors[] = 'Nama pasien tidak boleh kosong';
            }

            if (isset($columnMapping['tgl_order']) && !empty($row[$columnMapping['tgl_order']])) {
                $date = $row[$columnMapping['tgl_order']];
                if (!$this->isValidDate($date)) {
                    $errors[] = 'Format tanggal tidak valid';
                }
            }

            // Validate numeric fields (exclude sedimen fields which can contain ranges like "0-2")
            $numericFields = ['umur', 'hemoglobin', 'leukosit', 'sgot', 'sgpt', 'berat_badan', 'tinggi_badan',
                            'erytrosit', 'hematokrit', 'mcv', 'mch', 'mchc', 'rdw', 'trombosit', 'cholesterol',
                            'trigliserida', 'hdl_cholesterol', 'ldl_cholesterol', 'glukosa_puasa', 'ureum',
                            'creatinin', 'asam_urat', 'nadi', 'suhu_tubuh', 'eosinofil', 'basofil',
                            'neutrofil_batang', 'neutrofil_segmen', 'limfosit', 'monosit', 'laju_endap_darah',
                            'glukosa_2jam_pp', 'hba1c', 'bj', 'ph'];

            foreach ($numericFields as $field) {
                if (isset($columnMapping[$field]) && !empty($row[$columnMapping[$field]])) {
                    $originalValue = $row[$columnMapping[$field]];
                    $value = trim($originalValue);

                    // Handle Excel auto-converted dates back to numbers
                    if (preg_match('/(\d{1,2})-([A-Za-z]{3})/', $value, $matches)) {
                        // This looks like Excel converted "3-5" to "5-Mar", skip validation for sedimen fields
                        $sedimenFields = ['sedimen_eritrosit', 'sedimen_leukosit', 'sedimen_epitel', 'sedimen_silinder'];
                        if (in_array($field, $sedimenFields)) {
                            continue; // Skip validation for sedimen fields
                        }
                    }

                    // Remove common formatting characters that might interfere
                    $value = str_replace([',', ' '], '', $value);

                    if (!is_numeric($value) && $value !== '') {
                        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' harus berupa angka (nilai: "' . $originalValue . '", index kolom: ' . $columnMapping[$field] . ')';
                    }
                }
            }

            if (empty($errors)) {
                $results['valid_rows']++;
            } else {
                $results['invalid_rows']++;
                $results['errors'][$rowIndex] = $errors;
            }
        }

        return $results;
    }

    private function isValidDate($date)
    {
        if (empty($date)) {
            return true; // Empty date is acceptable
        }

        // Convert to string and trim
        $date = trim((string) $date);

        // Handle Excel serial date numbers (like 45123)
        if (is_numeric($date) && $date > 1) {
            try {
                // Excel epoch starts from 1900-01-01, but has a bug with 1900 leap year
                $excelEpoch = new \DateTime('1900-01-01');
                $days = (int) $date - 2; // Adjust for Excel's leap year bug
                $excelEpoch->add(new \DateInterval('P' . $days . 'D'));
                return true;
            } catch (\Exception $e) {
                // Continue to other formats
            }
        }

        // Define multiple date formats
        $formats = [
            'Y-m-d',           // 2024-01-15
            'd/m/Y',           // 15/01/2024
            'd-m-Y',           // 15-01-2024
            'Y/m/d',           // 2024/01/15
            'm/d/Y',           // 01/15/2024
            'd.m.Y',           // 15.01.2024
            'Y.m.d',           // 2024.01.15
            'd M Y',           // 15 Jan 2024
            'd F Y',           // 15 January 2024
            'Y-m-d H:i:s',     // 2024-01-15 10:30:00
            'd/m/Y H:i',       // 15/01/2024 10:30
        ];

        foreach ($formats as $format) {
            try {
                $dateTime = \DateTime::createFromFormat($format, $date);
                if ($dateTime && $dateTime->format($format) === $date) {
                    return true;
                }

                // Try with flexible parsing (without strict format matching)
                if ($dateTime !== false) {
                    // Check if the date is reasonable (between 1900 and 2100)
                    $year = $dateTime->format('Y');
                    if ($year >= 1900 && $year <= 2100) {
                        return true;
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Try using strtotime as fallback
        try {
            $timestamp = strtotime($date);
            if ($timestamp !== false && $timestamp > 0) {
                $testDate = new \DateTime();
                $testDate->setTimestamp($timestamp);
                $year = $testDate->format('Y');
                if ($year >= 1900 && $year <= 2100) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            // Continue
        }

        return false;
    }

    private function checkForExistingPatients($rows, $columnMapping)
    {
        $results = [];

        if (!isset($columnMapping['name']) || !isset($columnMapping['tanggal_lahir'])) {
            return $results;
        }

        foreach ($rows as $index => $row) {
            $name = trim($row[$columnMapping['name']] ?? '');
            $birthDate = trim($row[$columnMapping['tanggal_lahir']] ?? '');

            if (empty($name) || empty($birthDate)) {
                $results[$index] = ['status' => 'insufficient_data'];
                continue;
            }

            // Normalize name
            $normalizedName = strtolower(str_replace(' ', '', $name));

            // Parse birth date
            $parsedBirthDate = $this->parseDate($birthDate);
            if (!$parsedBirthDate) {
                $results[$index] = ['status' => 'invalid_date'];
                continue;
            }

            // Check if patient exists with this name + birth date
            $existingPatient = Patient::whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [$normalizedName])
                ->whereDate('tanggal_lahir', $parsedBirthDate->format('Y-m-d'))
                ->first();

            if ($existingPatient) {
                $results[$index] = [
                    'status' => 'existing',
                    'patient' => $existingPatient,
                    'action' => 'update'
                ];
            } else {
                $results[$index] = [
                    'status' => 'new',
                    'action' => 'create'
                ];
            }
        }

        return $results;
    }

    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        // Convert to string and trim
        $date = trim((string) $date);

        // Handle Excel serial date numbers
        if (is_numeric($date) && $date > 1) {
            try {
                $excelEpoch = new \DateTime('1900-01-01');
                $days = (int) $date - 2;
                $excelEpoch->add(new \DateInterval('P' . $days . 'D'));
                return $excelEpoch;
            } catch (\Exception $e) {
                // Continue to other formats
            }
        }

        // Define multiple date formats
        $formats = [
            'Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d', 'm/d/Y', 'd.m.Y', 'Y.m.d',
            'd M Y', 'd F Y', 'Y-m-d H:i:s', 'd/m/Y H:i'
        ];

        foreach ($formats as $format) {
            try {
                $dateTime = \DateTime::createFromFormat($format, $date);
                if ($dateTime && $dateTime->format('Y') >= 1900 && $dateTime->format('Y') <= 2100) {
                    return $dateTime;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Try strtotime as fallback
        try {
            $timestamp = strtotime($date);
            if ($timestamp !== false && $timestamp > 0) {
                $testDate = new \DateTime();
                $testDate->setTimestamp($timestamp);
                if ($testDate->format('Y') >= 1900 && $testDate->format('Y') <= 2100) {
                    return $testDate;
                }
            }
        } catch (\Exception $e) {
            // Continue
        }

        return null;
    }
}