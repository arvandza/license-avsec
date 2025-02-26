<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\License;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $employees = Employee::with('license', 'diklatSchedule', 'rekurenSchedule')->get();
        $totalEmployee = $employees->count();
        $totalLicenses = License::where('status', 'ACCEPTED')->count();
        $totalDiklat = $employees->pluck('diklatSchedule')->flatten()->count();
        $totalRekuren = $employees->pluck('rekurenSchedule')->flatten()->count();

        return view('admin.dashboard', compact('totalEmployee', 'totalLicenses', 'totalDiklat', 'totalRekuren'));
    }
}
