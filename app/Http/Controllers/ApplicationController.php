<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Models\Application;
use App\Models\School;
use App\Models\Education;
use App\Models\Training;
use App\Models\Experience;
use App\Models\Eligibility;
use App\Models\Ipcrf;
use App\Models\ApplicationPpstRating;
use App\Models\PpstIndicator;
use App\Mail\ApplicationStatusMail;

class ApplicationController extends Controller
{
    /* =====================================================
     | STORE FINAL APPLICANT (SUBMIT)
     ===================================================== */
public function store(Request $request)
{
    // =========================
    // 1️⃣ VALIDATION
    // =========================
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'position_applied' => 'required|string',
        'levels' => 'required|array',
        'school_name' => 'required|string',
    ]);

    // =========================
    // 2️⃣ DETERMINE STATUS (🔥 DYNAMIC)
    // =========================
    $status = 'draft'; // default

    if ($request->ppst_result === 'qualified') {
        $status = 'pending';
    } elseif ($request->ppst_result === 'disqualified') {
        $status = 'draft';
    }

    // =========================
    // 3️⃣ CREATE APPLICATION
    // =========================
    $application = Application::create([
        'name' => $request->name,
        'email' => $request->email,
        'current_position' => $request->current_position,
        'position_applied' => $request->position_applied,
        'item_number' => $request->item_number,
        'school_name' => $request->school_name,
        'sg_annual_salary' => $request->sg_annual_salary,
        'levels' => $request->levels,
        'status' => $status, 
        'last_activity_at' => now(),
    ]);

    $applicantId = $application->id;

    // =========================
    // 3️⃣ CREATE BASE FOLDER
    // =========================
    Storage::disk('public')->makeDirectory("applications/$applicantId");

    $getPath = function($type) use ($applicantId) {
        return "applications/{$applicantId}/{$type}";
    };

    // =========================
    // 4️⃣ TRAININGS
    // =========================
    if ($request->trainings) {
        foreach ($request->trainings as $index => $training) {

            if ($request->hasFile("trainings.$index.file")) {

                $file = $training['file'];

                $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $training['title'] ?? 'training');

                $fileName = $safeName . '_' . time() . '.pdf';

                $filePath = $file->storeAs($getPath('trainings'), $fileName, 'public');

                Training::create([
                    'application_id' => $applicantId,
                    'title' => $training['title'] ?? null,
                    'type' => $training['type'] ?? null,
                    'start_date' => $training['start_date'] ?? null,
                    'end_date' => $training['end_date'] ?? null,
                    'hours' => $training['hours'] ?? 0,
                    'file_path' => $filePath
                ]);
            }
        }
    }

    // =========================
    // 5️⃣ EDUCATION
    // =========================
    if ($request->file('education.file')) {

        $file = $request->file('education.file');

        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->education['degree'] ?? 'education');

        $fileName = $safeName . '_' . time() . '.pdf';

        $filePath = $file->storeAs($getPath('educations'), $fileName, 'public');

        Education::create([
            'application_id' => $applicantId,
            'degree' => $request->education['degree'] ?? null,
            'school' => $request->education['school'] ?? null,
            'date_graduated' => $request->education['date_graduated'] ?? null,
            'units' => $request->education['level'] ?? null,
            'file_path' => $filePath,
        ]);
    }

    // =========================
    // 6️⃣ EXPERIENCE
    // =========================
    if ($request->has('experiences')) {
        foreach ($request->experiences as $index => $exp) {

            if (empty($exp['position']) || empty($exp['start']) || empty($exp['end'])) {
                continue;
            }

            $filePath = null;

            if ($request->hasFile("experiences.$index.file")) {
                $file = $request->file("experiences.$index.file");
                $fileName = time().'_'.$index.'.'.$file->getClientOriginalExtension();
                $filePath = $file->storeAs($getPath('experience'), $fileName, 'public');
            }

            Experience::create([
                'application_id' => $applicantId,
                'school_type' => $exp['school_type'],
                'school' => $exp['school'],
                'position' => $exp['position'],
                'start_date' => $exp['start'],
                'end_date' => $exp['end'],
                'file_path' => $filePath,
            ]);
        }
    }

    // =========================
    // 7️⃣ ELIGIBILITY
    // =========================
    if ($request->eligibility_files) {

        foreach ($request->eligibility_files as $index => $eligFile) {

            if ($request->hasFile("eligibility_files.$index.file")) {

                $file = $request->file("eligibility_files.$index.file");

                $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $eligFile['eligibility'] ?? 'eligibility');

                $fileName = $safeName . '_' . time() . '.pdf';

                $filePath = $file->storeAs($getPath('eligibility'), $fileName, 'public');

                Eligibility::create([
                    'application_id' => $applicantId,
                    'eligibility_name' => $eligFile['eligibility'] ?? null,
                    'expiry_date' => $eligFile['expiry_date'] ?? null, // ✅ ADD THIS
                    'file_path' => $filePath
                ]);
            }
        }
    }

    // =========================
    // 8️⃣ IPCRF
    // =========================
   if($request->has('ipcrf_files')){
    
    foreach($request->ipcrf_files as $ipcrf){

        if(isset($ipcrf['file'])){

            $file = $ipcrf['file'];

            $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $ipcrf['title'] ?? 'ipcrf');

            $fileName = $safeName . '_' . time() . '.pdf';

            $filePath = $file->storeAs("applications/{$application->id}/ipcrf", $fileName, 'public');

            \App\Models\IpcrfFile::create([
                'application_id' => $application->id,
                'file_name' => $ipcrf['title'] ?? null,
                'file_path' => $filePath
            ]);
        }
    }
}
// =========================
    // 8️⃣ PPST_RATINGS_APPLICATION
    // =========================
