<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $totalPatients = Patient::count();
            $totalOrders = Order::count();
            $recentOrders = Order::with('patient')->latest()->take(10)->get();

            return view('admin.dashboard', compact('totalPatients', 'totalOrders', 'recentOrders'));
        } else {
            // For regular users, check if they have a patient record
            $patient = $user->patient;
            if ($patient) {
                // User has patient data, redirect to their health results
                return redirect()->route('my-health');
            } else {
                // No patient data found
                return view('user.dashboard')->with('error', 'No medical records found for your account. Please contact the administrator.');
            }
        }
    }

    public function adminHealthRecords()
    {
        $orders = Order::with([
            'patient',
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
        ])->paginate(10);

        return view('admin.health-records', compact('orders'));
    }
}