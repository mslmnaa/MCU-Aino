<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Order;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('orders')->paginate(10);
        return view('admin.patients.index', compact('patients'));
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
            ]);
        }]);

        return view('admin.patients.show', compact('patient'));
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

    public function myHealth()
    {
        $user = auth()->user();
        $patient = $user->patient;

        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'No medical records found for your account.');
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
            ]);
        }]);

        return view('user.health-check', compact('patient'));
    }
}