if ($request->has('ppst')) {

    foreach ($request->ppst as $indicatorId => $values) {

        if (!is_array($values)) continue;

        $selectedRating = null;

        // 🔍 hanapin kung alin ang naka-check (O / VS / S)
        foreach (['O', 'VS', 'S'] as $rating) {

            if (!empty($values[$rating])) {
                $selectedRating = $rating;
                break; // 👉 isa lang dapat per indicator
            }

        }

        // ✅ SAVE ONLY IF MAY NAKA-CHECK
        if ($selectedRating) {

            \DB::table('application_ppst_ratings')->updateOrInsert(
                [
                    'application_id' => $application->id,
                    'ppst_indicator_id' => $indicatorId,
                ],
                [
                    'rating' => $selectedRating,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

        }

        // ❗ WALANG CHECK → WALANG SAVE → TREAT AS NULL

    }

}
    // =========================
    // 9️⃣ SAVE SCORES + REMARKS
    // =========================
    \DB::table('application_scores')->updateOrInsert(
        ['application_id' => $applicantId],
        [
            'education_points'   => $request->education_points,
            'education_remarks'  => $request->education_remarks,
            'training_points'    => $request->training_points,
            'training_remarks'   => $request->training_remarks,
            'experience_points'  => $request->experience_points,
            'experience_remarks' => $request->experience_remarks,
            'eligibility_remarks' => $request->eligibility_remarks,
            'performance_points' => $request->performance_points,

            'coi_outstanding' => $request->coi_outstanding,
            'coi_very_satisfactory' => $request->coi_very_satisfactory,
            'ncoi_outstanding' => $request->ncoi_outstanding,
            'ncoi_very_satisfactory' => $request->ncoi_very_satisfactory,
            'final_result' => $request->ppst_result,

            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    try {

       Mail::to($application->email)
    ->send(new ApplicationStatusMail($application, $request->ppst_result));

    } catch (\Exception $e) {

        \Log::error('Mail Error: ' . $e->getMessage());

    }
    return redirect()->back()->with('success', 'Application submitted successfully.');
}
    /* =====================================================
     | EMAIL: UNQUALIFIED
     ===================================================== */
    public function notifyUnqualified(Request $request)
    {
        $request->validate([
            'email'   => 'required|email',
            'remarks' => 'required|array'
        ]);

        try {
            Mail::send([], [], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Application Status Notification')
                    ->setBody(
                        "Dear Applicant,<br><br>
                        After evaluating your qualifications, some requirements were not met.<br><br>
                        <strong>Remarks:</strong><br>" . implode('<br>', $request->remarks) . "<br><br>
                        You may review your application and reapply if applicable.<br><br>
                        Best regards,<br>HR Department",
                        'text/html'
                    );
            });

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Email failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Email failed to send. SMTP error: ' . $e->getMessage()
            ]);
        }
    }

    /* =====================================================
     | CREATE FORM
     ===================================================== */
  public function create(Request $request)
{
    $schools = School::orderBy('name')->get();

    $positions = [
        'Teacher I','Teacher II','Teacher III','Teacher IV',
        'Teacher V','Teacher VI','Teacher VII',
        'Master Teacher I','Master Teacher II',
        'Master Teacher III','Master Teacher IV','Master Teacher V'
    ];

    $level    = $request->get('level', 'elementary');
    $position = $request->get('position', 'Teacher I');

    $requiredHours = $this->requiredTrainingHours($level, $position);

    $qs = config('qs');
    $qsUnits = [];

    foreach ($qs as $levelKey => $positionsData) {
        foreach ($positionsData as $posTitle => $info) {

            if (isset($info['education'])) {

                if (preg_match('/(\d+)\s+professional\s+units/i', $info['education'], $m)) {
                    $qsUnits[strtolower($levelKey)][strtolower($posTitle)] = (int) $m[1];
                } else {
                    $qsUnits[strtolower($levelKey)][strtolower($posTitle)] = 0;
                }

            }

        }
    }

    $levelPPST = $this->mapPositionToDbLevel($position); // <- TAMA ito

    $ppstIndicators = collect(); // empty muna

    return view('applicants.create', compact(
        'schools',
        'positions',
        'level',
        'position',
        'requiredHours',
        'qsUnits',
        'ppstIndicators'
    ));
}

