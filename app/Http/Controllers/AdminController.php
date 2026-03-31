<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\School;
use App\Models\PpstIndicator;
use App\Models\ApplicationScore;

class AdminController extends Controller
{
   public function dashboard()
{
    // =========================
    // POSITIONS GROUPED
    // =========================
    $teacherPositions = [
        'Teacher I',
        'Teacher II',
        'Teacher III',
        'Teacher IV',
        'Teacher V',
        'Teacher VI',
        'Teacher VII',
    ];

    $masterPositions = [
        'Master Teacher I',
        'Master Teacher II',
        'Master Teacher III',
    ];

    // =========================
    // COUNTERS
    // =========================
    $teacherCounts = [];
    $masterCounts = [];

    $teacherTotal = 0;
    $masterTotal = 0;

    // Teacher loop
    foreach ($teacherPositions as $pos) {
        $count = Application::where('position_applied', $pos)->count();
        $teacherCounts[] = $count;
        $teacherTotal += $count;
    }

    // Master Teacher loop
    foreach ($masterPositions as $pos) {
        $count = Application::where('position_applied', $pos)->count();
        $masterCounts[] = $count;
        $masterTotal += $count;
    }

    // =========================
    // OVERALL STATS
    // =========================
    $total = Application::count();
    $pending = Application::where('status', 'pending')->count();
    $draft = Application::where('status', 'draft')->count();
    $evaluated = Application::where('status', 'evaluated')->count();

    // =========================
    // RETURN VIEW
    // =========================
    return view('admin.dashboard', [
        'total' => $total,
        'pending' => $pending,
        'draft' => $draft,
        'evaluated' => $evaluated,

        // labels
        'teacherPositions' => $teacherPositions,
        'masterPositions' => $masterPositions,

        // data
        'teacherCounts' => $teacherCounts,
        'masterCounts' => $masterCounts,

        // totals for percent
        'teacherTotal' => $teacherTotal,
        'masterTotal' => $masterTotal,
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
        'trainings',
        'experiences',
        'eligibilities',
        'ipcrfs',
        'ppstRatings',
        'scores'
    ])->findOrFail($id);

    $positions = [
        'Teacher I', 'Teacher II', 'Teacher III',
        'Teacher IV', 'Teacher V', 'Teacher VI', 'Teacher VII',
        'Master Teacher I', 'Master Teacher II', 'Master Teacher III'
    ];

    $schools = School::all();

    // Detect position group
    // Detect position group
    if (str_contains($application->position_applied, 'Master Teacher')) {
        $positionLevel = 'Master Teacher II–III';
    } else {
        $positionLevel = 'Teacher I – MT I';
    }

    // Get only matching indicators
    $ppstIndicators = PpstIndicator::where('position_level', $positionLevel)
        ->orderBy('domain')
        ->orderBy('order')
        ->get();
    $ratings = $application->ppstRatings->keyBy('ppst_indicator_id');


    return view('admin.view', [
        'application' => $application,
        'positions' => $positions,
        'schools' => $schools,
        'ppstIndicators' => $ppstIndicators,
        'ratings' => $ratings,

        // 🔥 IMPORTANT: for JS adminData
        'adminData' => [
            'educations'   => $application->educations,
            'trainings'    => $application->trainings,
            'experiences'  => $application->experiences,
            'eligibilities'=> $application->eligibilities,
            'ipcrfs'       => $application->ipcrfs,
            'ppstRatings'  => $application->ppstRatings,
            'scores'       => $application->scores,
        ]
    ]);
}
public function update(Request $request, $id)
{
    $score = ApplicationScore::firstOrCreate(
        ['application_id' => $id]
    );

    $score->education_points   = $request->comparative['education'] ?? 0;
    $score->training_points    = $request->comparative['training'] ?? 0;
    $score->experience_points  = $request->comparative['experience_points'] ?? 0;
    $score->performance_points = $request->comparative['performance'] ?? 0;

    $score->coi_score  = $request->comparative['coi_score'] ?? 0;
    $score->ncoi_score = $request->comparative['ncoi_score'] ?? 0;
    $score->bei_score  = $request->comparative['bei_score'] ?? 0;

    $score->total_score = $request->comparative['total'] ?? 0;
    $score->save();

    // 🔥 AUTO UPDATE STATUS
    $application = Application::findOrFail($id);
    $application->status = 'evaluated';
    $application->last_activity_at = now();
    $application->save();

    return back()->with('success', 'Scores updated and application evaluated!');
}
    public function settings()
    {
        return view('admin.settings');
    }
}

