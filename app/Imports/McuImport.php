<?php

namespace App\Imports;

use App\Models\Patient;
use App\Models\Order;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Carbon\Carbon;

class McuImport implements ToModel, SkipsOnError
{
    use SkipsErrors;

    protected $columnMapping;
    protected $importStats = [
        'success' => 0,
        'errors' => 0
    ];
    protected $rowCount = 0;
    protected $credentials = [];

    public function __construct($columnMapping = [])
    {
        $this->columnMapping = $columnMapping;
    }

    public function model(array $row)
    {
        try {
            $this->rowCount++;

            // Skip header row (first row)
            if ($this->rowCount === 1) {
                return null;
            }

            // Skip empty rows
            if (empty(array_filter($row))) {
                return null;
            }

            // Map columns based on provided mapping
            $mappedData = $this->mapRowData($row);

            // Find or create patient
            $patient = $this->findOrCreatePatient($mappedData);

            // Create medical checkup order
            $order = $this->createMedicalOrder($patient, $mappedData);

            // Create lab results
            $this->createLabResults($order, $mappedData);

            $this->importStats['success']++;

            return $patient; // Return patient model for Laravel Excel

        } catch (\Exception $e) {
            $this->importStats['errors']++;
            \Log::error('Import error: ' . $e->getMessage(), ['row' => $row]);
            return null;
        }
    }

    private function mapRowData($row)
    {
        $mapped = [];

        if (empty($this->columnMapping)) {
            return $mapped;
        }

        foreach ($this->columnMapping as $field => $columnIndex) {
            if (isset($row[$columnIndex]) && !empty(trim($row[$columnIndex]))) {
                $mapped[$field] = trim($row[$columnIndex]);
            }
        }

        return $mapped;
    }

    private function findOrCreatePatient($data)
    {
        // Debug: Log patient data being processed
        \Log::info('Processing patient data', [
            'name' => $data['name'] ?? 'NOT_SET',
            'tanggal_lahir' => $data['tanggal_lahir'] ?? 'NOT_SET',
            'all_data_keys' => array_keys($data),
            'name_exists' => isset($data['name'])
        ]);

        $normalizedName = $this->normalizeString($data['name'] ?? '');
        $parsedBirthDate = $this->parseDate($data['tanggal_lahir'] ?? null);

        // Step 1: Try to find existing patient by normalized name and birth date
        $patient = Patient::whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [$normalizedName])
            ->whereDate('tanggal_lahir', $parsedBirthDate->format('Y-m-d'))
            ->first();

        if (!$patient) {
            // Step 2: No existing patient found - create new user and patient
            $defaultEmail = $this->generateUniqueEmail($data['name'] ?? '', $data['departemen'] ?? '');
            $defaultPassword = $this->generateDefaultPassword($data['name'] ?? '');

            // Create new User
            $user = User::create([
                'name' => $data['name'] ?? '',
                'email' => $defaultEmail,
                'password' => bcrypt($defaultPassword),
                'email_verified_at' => now()
            ]);

            // Create new Patient and associate with User
            $patient = Patient::create([
                'user_id' => $user->id,
                'name' => $data['name'] ?? '',
                'tanggal_lahir' => $parsedBirthDate,
                'jenis_kelamin' => $this->parseGender($data['jenis_kelamin'] ?? ''),
                'umur' => (int) ($data['umur'] ?? 0),
                'departemen' => $data['departemen'] ?? '',
                'jabatan' => $data['jabatan'] ?? '',
                'share_id' => $this->generateShareId(),
                'riwayat_kebiasaan_hidup' => $this->buildLifestyleHistory($data),
                'merokok' => $this->parseBoolean($data['merokok'] ?? ''),
                'minum_alkohol' => $this->parseBoolean($data['minum_alkohol'] ?? ''),
                'olahraga' => $this->parseBoolean($data['olahraga'] ?? ''),
                'profile_photo' => null
            ]);

            // Store credentials for CSV export (only for new patients)
            $this->credentials[] = [
                'name' => $patient->name,
                'email' => $defaultEmail,
                'password' => $defaultPassword,
                'departemen' => $data['departemen'] ?? '',
                'jabatan' => $data['jabatan'] ?? '',
                'patient_id' => $patient->id,
                'user_id' => $user->id,
                'status' => 'NEW'
            ];

            \Log::info('Created NEW patient with credentials', [
                'patient_id' => $patient->id,
                'user_id' => $user->id,
                'name' => $patient->name,
                'email' => $defaultEmail
            ]);
        } else {
            // Step 3: Existing patient found - update data but keep existing user/email
            \Log::info('Found EXISTING patient - updating data', [
                'patient_id' => $patient->id,
                'user_id' => $patient->user_id,
                'existing_name' => $patient->name,
                'existing_email' => $patient->user ? $patient->user->email : 'NO_USER'
            ]);

            // Update existing patient data if provided
            $updateData = [];
            if (!empty($data['jenis_kelamin'])) $updateData['jenis_kelamin'] = $this->parseGender($data['jenis_kelamin']);
            if (!empty($data['umur'])) $updateData['umur'] = (int) $data['umur'];
            if (!empty($data['departemen'])) $updateData['departemen'] = $data['departemen'];
            if (!empty($data['jabatan'])) $updateData['jabatan'] = $data['jabatan'];
            if (isset($data['merokok'])) $updateData['merokok'] = $this->parseBoolean($data['merokok']);
            if (isset($data['minum_alkohol'])) $updateData['minum_alkohol'] = $this->parseBoolean($data['minum_alkohol']);
            if (isset($data['olahraga'])) $updateData['olahraga'] = $this->parseBoolean($data['olahraga']);

            if (!empty($updateData)) {
                $patient->update($updateData);
                \Log::info('Updated existing patient data', ['updated_fields' => array_keys($updateData)]);
            }

            // Add to tracking (but no new credentials)
            $this->credentials[] = [
                'name' => $patient->name,
                'email' => $patient->user ? $patient->user->email : 'NO_USER',
                'password' => '(existing account)',
                'departemen' => $data['departemen'] ?? $patient->departemen,
                'jabatan' => $data['jabatan'] ?? $patient->jabatan,
                'patient_id' => $patient->id,
                'user_id' => $patient->user_id,
                'status' => 'EXISTING'
            ];
        }