private function mapPositionToDbLevel($position)
{
    $map = [

        'Teacher I'   => 'Teacher I – MT I',
        'Teacher II'  => 'Teacher I – MT I',
        'Teacher III' => 'Teacher I – MT I',
        'Teacher IV'  => 'Teacher I – MT I',
        'Teacher V'   => 'Teacher I – MT I',
        'Teacher VI'  => 'Teacher I – MT I',
        'Teacher VII' => 'Teacher I – MT I',
        'Master Teacher I' => 'Teacher I – MT I',

        'Master Teacher II'  => 'Master Teacher II–III',
        'Master Teacher III' => 'Master Teacher II–III',
        'Master Teacher IV'  => 'Master Teacher II–III',
        'Master Teacher V'   => 'Master Teacher II–III',
    ];

    return $map[$position] ?? 'Teacher I – MT I';
}

public function loadPPST(Request $request)
{
    $position = $request->position;

    $level = $this->mapPositionToDbLevel($position);

    $ppstIndicators = PpstIndicator::where('position_level', $level)
        ->orderBy('order')
        ->get();

    return view('applicants.ppst-table', compact('ppstIndicators'));
}
    /* =====================================================
     | AJAX: FETCH QS
     ===================================================== */
    public function getQS(Request $request)
    {
        $level    = strtolower($request->get('level'));
        $position = $request->get('position');

        $qsConfig = config('qs');

        if (isset($qsConfig[$level][$position])) {
            $data = $qsConfig[$level][$position];
            return response()->json([
                'success' => true,
                'data' => [
                    'education'        => $data['education'] ?? null,
                    'training'         => $data['training'] ?? null,
                    'training_hours'   => $data['training_hours'] ?? 0,
                    'experience'       => $data['experience'] ?? null,
                    'experience_years' => $data['experience_years'] ?? 0,
                    'eligibility'      => $data['eligibility'] ?? null,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No QS found for this position and level'
        ]);
    }

    /* =====================================================
     | EXPERIENCE REQUIREMENT
     ===================================================== */
    public function experienceRequirement(Request $request)
    {
        $request->validate([
            'level'    => 'required|string',
            'position' => 'required|string',
        ]);

        $qs = config('qs');

        if (!isset($qs[$request->level][$request->position])) {
            return response()->json(['label' => '—', 'years' => 0]);
        }

        $data = $qs[$request->level][$request->position];

        return response()->json([
            'label' => $data['experience'],
            'years' => $data['experience_years'] ?? 0
        ]);
    }

    /* =====================================================
     | HELPER: TRAINING HOURS
     ===================================================== */
    private function requiredTrainingHours($level, $position)
    {
        $qs = config('qs');
        $level = strtolower($level);

        return $qs[$level][$position]['training_hours'] ?? 0;
    }

}