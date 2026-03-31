<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reclassification - Applicant Form</title>

      <!-- Favicon - DO LOGO -->
    <link rel="icon" type="image/png" href="{{ asset('images/DO-LOGO.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/DO-LOGO.png') }}">
    
    <!-- Alternative sizes -->
    <link rel="apple-touch-icon" href="{{ asset('images/DO-LOGO.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/template.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}" media="print">
</head>
<body>
  
  
<div class="container">

    <!-- Top reference + automatic "For ..." -->
    <div class="doc-top">
        <div class="left">DBM-DepEd JC 01, s.2025_Form No. 2-A</div>
        <!-- This will update automatically based on selected position + level -->
        <div class="right" id="forPosition">For —</div>
    </div>
<br>
    <div class="header">
        <!-- Put your logo in public/images/depEd-logo.png or change the src -->
       <img src="{{ asset('images/depEd-logo.png') }}" alt="DepEd Logo" style="height: 80px;">

        <div class="dep-rc-title">Republika ng Pilipinas</div>
        <div class="dep-sub">Department of Education</div>
<br>
        <h5 class="fw-bold">RECLASSIFICATION FORM FOR TEACHING POSITIONS (RFTP)</h5>
    </div>

    <div class="card form-card p-4">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        

            @csrf
            <div id="educationHiddenInputs"></div>
            <div id="eligibilityHiddenContainer" class="d-none"></div>
            <div id="ipcrfContainerInputs"></div>

            <input type="hidden" name="education_points" id="education_points">
            <input type="hidden" name="education_remarks" id="remarksEducation">

            <input type="hidden" name="training_points" id="training_points">
            <input type="hidden" name="training_remarks" id="remarksTraining">

            <input type="hidden" name="experience_points" id="experience_points">
            <input type="hidden" name="experience_remarks" id="remarksExperience">

            <input type="hidden" name="performance_points" id="performance_points">

            <input type="hidden" name="education[degree]" id="input_education_degree">
            <input type="hidden" name="education[school]" id="input_education_school">
            <input type="hidden" name="education[date_graduated]" id="input_education_date">
            <input type="hidden" name="education[units]" id="input_education_units">
            
            @include('modals.education')
            @include('modals.training')
            @include('modals.experience')
            @include('modals.eligibility')
            @include('modals.ipcrf')
            @include('modals.performance')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Name:</label>
                    <input name="name" id="name" class="form-control" placeholder="Ex.Juan D. Cruz" value="{{ $application->name }}">
                </div>

             <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Current Position:</label>
                <select id="current_position" name="current_position" class="form-select">
                <option value="">-- Select Current Position --</option>

                @foreach($positions as $p)
                <option value="{{ $p }}" {{ $application->current_position == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach

                </select>
                </div>
            
                <!-- <div class="col-md-2 mb-3">
                  <label class="form-label fw-bold">Step:</label>
                  <select id="step" class="form-select">

                  <option value="">Step</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>

                  </select>
                  </div>

                  </div> -->
            @php
                $groupedSchools = $schools->groupBy('level_type');
            @endphp
          @php
$levels = [];

if(isset($application) && $application->levels){
    $levels = is_array($application->levels)
        ? $application->levels
        : json_decode($application->levels, true);
}
@endphp


<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Position Applied:</label>
        <select id="position_applied" name="position_applied" class="form-select">
            <option value="">-- Select Position Applied --</option>

            @foreach($positions as $p)
                <option value="{{ $p }}" {{ $application->position_applied == $p ? 'selected' : '' }}>
                    {{ $p }}
                </option>
            @endforeach
        </select>
    </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Item No. of Current Position:</label>
                    <input type="text" name="item_number" class="form-control" value="{{ $application->item_number }}">
                </div>
            </div>

         <div class="row">
    {{-- ========================= --}}
    {{-- SCHOOL --}}
    {{-- ========================= --}}
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Station / School:</label>
        <select id="school_id" name="school_name" class="form-select">
            <option value="">-- Select School --</option>

            {{-- Kindergarten --}}
            @if(isset($groupedSchools['kindergarten']))
                <optgroup label="Kindergarten Schools">
                    @foreach($groupedSchools['kindergarten'] as $school)
                        <option value="{{ $school->name }}" data-level="kindergarten"
                            {{ $application->school_name == $school->name ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </optgroup>
            @endif

            {{-- Elementary --}}
            @if(isset($groupedSchools['elementary']))
                <optgroup label="Elementary Schools">
                    @foreach($groupedSchools['elementary'] as $school)
                        <option value="{{ $school->name }}" data-level="elementary"
                            {{ $application->school_name == $school->name ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </optgroup>
            @endif

            {{-- Junior High --}}
            @if(isset($groupedSchools['junior_high']))
                <optgroup label="Junior High Schools">
                    @foreach($groupedSchools['junior_high'] as $school)
                        <option value="{{ $school->name }}" data-level="junior_high"
                            {{ $application->school_name == $school->name ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </optgroup>
            @endif

            {{-- Senior High --}}
            @if(isset($groupedSchools['senior_high']))
                <optgroup label="Senior High Schools">
                    @foreach($groupedSchools['senior_high'] as $school)
                        <option value="{{ $school->name }}" data-level="senior_high"
                            {{ $application->school_name == $school->name ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </optgroup>
            @endif

        </select>
    </div>

                <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">SG/Annual Salary:</label>
               <input 
                type="text"
                id="salary"
                value="{{ $application->sg_annual_salary }}"
                name="sg_annual_salary"
                class="form-control"
                readonly
                placeholder="Auto computed from position and step">
                </div>
            </div>


<div class="mb-3">
  <label class="form-label fw-bold">Level:</label>

  <div class="row ps-5">

    <div class="col-md-6">
      <div class="form-check">
        <input class="form-check-input"
               type="checkbox"
               name="levels[]"
               value="kindergarten"
               id="levelKindergarten"
               {{ in_array('kindergarten', $levels) ? 'checked' : '' }}>
        <label class="form-check-label" for="levelKindergarten">
          Kindergarten
        </label>
      </div>

      <div class="form-check">
        <input class="form-check-input"
               type="checkbox"
               name="levels[]"
               value="elementary"
               id="levelElementary"
               {{ in_array('elementary', $levels) ? 'checked' : '' }}>
        <label class="form-check-label" for="levelElementary">
          Elementary
        </label>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-check">
        <input class="form-check-input"
               type="checkbox"
               name="levels[]"
               value="junior_high"
               id="levelJuniorHigh"
               {{ in_array('junior_high', $levels) ? 'checked' : '' }}>
        <label class="form-check-label" for="levelJuniorHigh">
          Junior High School
        </label>
      </div>

      <div class="form-check">
        <input class="form-check-input"
               type="checkbox"
               name="levels[]"
               value="senior_high"
               id="levelSeniorHigh"
               {{ in_array('senior_high', $levels) ? 'checked' : '' }}>
        <label class="form-check-label" for="levelSeniorHigh">
          Senior High School
        </label>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="isEditMode" value="{{ isset($application) ? 1 : 0 }}">

        <!-- QS TABLE -->
    <hr class="mt-2">
    <h5 class="text-left fw-bold mt-3">I. QUALIFICATION STANDARDS</h5>
    <table class="table table-bordered mt-3 text-center align-middle">
        <thead>
            <tr>
                <th>Elements</th>
                <th>QS of the Position</th>
                <th>QS of the Applicant</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
        <tr id="education-row">
        <td>Education</td>
        <td id="qs_education">—</td>
        <td><button type="button" class="btn btn-sm btn-primary add-education-btn">➕ Add Education</button>
            <div id="education_summary" class="mt-2 small text-muted">
        @if($application->educations && $application->educations->count())
            @foreach($application->educations as $edu)
                <div class="mb-1">
                    <strong>{{ $edu->degree }}</strong><br>
                    {{ $edu->school }}<br>
                    <small>{{ $edu->date_graduated }} | Units: {{ $edu->units ?? '-' }}</small>
                </div>
            @endforeach
        @else
            No Education Added.
        @endif
    </div>
        </td>
        <td id="education_remark"><span class="text-muted">Waiting for the QS</span></td>
    </tr>
        <tr>
        <td>Training</td>

        <td id="qs_training">—</td>

        <td>
            <button type="button" class="btn btn-sm btn-primary add-training-btn">
                ➕ Add Training
            </button>

            <div id="training_summary" class="mt-2 small text-muted">
            @if($application->trainings && $application->trainings->count())
        @foreach($application->trainings as $t)
            <div class="mb-1">
                <strong>{{ $t->title ?? 'Training' }}</strong><br>
                {{ $t->type ?? '-' }}<br>
                <small>
                    {{ $t->hours ?? 0 }} hrs |
                    {{ $t->start_date ?? '' }} - {{ $t->end_date ?? '' }}
                </small>
            </div>
        @endforeach
    @else
        No Training Added.
    @endif
            </div>
        </td>

        <td id="training_remark">
            <span class="text-muted">Waiting for the QS</span>
        </td>
    </tr>
        <tr>
        <td>Experience</td>
        <td id="qs_experience">—</td>
        <td><button type="button"class="btn btn-sm btn-primary add-experience-btn">➕ Add Experience</button>
            <div id="experience_summary" class="mt-2 small text-muted">
    @if($application->experiences && $application->experiences->count())
        @foreach($application->experiences as $exp)
            <div class="mb-1">
                <strong>{{ $exp->position }}</strong><br>
                {{ $exp->school }} ({{ $exp->school_type }})<br>
                <small>
                    {{ $exp->start_date }} - {{ $exp->end_date ?? 'Present' }}
                </small>
            </div>
        @endforeach
    @else
        No experience added.
    @endif
</div>
        </td>
        <td id="experience_remark">
            <span class="text-muted">Waiting for the QS</span>
        </td>
    </tr>
    <tr>
        <td>Eligibility</td>

        <td id="qs_eligibility">—</td>

        <td>
            <button type="button" class="btn btn-sm btn-primary add-eligibility-btn">
                ➕ Add Eligibility
            </button>

           <div id="eligibility_summary" class="mt-2 small text-muted">
    @if($application->eligibilities && $application->eligibilities->count())
        @foreach($application->eligibilities as $el)
            <div class="mb-1">
                <strong>{{ $el->eligibility_name ?? 'Eligibility' }}</strong><br>
                <small>
                    Expiry: {{ $el->expiry_date ?? '-' }}
                </small>
            </div>
        @endforeach
    @else
        No Eligibility Added.
    @endif
</div>
        </td>

        <td id="eligibility_remark">
            <span class="text-muted">Waiting for the QS</span>
        </td>
    </tr>
        </tbody>
    </table>

   <div class="d-flex justify-content-between align-items-center mb-3">
  <p class="text-muted fst-italic mb-0">
    Note: Indicate the QS of the Position Applied for based on the CSC-Approved QS
  </p>
</div>

    <!-- PERFORMANCE REQUIREMENTS -->
     <div id="performanceRequirements">
    <h5 class="text-left fw-bold mt-4">II. PERFORMANCE REQUIREMENTS</h5>
    <div class="d-flex justify-content-between align-items-center mt-2">
  <p class="mb-0">
    1. Copy of duly approved IPCRF for the school year immediately preceeding the application.
    <span id="ipcrfStatus" class="ms-2 d-none text-success fw-semibold">
      <i class="bi bi-check-circle-fill me-1"></i> Uploaded
    </span>
  </p>

  <button type="button" class="btn btn-sm btn-outline-primary"
      data-bs-toggle="modal" data-bs-target="#ipcrfModal">
      View/Upload IPCRF
  </button>
</div>
    <p>2. The applicant must meet the following performance requirements depending on the position applied for.</p>

    <table class="table table-bordered mt-3 text-center align-middle" id="performanceTable">
        <thead>
            <tr>
                <th>Position Applied</th>
                <th>Performance Requirements</th>
            </tr>
        </thead>
        <tbody>
            <tr data-position="Teacher II">
                <td>Teacher II</td>
                <td>
                    At least 6 Proficient COIs at Very Satisfactory; and<br>
                    At least 4 Proficient NCOIs at Very Satisfactory
                </td>
            </tr>
            <tr data-position="Teacher III">
                <td>Teacher III</td>
                <td>
                    At least 12 Proficient COIs at Very Satisfactory; and<br>
                    At least 8 Proficient NCOIs at Very Satisfactory
                </td>
            </tr>
            <tr data-position="Teacher IV">
                <td>Teacher IV</td>
                <td>
                    21 Proficient COIs at Very Satisfactory; and<br>
                    16 Proficient NCOIs at Very Satisfactory
                </td>
            </tr>
            <tr data-position="Teacher V">
                <td>Teacher V</td>
                <td>
                    At least 6 Proficient COIs at Outstanding; and<br>
                    At least 4 Proficient NCOIs at Outstanding
                </td>
            </tr>
            <tr data-position="Teacher VI">
                <td>Teacher VI</td>
                <td>
                    At least 12 Proficient COIs at Outstanding; and At least 4 Proficient NCOIs 
                    at Very Satisfactory and 4 Proficient NCOIs at Outstanding
                </td>
            </tr>
            <tr data-position="Teacher VII">
                <td>Teacher VII</td>
                <td>
                    At least 18 Proficient COIs at Outstanding; and At least 6 Proficient NCOIs 
                    at Very Satisfactory and 6 Proficient NCOIs at Outstanding
                </td>
            </tr>
            <tr data-position="Master Teacher I">
                <td>Master Teacher I</td>
                <td>
                    21 Proficient COIs at Outstanding; and 8 Proficient NCOIs at Very Satisfactory 
                    and 8 Proficient NCOIs at Outstanding
                </td>
            </tr>
             <tr data-position="Master Teacher II">
                <td>Master Teacher II</td>
                <td>
                    At least 10 Highly Proficient COIs at Outstanding; and At least 5 Highly Proficient NCOIs at Very Satisfactory 
                    and 5 Highly Proficient NCOIs at Outstanding
                </td>
            </tr>
            <tr data-position="Master Teacher III">
                <td>Master Teacher III</td>
                <td>
                    21 Highly Proficient COIs at Outstanding; and At least 8 Highly Proficient NCOIs at Very Satisfactory 
                    and 8 Highly Proficient NCOIs at Outstanding
                </td>
            </tr>
        </tbody>
    </table>

 <div id="ppst-container">
    @include('admin.ppst-table')
</div>


{{-- ========================================================= --}}
{{-- III. COMPARATIVE ASSESSMENT RESULT --}}
{{-- ========================================================= --}}
<form action="{{ route('admin.scores.update', $application->id) }}" method="POST">
    @csrf
    @method('PUT')
<div id="hr-section">
<hr class="mt-5 mb-4">
<h5 class="fw-bold text-uppercase">III. Comparative Assessment Result</h5>

<div class="table-responsive mb-4">
  <table class="table table-bordered align-middle text-center">
    <thead class="table-light">
      <tr>
        <th>Education</th>
        <th>Training</th>
        <th>Experience</th>
        <th>Performance</th>
        <th>Classroom Observable Indicators</th>
        <th>Non-Classroom Observable Indicators</th>
        <th>Behavioral Events Interview (BEI)</th>
        <th>Total Score</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><input type="number" name="comparative[education]" class="form-control form-control-sm text-center"value="{{ $application->scores->education_points ?? 0 }}"></td>
        <td><input type="number" name="comparative[training]" class="form-control form-control-sm text-center"value="{{ $application->scores->training_points ?? 0 }}"></td>
        <td><input type="number" name="comparative[experience_points]" class="form-control form-control-sm text-center"value="{{ $application->scores->experience_points ?? 0 }}"></td>
        <td><input type="number" name="comparative[performance]" id="performanceFinal" class="form-control form-control-sm text-center" value="{{ $application->scores->performance_points ?? 0 }}">
        <button type="button" class="btn btn-sm btn-outline-success"data-bs-toggle="modal" data-bs-target="#performanceModal">Compute</button></td>
        <td><input type="number" name="comparative[coi_score]" class="form-control form-control-sm text-center" value="0"></td>
        <td><input type="number" name="comparative[ncoi_score]" class="form-control form-control-sm text-center" value="0"></td>
        <td><input type="number" name="comparative[bei_score]" class="form-control form-control-sm text-center" value="0"></td>
        <td><input type="number" name="comparative[total]" class="form-control form-control-sm text-center"></td>
      </tr>
    </tbody>
  </table>
</div>


<div class="row text-center mb-5 mt-5">
    <div class="col-md-6">
        <p class="fw-semibold mb-1">Conforme:</p>
        <br><br>
        <p id="teacherApplicant" class="fw-bold text-decoration-underline mb-0">{{ old('name') }}</p>
        <p class="small mb-0">Teacher Applicant</p>
    </div>

    <div class="col-md-6">
        <p class="fw-semibold mb-1">Attested by:</p>
        <br><br>
        <p class="fw-bold text-decoration-underline">ERNEST JOSEPH C. CABRERA</p>
        <p class="small mb-0">HRMPSB Chair</p>
    </div>
</div>

{{-- ========================================================= --}}
{{-- IV. DEPED SCHOOLS DIVISION OFFICE ACTION --}}
{{-- ========================================================= --}}
<h5 class="fw-bold text-uppercase">IV. DepEd Schools Division Office Action</h5>

<div class="table-responsive mb-4">
  <table class="table table-bordered align-middle text-center">
    <thead class="table-light">
      <tr>
        <th>Reclassification of Position</th>
        <th>From</th>
        <th>Salary Grade</th>
        <th>To</th>
        <th>Salary Grade</th>
        <th>Date Processed</th>
        <th>Remarks</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Reclassification of Position</td>
        <td><input type="text" id="from_position" name="division[from_position]" class="form-control form-control-sm text-center"></td>
        <td><input type="text" id="from_grade" name="division[from_grade]" class="form-control form-control-sm text-center"></td>
        <td><input type="text" id="to_position" name="division[to_position]" class="form-control form-control-sm text-center"></td>
        <td><input type="text" id="to_grade" name="division[to_grade]" class="form-control form-control-sm text-center"></td>
        <td><input type="date" name="division[date_processed]" class="form-control form-control-sm text-center"></td>
        <td><input type="text" name="division[remarks]" class="form-control form-control-sm text-center"></td>
      </tr>
    </tbody>
  </table>
</div>

<!-- ROW 1 : Evaluated (Right) -->
<div class="row mb-4">
  <div class="col-md-6"></div>
  <div class="col-md-6 text-center">
    <p class="fw-semibold mb-1">Evaluated by:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1">MA. CLARINDA L. OMO</p>
    <p class="small mb-0">Administrative Officer IV (HRMO)</p>
  </div>
</div>

<!-- ROW 2 : Certified (Left) -->
<div class="row mb-4">
  <div class="col-md-6 text-center">
    <p class="fw-semibold mb-1">Certified Correct:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1">MARK ANGELO S. ENRIQUEZ, JD</p>
    <p class="small mb-0">Administrative Officer V (Admin Services)</p>
  </div>
  <div class="col-md-6"></div>
</div>

<!-- ROW 3 : Recommending (Center) -->
<div class="row text-center mb-4">
  <div class="col-md-12">
    <p class="fw-semibold mb-1">Recommending Approval:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1">NOEL D. BAGANO</p>
    <p class="small mb-0">Schools Division Superintendent</p>
  </div>
</div>



{{-- ========================================================= --}}
{{-- V. DEPED REGIONAL OFFICE ACTION --}}
{{-- ========================================================= --}}
<h5 class="fw-bold text-uppercase">V. DepEd Regional Office Action</h5>

<div class="table-responsive mb-4">
  <table class="table table-bordered align-middle text-center">
    <thead class="table-light">
      <tr>
        <th>Reclassification of Position</th>
        <th>From</th>
        <th>Salary Grade</th>
        <th>To</th>
        <th>Salary Grade</th>
        <th>Date Processed</th>
        <th>Remarks</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Reclassification of Position</td>
        <td><input type="text" name="regional[from_position]" class="form-control form-control-sm"></td>
        <td><input type="text" name="regional[from_grade]" class="form-control form-control-sm"></td>
        <td><input type="text" name="regional[to_position]" class="form-control form-control-sm"></td>
        <td><input type="text" name="regional[to_grade]" class="form-control form-control-sm"></td>
        <td><input type="date" name="regional[date_processed]" class="form-control form-control-sm"></td>
        <td><input type="text" name="regional[remarks]" class="form-control form-control-sm"></td>
      </tr>
    </tbody>
  </table>
</div>

<!-- ROW 1 : Evaluated (Right) -->
<div class="row mb-4">
  <div class="col-md-6"></div>
  <div class="col-md-6 text-center">
    <p class="fw-semibold mb-1">Evaluated by:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1"></p>
    <p class="small mb-0">Teachers Credential Evaluator</p>
  </div>
</div>

<!-- ROW 2 : Certified (Left) -->
<div class="row mb-4">
  <div class="col-md-6 text-center">
    <p class="fw-semibold mb-1">Certified Correct:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1">Atty. JOYLYN P. DUNLUAN</p>
    <p class="small mb-0">Chief, Administrative Division</p>
  </div>
  <div class="col-md-6"></div>
</div>

<!-- ROW 3 : Approved (Center) -->
<div class="row text-center mb-4">
  <div class="col-md-12">
    <p class="fw-semibold mb-1">Approved:</p>
    <br>
    <p class="fw-bold text-decoration-underline mb-1">JOCELYN DR. ANDAYA</p>
    <p class="small mb-0">Regional Director, NCR</p>
    <p class="small mb-0">Concurrent Officer-In-Charge, Office of the Assistant Secretary for Operations</p>
  </div>
</div>
<div class="text-center my-4">
    <button type="submit" class="btn btn-warning btn-lg px-5">
                    Update Applications
                </button>
</div>
        </form>
    </div>
   
</div>
</div>
</div> <!-- /.container -->

<div id="qsLoadingOverlay">
    <div class="qs-loader-wrapper">
        <img src="{{ asset('images/DO-LOGO.png') }}" class="loader-logo">
        <div class="qs-loading-text">
            Please wait while we load your Qualification Standards
        </div>
    </div>
</div>

<!-- Submit Loader -->
<div id="submitLoadingOverlay">
    <div class="qs-loader-wrapper text-center">
        <div class="logo-aura">
            <img src="{{ asset('images/DO-LOGO.png') }}" class="loader-logo mb-3">
        </div>

        <div id="submitLottie" style="width:150px; height:150px; margin:auto;"></div>

        <div class="qs-loading-text">
            Submitting your application... Please wait.
        </div>
    </div>
</div>

<div id="evalLoadingOverlay">
    <div class="qs-loader-wrapper">
        <div class="logo-aura">
            <img src="{{ asset('images/DO-LOGO.png') }}" class="loader-logo">
        </div>
        <div class="qs-loading-text">
            Evaluating Qualifications<br>
            Please wait while we check your records...
        </div>
    </div>
</div>
  
  <!-- SCRIPTS - UPDATED VERSION -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.10.2/lottie.min.js"></script>
    
  <!-- GLOBAL VARIABLES -->
  <script>
      // ITO ANG IMPORTANTE: Check kung tama ang structure
      window.qsConfig = @json(config('qs') ?? []);
      
      // Debug logging
      console.log('=== QS CONFIG DEBUG ===');
      console.log('Full config:', window.qsConfig);
      console.log('Selected Level:', '{{ $selectedLevel ?? "kindergarten" }}');
      console.log('Selected Position:', '{{ $selectedPosition ?? "Teacher III" }}');
      console.log('=== END DEBUG ===');
      
      window.requiredTrainingHours = {{ $requiredTrainingHours ?? 0 }};
      window.requiredTrainingLevel = {{ $requiredTrainingLevel ?? 0 }};
      // Other globals
      window.requiredExperienceYears = {{ $requiredYears ?? 0 }};
      window.qsEducationUnits = @json($qsUnits ?? []);
  </script>
  <script>
    window.savedLevels = @json($levels);
</script>

<script>
    window.adminData = {
        educations: @json($application->educations ?? collect()->values()),
        trainings: @json($application->trainings ?? collect()->values()),
        experiences: @json($application->experiences ?? collect()->values()),
        eligibilities: @json($application->eligibilities ?? collect()->values()),
        scores: @json($application->scores ?? null)
    };
</script>
  <script src="{{ asset('js/admin-load.js') }}"></script>

  <!-- LOAD JS FILES -->
  <script src="{{ asset('js/ipcrf.js') }}"></script>
  <script src="{{ asset('js/auto-check-qs.js') }}"></script>
  <script src="{{ asset('js/experience.js') }}"></script>
  <script src="{{ asset('js/education-points.js') }}"></script>
  <script src="{{ asset('js/training.js') }}"></script>
  <script src="{{ asset('js/eligibility.js') }}"></script>
  <script src="{{ asset('js/dataprivacy.js') }}"></script>
  <script src="{{ asset('js/indicators.js') }}"></script>
  <script src="{{ asset('js/fillout.js') }}"></script>
  <script src="{{ asset('js/performancerating.js') }}"></script>
  <script src="{{ asset('js/form-submit.js') }}"></script>

  <script src="{{ asset('js/mapping-sg.js') }}"></script>
  <script src="{{ asset('js/position-ranking.js') }}"></script>
  <script src="{{ asset('js/position-change.js') }}"></script>
  
  <script>
  function tryAutoEvaluate() {
      const remarks = [
          $('#education_remark').text(),
          $('#training_remark').text(),
          $('#experience_remark').text(),
          $('#eligibility_remark').text()
      ];

      if (remarks.every(r => r.includes('MET') || r.includes('NOT MET'))) {
          autoCheckQS();
      }
  }

  // call after each save
  $('#saveEducation, #saveTraining, #saveExperienceBtn, #saveEligibilityBtn')
      .on('click', () => setTimeout(tryAutoEvaluate, 500));
  </script>

  <script>
    $(document).ready(function() {

    // === SUCCESS / ERROR ALERTS (SERVER FEEDBACK) ===
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Saved Successfully!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6'
      });
    @endif

    @if ($errors->any())
      Swal.fire({
        icon: 'warning',
        title: 'Incomplete Form!',
        html: `
          <ul style="text-align:left;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        `,
        confirmButtonColor: '#d33'
      });
    @endif
  });
  </script>
  <script>
  /* ================================
    UTILITY FUNCTIONS
  ================================ */

  /* Return level display text */
  function getLevelDisplay(levelKey) {
      if (!levelKey) return '';
      if (levelKey === 'elementary' || levelKey === 'kindergarten') return 'Elementary';
      if (levelKey === 'junior_high') return 'Junior High';
      if (levelKey === 'senior_high') return 'Senior High';
      return '';
  }

  /* Determine selected level (FROM SCHOOL ONLY) */
  function getSelectedLevel() {
      let schoolLevel = $('#school_id').find(':selected').data('level');
      return schoolLevel ? schoolLevel : '';
  }

  /* Update top-right header */
  function updateHeaderForPosition() {
      let pos = $('#position_applied').val() ? $('#position_applied').val().trim() : '';
      let levelKey = getSelectedLevel();
      let levelText = getLevelDisplay(levelKey);

      if (pos) {
          $('#forPosition').text(
              levelText ? `For ${pos} (${levelText})` : `For ${pos}`
          );
      } else {
          $('#forPosition').text('For —');
      }
  }
  /* ================================
    QS LOADER (MANUAL ONLY)
  ================================ */
  function loadQS(position) {
      let level = getSelectedLevel();

      if (!position) {
          $('#qs_education, #qs_training, #qs_experience, #qs_eligibility').text('—');
          return;
      }

      showQSLoading(); // show loader

      $.ajax({
          url: '{{ route("get.qs") }}',
          type: 'GET',
          data: { position: position, level: level },
          success: function(response) {
              if (response.success) {
                  $('#qs_education').text(response.data.education);
                  $('#qs_training').text(response.data.training);
                  $('#qs_experience').text(response.data.experience);
                  $('#qs_eligibility').text(response.data.eligibility);
              } else {
                  $('#qs_education, #qs_training, #qs_experience, #qs_eligibility').text('—');
              }
          },
          error: function() {
              $('#qs_education, #qs_training, #qs_experience, #qs_eligibility').text('—');
          },
          complete: function() {
              hideQSLoading(5000); // 5000ms = 5 seconds delay
             
              if (window.savedLevels) {
        restoreLevels(window.savedLevels);
    }
          }
      });
  }
  </script>

  <script>
  document.addEventListener('DOMContentLoaded', function () {

      let formChanged = false;

      const form = document.querySelector('form'); // or #createForm

      if (!form) return;

      // Detect ANY input change
      form.querySelectorAll('input, textarea, select').forEach(el => {
          el.addEventListener('change', () => formChanged = true);
          el.addEventListener('input', () => formChanged = true);
      });

      // Browser warning on refresh / close / back
      window.addEventListener('beforeunload', function (e) {
          if (!formChanged) return;

          e.preventDefault();
          e.returnValue = ''; // REQUIRED
      });

      // OPTIONAL: remove warning on successful submit
      form.addEventListener('submit', function () {
          formChanged = false;
      });

  });
  </script>
  <!-- JS for live update -->
  <script>
      const nameInput = document.getElementById('name');
      const teacherApplicant = document.getElementById('teacherApplicant');

      nameInput.addEventListener('input', function() {
          teacherApplicant.textContent = this.value;
      });
  </script>
  <script>
  $(document).ready(function() {
      // Auto-initialize experience if QS data is available
      if (window.requiredExperienceYears > 0) {
          if (typeof window.experienceModule !== 'undefined') {
              window.experienceModule.initializeExperienceFromQS(window.requiredExperienceYears);
          }
      }
  });
  </script>
 <script>
document.addEventListener("DOMContentLoaded", function() {

    // 🔹 QS Loader Lottie
    if(document.getElementById('qsLottie')){
        lottie.loadAnimation({
            container: document.getElementById('qsLottie'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets9.lottiefiles.com/packages/lf20_usmfx6bp.json'
        });
    }

    // 🔹 Submit Loader Lottie (optional, safe lang kahit di gamitin)
    if(document.getElementById('submitLottie')){
        lottie.loadAnimation({
            container: document.getElementById('submitLottie'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets9.lottiefiles.com/packages/lf20_usmfx6bp.json'
        });
    }

});

// =========================
// QS LOADER (REAL)
// =========================
function showQSLoading() {
    $('#qsLoadingOverlay').addClass('active');
}

function hideQSLoading() {
    $('#qsLoadingOverlay').removeClass('active');
}


// =========================
// SUBMIT LOADER (USE SAME OVERLAY)
// =========================
function showSubmitLoading() {
    $('#qsLoadingOverlay').addClass('active'); // 🔥 reuse QS overlay
}

function hideSubmitLoading() {
    $('#qsLoadingOverlay').removeClass('active'); // 🔥 instant hide
}
</script>
  <script>
  $(document).ready(function(){

      function loadPPST(position){

          if(!position){
              $('#ppst-container').html(
                  '<div class="text-muted text-center p-4">Select Position to load PPST</div>'
              );
              return;
          }

          $.ajax({
              url: "/load-ppst",
              type: "GET",
              data: { position: position },
              success: function(response){

                  $('#ppst-container').html(response);

              },
              error: function(){

                  $('#ppst-container').html(
                      '<div class="text-danger text-center p-4">Failed to load PPST</div>'
                  );

              }
          });

      }

      // auto load kapag may selected position
      let initialPosition = $('#position_applied').val();

      if(initialPosition){
          loadPPST(initialPosition);
      }

      // kapag nag change ang dropdown
      $('#position_applied').on('change', function(){

          let position = $(this).val();

          loadPPST(position);

      });

  });
  </script>
  <script>
 document.getElementById("saveEducation").addEventListener("click", function () {

    let degree = document.getElementById("education_name").value;
    let school = document.getElementById("education_school").value;
    let date = document.getElementById("education_date").value;
    let units = document.getElementById("education_units_select").value;
    let fileInput = document.getElementById("education_file");

    if (!degree || !school || !date || !fileInput.files.length) {
    Swal.fire({
        icon: 'warning',
        title: 'Complete education fields',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    });
    return;
}

    let container = document.getElementById("educationHiddenInputs");

    container.insertAdjacentHTML('beforeend', `
    <input type="hidden" name="educations[${index}][degree]" value="${degree}">
    <input type="hidden" name="educations[${index}][school]" value="${school}">
    <input type="hidden" name="educations[${index}][date_graduated]" value="${date}">
    <input type="hidden" name="educations[${index}][units]" value="${units}">
`);

    // clone file input para hindi mawala sa modal
    let clone = fileInput.cloneNode(true);
    clone.name = "education[file]";
    clone.files = fileInput.files;

    container.appendChild(clone);

    // alert("Education saved!");

});
</script>
<script>
    function restoreLevels(levels) {
    $('input[name="levels[]"]').prop('checked', false);

    levels.forEach(level => {
        $('input[value="'+level+'"]').prop('checked', true);
    });
}
</script>
<script>
    $(document).ready(function() {
    // Function para i-compute ang total
    function calculateTotalScore() {
    let total = 0;
    $('input[name^="comparative"]').each(function() {
        if ($(this).attr('name') !== 'comparative[total]') {
            let val = parseFloat($(this).val());
            if (!isNaN(val)) {
                total += val;
            }
        }
    });
    $('input[name="comparative[total]"]').val(total.toFixed(3));
    }

    // Kapag nag manual type ang HR
    $(document).on('input', 'input[name^="comparative"]', function() {
        calculateTotalScore();
    });

    // Tawagin agad pag-load ng page para kung may existing scores, ma-compute ang total
    $(document).ready(function() {
        calculateTotalScore();
    });
});
</script>
<script>
    $('#adminScoreForm').on('submit', function() {

    // disable ALL education modal inputs
    $('#educationModal').find('input, select, textarea').prop('disabled', true);

    // disable training, experience, etc (optional)
    $('#trainingModal, #experienceModal, #eligibilityModal')
        .find('input, select, textarea')
        .prop('disabled', true);

});
</script>
</body>
</html>