        return $patient;
    }

    private function createMedicalOrder($patient, $data)
    {
        $orderDate = $this->parseDate($data['tgl_order'] ?? null) ?: now();
        $noLab = $data['no_lab'] ?? $this->generateLabNumber();

        // Check if order already exists for this patient on SAME DATE with SAME lab number
        // Allow multiple MCU for same patient on different dates
        $existingOrder = Order::where('patient_id', $patient->id)
            ->where('no_lab', $noLab)
            ->whereDate('tgl_order', $orderDate->format('Y-m-d'))
            ->first();

        if ($existingOrder) {
            \Log::info('Order already exists for same patient, same date, same no_lab - updating', [
                'patient_id' => $patient->id,
                'no_lab' => $noLab,
                'order_date' => $orderDate->format('Y-m-d'),
                'existing_order_id' => $existingOrder->id
            ]);
            return $existingOrder;
        }

        $order = Order::create([
            'patient_id' => $patient->id,
            'tgl_order' => $orderDate,
            'no_lab' => $noLab,
            'cabang' => $data['cabang'] ?? 'Lab Utama',
            'mou' => $data['mou'] ?? 'Default MOU'
        ]);

        \Log::info('Created new order', [
            'patient_id' => $patient->id,
            'order_id' => $order->id,
            'no_lab' => $noLab
        ]);

        return $order;
    }

    private function createLabResults($order, $data)
    {
        // Create Pemeriksaan Vital
        if ($this->hasVitalData($data)) {
            $order->pemeriksaanVital()->create([
                'berat_badan' => $this->formatValue($data['berat_badan'] ?? null, 'kg'),
                'tinggi_badan' => $this->formatValue($data['tinggi_badan'] ?? null, 'cm'),
                'bmi' => $this->calculateBmi($data['berat_badan'] ?? null, $data['tinggi_badan'] ?? null),
                'klasifikasi_tekanan_darah' => $this->classifyBloodPressure($data['tekanan_darah'] ?? null)
            ]);
        }

        // Create Tanda Vital
        if (!empty($data['tekanan_darah'])) {
            $order->tandaVital()->create([
                'tekanan_darah' => $data['tekanan_darah'],
                'nadi' => $this->formatValue($data['nadi'] ?? null, 'x/menit'),
                'pernapasan' => $this->formatValue($data['pernapasan'] ?? null, 'x/menit'),
                'suhu_tubuh' => $this->formatValue($data['suhu_tubuh'] ?? null, '°C')
            ]);
        }

        // Create Lab Hematologi
        if ($this->hasHematologyData($data)) {
            $order->labHematologi()->create([
                'hemoglobin' => $this->formatValue($data['hemoglobin'] ?? null, 'g/dL'),
                'erytrosit' => $this->formatValue($data['erytrosit'] ?? null, 'juta/μL'),
                'hematokrit' => $this->formatValue($data['hematokrit'] ?? null, '%'),
                'mcv' => $this->formatValue($data['mcv'] ?? null, 'fL'),
                'mch' => $this->formatValue($data['mch'] ?? null, 'pg'),
                'mchc' => $this->formatValue($data['mchc'] ?? null, 'g/dL'),
                'rdw' => $this->formatValue($data['rdw'] ?? null, '%'),
                'leukosit' => $this->formatValue($data['leukosit'] ?? null, '/μL'),
                'eosinofil' => $this->formatValue($data['eosinofil'] ?? null, '%'),
                'basofil' => $this->formatValue($data['basofil'] ?? null, '%'),
                'neutrofil_batang' => $this->formatValue($data['neutrofil_batang'] ?? null, '%'),
                'neutrofil_segmen' => $this->formatValue($data['neutrofil_segmen'] ?? null, '%'),
                'limfosit' => $this->formatValue($data['limfosit'] ?? null, '%'),
                'monosit' => $this->formatValue($data['monosit'] ?? null, '%'),
                'trombosit' => $this->formatValue($data['trombosit'] ?? null, '/μL'),
                'laju_endap_darah' => $this->formatValue($data['laju_endap_darah'] ?? null, 'mm/jam'),
                'kesimpulan_hematologi' => $data['kesimpulan_hematologi'] ?? null
            ]);
        }

        // Create Lab Fungsi Liver
        if (!empty($data['sgot']) || !empty($data['sgpt']) || !empty($data['kesimpulan_fungsi_hati'])) {
            $order->labFungsiLiver()->create([
                'sgot' => $this->formatValue($data['sgot'] ?? null, 'U/L'),
                'sgpt' => $this->formatValue($data['sgpt'] ?? null, 'U/L'),
                'kesimpulan_fungsi_hati' => $data['kesimpulan_fungsi_hati'] ?? null
            ]);
        }

        // Create Lab Profil Lemak
        if (!empty($data['cholesterol']) || !empty($data['kesimpulan_profil_lemak'])) {
            $order->labProfilLemak()->create([
                'cholesterol' => $this->formatValue($data['cholesterol'] ?? null, 'mg/dL'),
                'trigliserida' => $this->formatValue($data['trigliserida'] ?? null, 'mg/dL'),
                'hdl_cholesterol' => $this->formatValue($data['hdl_cholesterol'] ?? null, 'mg/dL'),
                'ldl_cholesterol' => $this->formatValue($data['ldl_cholesterol'] ?? null, 'mg/dL'),
                'kesimpulan_profil_lemak' => $data['kesimpulan_profil_lemak'] ?? null
            ]);
        }

        // Create Lab Gula Darah
        if (!empty($data['glukosa_puasa']) || !empty($data['kesimpulan_glukosa'])) {
            $order->labGlukosaDarah()->create([
                'glukosa_puasa' => $this->formatValue($data['glukosa_puasa'] ?? null, 'mg/dL'),
                'glukosa_2jam_pp' => $this->formatValue($data['glukosa_2jam_pp'] ?? null, 'mg/dL'),
                'hba1c' => $this->formatValue($data['hba1c'] ?? null, '%'),
                'kesimpulan_glukosa' => $data['kesimpulan_glukosa'] ?? null
            ]);
        }

        // Create Lab Fungsi Ginjal
        if ($this->hasKidneyData($data) || !empty($data['kesimpulan_fungsi_ginjal'])) {
            $order->labFungsiGinjal()->create([
                'ureum' => $this->formatValue($data['ureum'] ?? null, 'mg/dL'),
                'creatinin' => $this->formatValue($data['creatinin'] ?? null, 'mg/dL'),
                'asam_urat' => $this->formatValue($data['asam_urat'] ?? null, 'mg/dL'),
                'kesimpulan_fungsi_ginjal' => $data['kesimpulan_fungsi_ginjal'] ?? null
            ]);
        }

        // Create Lab Urine
        if ($this->hasUrineData($data)) {
            $urineData = [
                'warna' => $data['warna'] ?? null,
                'kejernihan' => $data['kejernihan'] ?? null,
                'bj' => $data['bj'] ?? null,
                'ph' => $data['ph'] ?? null,
                'protein' => $data['protein'] ?? null,
                'glukosa' => $data['glukosa'] ?? null,
                'keton' => $data['keton'] ?? null,
                'bilirubin' => $data['bilirubin'] ?? null,
                'urobilinogen' => $data['urobilinogen'] ?? null,
                'nitrit' => $data['nitrit'] ?? null,
                'darah' => $data['darah'] ?? null,
                'lekosit_esterase' => $data['lekosit_esterase'] ?? null,
                // Add sedimen data directly to urine record
                'eritrosit_sedimen' => $this->cleanSedimenValue($data['sedimen_eritrosit'] ?? null),
                'lekosit_sedimen' => $this->cleanSedimenValue($data['sedimen_leukosit'] ?? null),
                'epitel_sedimen' => $this->cleanSedimenValue($data['sedimen_epitel'] ?? null),
                'silinder_sedimen' => $this->cleanSedimenValue($data['sedimen_silinder'] ?? null),
                'kristal_sedimen' => $this->cleanSedimenValue($data['sedimen_kristal'] ?? null),
                'lain_lain_sedimen' => $this->cleanSedimenValue($data['sedimen_lain_lain'] ?? null),
                'kesimpulan_urine' => $data['kesimpulan_urine'] ?? null
            ];

            $order->labUrine()->create($urineData);
        }

        // Create Lab Penanda Tumor
        if ($this->hasPenandaTumorData($data)) {
            $order->labPenandaTumor()->create([
                'hbsag' => $data['hbsag'] ?? null,
                'cea' => $this->formatValue($data['cea'] ?? null, 'ng/mL'),
                'kesimpulan_penanda_tumor' => $data['kesimpulan_penanda_tumor'] ?? null
            ]);
        }

        // Create Radiologi
        if ($this->hasRadiologiData($data)) {
            $order->radiologi()->create([
                'ecg' => $data['ecg'] ?? null,
                'kesimpulan_ecg' => $data['kesimpulan_ecg'] ?? null,
                'thorax_pa' => $data['thorax_pa'] ?? null,
                'kesimpulan_thorax_pa' => $data['kesimpulan_thorax_pa'] ?? null
            ]);
        }

        // Create Pemeriksaan Gigi
        if (!empty($data['kondisi_gigi'])) {
            $order->pemeriksaanGigi()->create([
                'kondisi_gigi' => $data['kondisi_gigi'] ?? null
            ]);
        }

        // Create Pemeriksaan Mata
        if ($this->hasPemeriksaanMataData($data)) {
            $order->pemeriksaanMata()->create([
                'dengan_kacamata' => $data['dengan_kacamata'] ?? null,
                'tanpa_kacamata' => $data['tanpa_kacamata'] ?? null
            ]);
        }

        // Create Status Gizi
        if (!empty($data['status_gizi'])) {
            $order->statusGizi()->create([
                'status' => $data['status_gizi'] ?? null
            ]);
        }
    }

    private function hasVitalData($data)
    {
        return !empty($data['berat_badan']) || !empty($data['tinggi_badan']);
    }

    private function hasHematologyData($data)
    {
        return !empty($data['hemoglobin']) || !empty($data['leukosit']) || !empty($data['trombosit']) ||
               !empty($data['erytrosit']) || !empty($data['hematokrit']) || !empty($data['mcv']) ||
               !empty($data['mch']) || !empty($data['mchc']) || !empty($data['rdw']);
    }

    private function hasKidneyData($data)
    {
        return !empty($data['ureum']) || !empty($data['creatinin']) || !empty($data['asam_urat']);
    }

    private function hasUrineData($data)
    {
        return !empty($data['warna']) || !empty($data['kejernihan']) || !empty($data['ph']) ||
               !empty($data['protein']) || !empty($data['glukosa']) || !empty($data['kesimpulan_urine']);
    }

    private function hasPenandaTumorData($data)
    {
        return !empty($data['hbsag']) || !empty($data['cea']) || !empty($data['kesimpulan_penanda_tumor']);
    }

    private function hasRadiologiData($data)
    {
        return !empty($data['ecg']) || !empty($data['kesimpulan_ecg']) ||
               !empty($data['thorax_pa']) || !empty($data['kesimpulan_thorax_pa']);
    }

    private function hasPemeriksaanMataData($data)
    {
        return !empty($data['dengan_kacamata']) || !empty($data['tanpa_kacamata']);
    }


    private function parseGender($gender)
    {
        $gender = strtolower(trim($gender));
        if (in_array($gender, ['l', 'laki-laki', 'male', 'm', 'pria'])) {
            return 'L';
        } elseif (in_array($gender, ['p', 'perempuan', 'female', 'f', 'wanita'])) {
            return 'P';
        }
        return 'L'; // Default
    }

    private function parseDate($date)
    {
        if (empty($date)) return now(); // Default to current date if empty

        // Convert to string and trim
        $date = trim((string) $date);

        // Handle Excel serial date numbers (like 45123)
        if (is_numeric($date) && $date > 1) {
            try {
                // Excel epoch starts from 1900-01-01, but has a bug with 1900 leap year
                $excelEpoch = Carbon::create(1900, 1, 1);
                $days = (int) $date - 2; // Adjust for Excel's leap year bug
                return $excelEpoch->addDays($days);
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
                $parsed = Carbon::createFromFormat($format, $date);
                if ($parsed && $parsed->year >= 1900 && $parsed->year <= 2100) {
                    return $parsed;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Try using Carbon's flexible parsing as fallback
        try {
            $parsed = Carbon::parse($date);
            if ($parsed && $parsed->year >= 1900 && $parsed->year <= 2100) {
                return $parsed;
            }
        } catch (\Exception $e) {
            // Continue
        }

        // Return current date as fallback
        return now();
    }

    private function calculateBmi($weight, $height)
    {
        if (empty($weight) || empty($height)) return null;

        $weight = (float) $weight;
        $height = (float) $height / 100; // Convert cm to meters

        if ($height <= 0) return null;

        return round($weight / ($height * $height), 1);
    }

    private function classifyBloodPressure($bp)
    {
        if (empty($bp)) return null;

        // Parse blood pressure (e.g., "120/80")
        if (preg_match('/(\d+)\/(\d+)/', $bp, $matches)) {
            $systolic = (int) $matches[1];
            $diastolic = (int) $matches[2];

            if ($systolic < 120 && $diastolic < 80) return 'Normal';
            if ($systolic < 130 && $diastolic < 80) return 'Elevated';
            if ($systolic < 140 || $diastolic < 90) return 'High Blood Pressure Stage 1';
            return 'High Blood Pressure Stage 2';
        }

        return 'Normal';
    }

    private function formatValue($value, $unit)
    {
        if (empty($value)) return null;

        // Handle Excel auto-converted dates for sedimen values
        if (preg_match('/(\d{1,2})-([A-Za-z]{3})/', $value, $matches)) {
            // Convert back from Excel date format (e.g., "5-Mar" back to "3-5")
            $monthToNumber = [
                'Jan' => '1', 'Feb' => '2', 'Mar' => '3', 'Apr' => '4',
                'May' => '5', 'Jun' => '6', 'Jul' => '7', 'Aug' => '8',
                'Sep' => '9', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'
            ];

            $day = $matches[1];
            $month = $matches[2];

            if (isset($monthToNumber[$month])) {
                $reconstructed = $monthToNumber[$month] . '-' . $day;
                return $reconstructed; // Return without unit for sedimen fields
            }
        }

        return trim($value) . ' ' . $unit;
    }

    private function cleanSedimenValue($value)
    {
        if (empty($value)) return null;

        // Handle Excel auto-converted dates for sedimen values
        if (preg_match('/(\d{1,2})-([A-Za-z]{3})/', $value, $matches)) {
            // Convert back from Excel date format (e.g., "5-Mar" back to "3-5")
            $monthToNumber = [
                'Jan' => '1', 'Feb' => '2', 'Mar' => '3', 'Apr' => '4',
                'May' => '5', 'Jun' => '6', 'Jul' => '7', 'Aug' => '8',
                'Sep' => '9', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'
            ];

            $day = $matches[1];
            $month = $matches[2];

            if (isset($monthToNumber[$month])) {
                return $monthToNumber[$month] . '-' . $day;
            }
        }

        // Handle "sampai" format
        if (strpos($value, 'sampai') !== false) {
            return str_replace(' sampai ', '-', $value);
        }

        return trim($value);
    }

    private function parseBoolean($value)
    {
        if (empty($value)) return false;
        $value = strtolower(trim($value));
        return in_array($value, ['ya', 'yes', 'true', '1', 'y']);
    }

    private function buildLifestyleHistory($data)
    {
        $lifestyle = [];

        if (isset($data['merokok'])) {
            $lifestyle[] = 'Merokok: ' . ($this->parseBoolean($data['merokok']) ? 'Ya' : 'Tidak');
        }

        if (isset($data['minum_alkohol'])) {
            $lifestyle[] = 'Alkohol: ' . ($this->parseBoolean($data['minum_alkohol']) ? 'Ya' : 'Tidak');
        }

        if (isset($data['olahraga'])) {
            $lifestyle[] = 'Olahraga: ' . ($this->parseBoolean($data['olahraga']) ? 'Ya' : 'Tidak');
        }

        return implode(', ', $lifestyle);
    }

    private function generateShareId()
    {
        return 'MCU-' . strtoupper(Str::random(8));
    }

    private function generateLabNumber()
    {
        return 'LAB-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    private function normalizeString($string)
    {
        return strtolower(str_replace([' ', '.', ',', '-'], '', trim($string)));
    }

    private function generateUniqueEmail($name, $departemen = '')
    {
        // Generate base email pattern
        $baseEmail = $this->generateBaseEmail($name, $departemen);
        $counter = 1;
        $finalEmail = $baseEmail;

        // Ensure email is unique
        while (User::where('email', $finalEmail)->exists()) {
            $finalEmail = str_replace('@company.com', $counter . '@company.com', $baseEmail);
            $counter++;
        }

        return $finalEmail;
    }

    private function generateBaseEmail($name, $departemen = '')
    {
        // Create clean, consistent email from name
        $cleanName = $this->normalizeString($name);

        // Try firstname.lastname pattern first
        $nameParts = explode(' ', trim($name));
        if (count($nameParts) >= 2) {
            $firstname = $this->normalizeString($nameParts[0]);
            $lastname = $this->normalizeString($nameParts[count($nameParts) - 1]);
            return substr($firstname, 0, 8) . '.' . substr($lastname, 0, 8) . '@company.com';
        }

        // Fallback to name + department
        $cleanDept = $this->normalizeString($departemen);
        $namePrefix = substr($cleanName, 0, 6);
        $deptPrefix = !empty($cleanDept) ? substr($cleanDept, 0, 3) : '';

        return $namePrefix . $deptPrefix . '@company.com';
    }

    private function generateDefaultEmail($name, $departemen = '')
    {
        // Backward compatibility - now calls generateUniqueEmail
        return $this->generateUniqueEmail($name, $departemen);
    }

    private function generateDefaultPassword($name)
    {
        // Generate simple password from name + random numbers
        $cleanName = ucfirst(strtolower(str_replace([' ', '.', ',', '-'], '', $name)));
        return substr($cleanName, 0, 4) . rand(1000, 9999);
    }

    public function getImportStats()
    {
        return $this->importStats;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }

    public function saveCredentialsToFile()
    {
        if (empty($this->credentials)) {
            return null;
        }

        $filename = 'import_summary_' . date('Y-m-d_H-i-s') . '.csv';
        $filepath = storage_path('app/public/' . $filename);

        $handle = fopen($filepath, 'w');

        // CSV Header
        fputcsv($handle, [
            'Status',
            'Nama',
            'Email',
            'Password',
            'Departemen',
            'Jabatan',
            'Patient ID',
            'User ID',
            'Keterangan'
        ]);

        // CSV Data with enhanced info
        foreach ($this->credentials as $credential) {
            $keterangan = $credential['status'] === 'NEW'
                ? 'Akun baru dibuat dengan kredensial ini'
                : 'Data ditambahkan ke akun existing';

            fputcsv($handle, [
                $credential['status'],
                $credential['name'],
                $credential['email'],
                $credential['password'],
                $credential['departemen'],
                $credential['jabatan'],
                $credential['patient_id'],
                $credential['user_id'],
                $keterangan
            ]);
        }

        fclose($handle);

        return $filename;
    }

    public function getDetailedStats()
    {
        $newAccounts = array_filter($this->credentials, function($cred) {
            return $cred['status'] === 'NEW';
        });

        $existingAccounts = array_filter($this->credentials, function($cred) {
            return $cred['status'] === 'EXISTING';
        });

        return [
            'total_processed' => count($this->credentials),
            'new_accounts_created' => count($newAccounts),
            'existing_accounts_updated' => count($existingAccounts),
            'success' => $this->importStats['success'],
            'errors' => $this->importStats['errors']
        ];
    }
}