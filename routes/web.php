<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HealthComparisonController;
use App\Http\Controllers\TrendConfigController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('patients', PatientController::class);
        Route::get('/patients/{patient}/export', [PatientController::class, 'export'])->name('patients.export');

        // Import routes
        Route::get('/admin/import', [App\Http\Controllers\Admin\ImportController::class, 'index'])->name('admin.import');
        Route::post('/admin/import/preview', [App\Http\Controllers\Admin\ImportController::class, 'preview'])->name('admin.import.preview');
        Route::get('/admin/import/preview', [App\Http\Controllers\Admin\ImportController::class, 'redirectToImport'])->name('admin.import.preview.redirect');
        Route::post('/admin/import/process', [App\Http\Controllers\Admin\ImportController::class, 'process'])->name('admin.import.process');
        Route::get('/admin/import/process', [App\Http\Controllers\Admin\ImportController::class, 'redirectToImport']);

        Route::get('/admin/health-records', [DashboardController::class, 'adminHealthRecords'])->name('admin.health-records');

        // Medical record management
        Route::get('/patients/{patient}/medical-records', [App\Http\Controllers\Admin\MedicalRecordController::class, 'edit'])->name('medical-records.patient');
        Route::get('/patients/{patient}/orders/{order}/edit', [App\Http\Controllers\Admin\MedicalRecordController::class, 'edit'])->name('medical-records.edit');
        Route::put('/patients/{patient}/orders/{order}', [App\Http\Controllers\Admin\MedicalRecordController::class, 'update'])->name('medical-records.update');

        // Normal values management
        Route::get('/admin/normal-values', [App\Http\Controllers\Admin\MedicalRecordController::class, 'normalValues'])->name('admin.normal-values');
        Route::put('/admin/normal-values', [App\Http\Controllers\Admin\MedicalRecordController::class, 'updateNormalValues'])->name('admin.normal-values.update');

        // Trend configuration routes
        Route::get('/patients/{patient}/trend-config', [TrendConfigController::class, 'index'])->name('trend-config.index');
        Route::get('/patients/{patient}/trend-config/data', [TrendConfigController::class, 'getConfig'])->name('trend-config.get');
        Route::post('/patients/{patient}/trend-config', [TrendConfigController::class, 'saveConfig'])->name('trend-config.save');
        Route::post('/patients/{patient}/trend-config/apply-template', [TrendConfigController::class, 'applyTemplate'])->name('trend-config.apply-template');
        Route::post('/patients/{patient}/trend-config/copy', [TrendConfigController::class, 'copyFromPatient'])->name('trend-config.copy');
        Route::get('/trend-config/patients', [TrendConfigController::class, 'getPatients'])->name('trend-config.patients');

        // User management routes
        Route::get('/users/create', [App\Http\Controllers\Admin\UserManagementController::class, 'createUser'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
        Route::get('/users/{user}/profile/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'editProfile'])->name('users.profile.edit');
        Route::put('/users/{user}/profile', [App\Http\Controllers\Admin\UserManagementController::class, 'updateProfile'])->name('users.profile.update');
        Route::get('/users/{user}/credentials/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'editCredentials'])->name('users.credentials.edit');
        Route::put('/users/{user}/credentials', [App\Http\Controllers\Admin\UserManagementController::class, 'updateCredentials'])->name('users.credentials.update');
        Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'deleteUser'])->name('users.destroy');
    });

    // User routes
    Route::get('/health-check/{shareId}', [PatientController::class, 'showHealthCheck'])->name('health-check');
    Route::get('/my-health', [PatientController::class, 'myHealth'])->name('my-health');

});

// PDF export routes removed - now using direct DomPDF export

