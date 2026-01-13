<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\School;
use App\Models\Training;

class ApplicantController extends Controller
{
    public function create()
    {
        $schools = School::orderBy('name')->get();
        $positions = [
            'Teacher I','Teacher II','Teacher III','Teacher IV',
            'Teacher V','Teacher VI','Teacher VII','Master Teacher I',
            'Master Teacher II','Master Teacher III','Master Teacher IV','Master Teacher V'
        ];

        return view('applicants.create', compact('schools','positions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position_applied' => 'required|string',
            'station_school_id' => 'nullable|exists:schools,id',
            'current_position' => 'nullable|string',
            'item_number' => 'nullable|string',
            'sg_annual_salary' => 'nullable|string',
            'levels' => 'nullable|array',
            'levels.*' => 'string',
        ]);

        $data['levels'] = $data['levels'] ?? [];
        Applicant::create($data);

        return redirect()->back()->with('success','Applicant saved (placeholder).');
    }

    // ✅ NEW METHOD FOR AJAX QS FETCH
    public function getQS(Request $request)
{
    $level = $request->get('level');
    $position = $request->get('position');

    $qs = config("qs.$level.$position");

    if ($qs) {
        return response()->json([
            'success' => true,
            'data' => $qs
        ]);
    }

    return response()->json(['success' => false]);
}

    public function storeTraining(Request $request)
{
    $request->validate([
        'training_name' => 'required|string|max:255',
        'training_date' => 'required|date',
        'training_file' => 'required|mimes:pdf|max:5120',
        'applicant_id'  => 'required|integer',
    ]);

    $applicantId = $request->applicant_id;

    $file = $request->file('training_file');

    $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->training_name);
    $fileName = $safeName . '_' . $request->training_date . '.pdf';

    $path = $file->storeAs(
        "public/applicants/{$applicantId}/training",
        $fileName
    );

    Training::create([
        'applicant_id' => $applicantId,
        'training_name' => $request->training_name,
        'training_date' => $request->training_date,
        'file_path' => $path,
    ]);

    return response()->json(['status' => 'success']);
}

}
