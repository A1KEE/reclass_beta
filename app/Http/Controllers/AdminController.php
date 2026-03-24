<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\School;
use App\Models\PpstIndicator;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'total' => Application::count(),
            'submitted' => Application::where('status', 'submitted')->count(),
            'draft' => Application::where('status', 'draft')->count(),
        ]);
    }

    public function applicants()
    {
        $applicants = Application::latest()->get();
        return view('admin.applicants', compact('applicants'));
    }

public function show($id)
{
    $application = Application::with([
        'educations',
        'experiences',
        'eligibilities',
        'ipcrfs',
        'ppstRatings' // optional pero recommended
    ])->findOrFail($id);

    $positions = [
        'Teacher I', 'Teacher II', 'Teacher III',
        'Teacher IV', 'Teacher V', 'Teacher VI', 'Teacher VII',
        'Master Teacher I', 'Master Teacher II', 'Master Teacher III'
    ];

    $schools = School::all();

    // ✅ ADD THIS
    $ppstIndicators = PpstIndicator::orderBy('order')->get();

    return view('admin.view', compact(
        'application',
        'positions',
        'schools',
        'ppstIndicators'
    ));
}
    public function settings()
    {
        return view('admin.settings');
    }
    
}