// Protected routes continued
Route::middleware('auth')->group(function () {
    // Health Comparison routes
    Route::get('/health-comparison', [HealthComparisonController::class, 'index'])->name('health-comparison.index');
    Route::post('/health-comparison/compare', [HealthComparisonController::class, 'compare'])->name('health-comparison.compare');
    Route::get('/health-comparison/trends', [HealthComparisonController::class, 'trends'])->name('health-comparison.trends');
    Route::get('/health-comparison/export', [HealthComparisonController::class, 'exportComparison'])->name('health-comparison.export');


    // Debug route to check data
    Route::get('/check-data', function() {
        $patients = \App\Models\Patient::with(['orders' => function($query) {
            $query->orderBy('tgl_order');
        }])->get();

        echo "<h2>DATA MCU PER TAHUN</h2>";

        foreach ($patients as $patient) {
            echo "<h3>PATIENT: {$patient->name} (ID: {$patient->id})</h3>";
            echo "<p><strong>Share ID:</strong> {$patient->share_id}</p>";
            echo "<p><strong>Lifestyle:</strong> Merokok=" . ($patient->merokok ? 'Ya' : 'Tidak') .
                 ", Alkohol=" . ($patient->minum_alkohol ? 'Ya' : 'Tidak') .
                 ", Olahraga=" . ($patient->olahraga ? 'Ya' : 'Tidak') . "</p>";

            echo "<p><strong>MCU Records:</strong></p><ul>";
            foreach ($patient->orders as $order) {
                $year = $order->tgl_order->year;
                echo "<li>Tahun {$year}: Order #{$order->id} | Tanggal: {$order->tgl_order->format('Y-m-d')}</li>";
            }
            echo "</ul><hr>";
        }

        echo "<h3>SUMMARY PER TAHUN</h3>";
        $ordersByYear = \App\Models\Order::selectRaw('YEAR(tgl_order) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        foreach ($ordersByYear as $yearData) {
            echo "<p>Tahun {$yearData->year}: {$yearData->total} MCU records</p>";
        }

        echo "<h3>CARA MEMBEDAKAN DATA PER TAHUN:</h3>";
        echo "<ol>";
        echo "<li><strong>Filter berdasarkan tahun:</strong><br><code>\$orders = Patient::find(1)->orders()->whereYear('tgl_order', 2023)->get();</code></li>";
        echo "<li><strong>Bandingkan 2 tahun:</strong><br><code>\$data2023 = Patient::find(1)->orders()->whereYear('tgl_order', 2023)->with('labHematologi')->first();</code></li>";
        echo "<li><strong>Ambil semua tahun:</strong><br><code>\$allYears = Patient::find(1)->orders()->orderBy('tgl_order')->get();</code></li>";
        echo "<li><strong>Group by tahun:</strong><br><code>\$grouped = \$orders->groupBy(function(\$order) { return \$order->tgl_order->year; });</code></li>";
        echo "</ol>";

        echo "<h3>CONTOH REAL DATA:</h3>";
        $patient1 = \App\Models\Patient::find(1);
        if ($patient1) {
            $orders2023 = $patient1->orders()->whereYear('tgl_order', 2023)->with('labHematologi')->first();
            $orders2024 = $patient1->orders()->whereYear('tgl_order', 2024)->with('labHematologi')->first();

            if ($orders2023 && $orders2024) {
                echo "<p><strong>{$patient1->name} - Perbandingan Hemoglobin:</strong></p>";
                echo "<p>2023: " . ($orders2023->labHematologi->hemoglobin ?? 'N/A') . "</p>";
                echo "<p>2024: " . ($orders2024->labHematologi->hemoglobin ?? 'N/A') . "</p>";

                if ($orders2023->labHematologi && $orders2024->labHematologi) {
                    $diff = $orders2024->labHematologi->hemoglobin - $orders2023->labHematologi->hemoglobin;
                    echo "<p><strong>Perubahan:</strong> " . ($diff > 0 ? '+' : '') . number_format($diff, 1) . "</p>";
                }
            }
        }
    });

    // Profile routes
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
    Route::delete('/profile/delete-photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
});