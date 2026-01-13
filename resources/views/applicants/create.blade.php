<head>
    <meta charset="utf-8">
    <title>Reclassification - Applicant Form</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
 /* === OLD ENGLISH TEXT FONT === */
@font-face {
  font-family: 'OldEnglishTextMT';
  src: local('Old English Text MT'), local('OldEnglishTextMT');
  font-style: normal;
  font-weight: normal;
}
  /* === GENERAL TYPOGRAPHY === */
  body {
    font-family: "Bookman Old Style", "Times New Roman", serif;
    font-size: 14px;
    color: #111;
  }
    .domain-title {
        background: #2c3e50 !important;
        color: white !important;
        font-weight: bold !important;
    }
  /* === HEADER STYLES === */
  .header {
    text-align: center;
    margin-top: 10px;
    margin-bottom: 10px;
    position: relative;
  }

  .doc-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 6px;
    font-size: 13px;
  }
  .doc-top .left { color: #111; }
  .doc-top .right { font-style: italic; font-size: 13px; }

  .dep-logo {
    display: block;
    margin: 0 auto 6px;
    width: 78px;
    height: auto;
  }
  .level-check {
    pointer-events: none;   /* hindi clickable */
    opacity: 1;             /* normal color */
    cursor: default;
}

/* COI - YELLOW */
.row-coi {
  background-color: #fff8e1;
}

.row-coi td:first-child {
  border-left: 6px solid #ffc107;
}

/* NCOI - GREEN */
.row-ncoi {
  background-color: #e9f7ef;
}

.row-ncoi td:first-child {
  border-left: 6px solid #28a745;
}

/* === HEADER TEXT STYLES === */
.dep-rc-title {
  font-family: 'OldEnglishTextMT', 'Old English Text MT', serif;
  font-size: 11pt;
  font-weight: normal;
}

.dep-sub {
  font-family: 'OldEnglishTextMT', 'Old English Text MT', serif;
  font-size: 16pt;
  font-weight: normal;
}


  .form-card { max-width: 980px; margin: 0 auto 30px; }

  .levels-checkboxes label { margin-right:15px; cursor: pointer; }
  table td[contenteditable="true"] { background: #f9f9f9; }

  /* === PERFORMANCE TABLE HIGHLIGHT === */
  .highlight-row {
    background-color: #f8fff8 !important;
    border: 2px solid #28a745 !important;
    border-radius: 6px;
    transition: all 0.25s ease-in-out;
  }

  #performanceTable tbody tr {
    transition: all 0.25s ease-in-out;
  }

  /* === TABLE HEADERS === */
  table.table thead th {
    vertical-align: middle;
  }
 /* === RESPONSIVE TWEAKS === */
  @media (max-width: 576px) {
    .doc-top { flex-direction: column; gap: 6px; }
    .dep-logo { width: 64px; }
  }
</style>

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

        <form id="applicantForm" action="{{ route('applicants.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Name:</label>
                    <input name="name" class="form-control" placeholder="Ex.Juan D. Cruz" value="{{ old('name') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Current Position:</label>
                    <select name="current_position" class="form-select">
                        <option value="">-- Select Current Position --</option>
                        @foreach($positions as $p)
                            <option value="{{ $p }}" {{ old('current_position') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Position Applied:</label>
                    <select id="position_applied" name="position_applied" class="form-select">
                        <option value="">-- Select Position Applied --</option>
                        @foreach($positions as $p)
                            <option value="{{ $p }}" {{ old('position_applied') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Item No. of Current Position:</label>
                    <input type="text" name="item_number" class="form-control" value="{{ old('item_number') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Station / School:</label>
                    <select id="school_id" name="station_school" class="form-select">
                        <option value="">-- Select School --</option>

                        {{-- Kindergarten Schools --}}
    <optgroup label="Kindergarten Schools">
        @php
            $kindergarten = [
                'Canumay West Elementary School',
                                    'Dalandanan Elementary School',
                                    'Lingunan Elementary School',
                                    'Malinta Elementary School',
                                    'Roberta de Jesus Elementary School',
                                    'Canumay East Elementary School',
                                    'Lawang Bato Elementary School',
                                    'Punturin ES',
                                    'Disiplina Village Elementary School',
                                    'Pinalagad Elementary School',
                                    'Apolonio Casimiro Elementary School',
                                    'Antonio M. Serapio Elementary School',
                                    'Andres Mariano Elementary School',
                                    'Sitio Sto. Rosario Elementary School',
                                    'Maysan Elementary School',
                                    'Parada Elementary School',
                                    'Gen. T. de Leon Elementary School',
                                    'Apolonia F. Rafael Elementary School',
                                    'Paso de Blas Elementary School',
                                    'Silvestre Lazaro Elementary School',
                                    'Santos Encarnacion Elementary School',
                                    'Santiago A. de Guzman Elementary School',
                                    'Arcadio F. Deato Elementary School',
                                    'Andres Fernando Elementary School',
                                    'Coloong Elementary School',
                                    'Isla Elementary School',
                                    'P. R. Sandiego ES',
                                    'Paltok Elementary School',
                                    'Pasolo Elementary School',
                                    'Pio Valenzuela Elementary School',
                                    'Rincon Elementary School',
                                    'Tagalag Elementary School',
                                    'Luis Francisco Elementary School',
                                    'Wawang Pulo Elementary School',
                                    'Bitik Elementary School',
                                    'Caruhatan East Elementary School',
                                    'Caruhatan West Elementary School',
                                    'Constantino Elementary School',
                                    'Marulas Central School',
                                    'San Miguel Heights Elementary School',
                                    'Serrano Elementary School',
            ];
        @endphp
        @foreach($kindergarten as $school)
            <option value="{{ $school }}" data-level="kindergarten">{{ $school }}</option>
        @endforeach
    </optgroup>

                        <!-- Elementary -->
                        <optgroup label="Elementary Schools">
                            @php
                                $elementary = [
                                    'Canumay West Elementary School',
                                    'Dalandanan Elementary School',
                                    'Lingunan Elementary School',
                                    'Malinta Elementary School',
                                    'Roberta de Jesus Elementary School',
                                    'Canumay East Elementary School',
                                    'Lawang Bato Elementary School',
                                    'Punturin ES',
                                    'Disiplina Village Elementary School',
                                    'Pinalagad Elementary School',
                                    'Apolonio Casimiro Elementary School',
                                    'Antonio M. Serapio Elementary School',
                                    'Andres Mariano Elementary School',
                                    'Sitio Sto. Rosario Elementary School',
                                    'Maysan Elementary School',
                                    'Parada Elementary School',
                                    'Gen. T. de Leon Elementary School',
                                    'Apolonia F. Rafael Elementary School',
                                    'Paso de Blas Elementary School',
                                    'Silvestre Lazaro Elementary School',
                                    'Santos Encarnacion Elementary School',
                                    'Santiago A. de Guzman Elementary School',
                                    'Arcadio F. Deato Elementary School',
                                    'Andres Fernando Elementary School',
                                    'Coloong Elementary School',
                                    'Isla Elementary School',
                                    'P. R. Sandiego ES',
                                    'Paltok Elementary School',
                                    'Pasolo Elementary School',
                                    'Pio Valenzuela Elementary School',
                                    'Rincon Elementary School',
                                    'Tagalag Elementary School',
                                    'Luis Francisco Elementary School',
                                    'Wawang Pulo Elementary School',
                                    'Bitik Elementary School',
                                    'Caruhatan East Elementary School',
                                    'Caruhatan West Elementary School',
                                    'Constantino Elementary School',
                                    'Marulas Central School',
                                    'San Miguel Heights Elementary School',
                                    'Serrano Elementary School',
                                ];
                            @endphp
                            @foreach($elementary as $school)
                                <option value="{{ $school }}" data-level="elementary">{{ $school }}</option>
                            @endforeach
                        </optgroup>

                        <!-- Junior High -->
                       <optgroup label="Junior High Schools">
                            @php
                                $junior = [
                                    'Bagbaguin National High School-JHS',
                                    'Caruhatan National High School-JHS',
                                    'Gen. T. De Leon National High School-JHS',
                                    'Justice Eliezer Delos Santos National High School-JHS',
                                    'Mapulang Lupa National High School-JHS',
                                    'Maysan National High School-JHS',
                                    'Paso De Blas National High School-JHS',
                                    'Parada National High School-JHS',
                                    'Sitero Francisco Memorial National High School-JHS',
                                    'Valenzuela National High School-JHS',
                                    'Arkong Bato National High School-JHS',
                                    'Dalandanan National High School-JHS',
                                    'Malanday National High School-JHS',
                                    'Malinta National High School-JHS',
                                    'Polo National High School-JHS',
                                    'Valenzuela City School of Mathematics and Science-JHS',
                                    'Veinte Reales National High School-JHS',
                                    'Wawangpulo National High School-JHS',
                                    'Bignay National High School-JHS',
                                    'Canumay East National High School-JHS',
                                    'Canumay West National High School-JHS',
                                    'Disiplina Village - Bignay National High School-JHS',
                                    'Lawang Bato National High School-JHS',
                                    'Lingunan National High School-JHS',
                                    'Vicente P. Trinidad National High School-JHS'
                                ];
                            @endphp

                            @foreach($junior as $school)
                                <option value="{{ $school }}" data-level="junior_high">{{ $school }}</option>
                            @endforeach
                        </optgroup>


                        <!-- Senior High -->
                        <optgroup label="Senior High Schools">
                            @php
                                $senior = [
                                    'Arkong Bato National High School-SHS',
                                    'Bignay National High School-SHS',
                                    'Dalandanan National High School-SHS',
                                    'Lawang Bato National High School-SHS',
                                    'Lingunan National High School-SHS',
                                    'Malanday National High School-SHS',
                                    'Malinta National High School-SHS',
                                    'Polo National High School-SHS',
                                    'Punturin National High School-SHS',
                                    'Valenzuela City School of Mathematics and Science-SHS',
                                    'Vicente P. Trinidad National High School-SHS',
                                    'Wawang Pulo National High School-SHS',
                                    'Caruhatan National High School-SHS',
                                    'Gen. T. De Leon National High School-SHS',
                                    'Maysan National High School-SHS',
                                    'Ugong SHS',
                                    'Mapulang Lupa National High School-SHS',
                                    'Parada National High School-SHS',
                                    'Paso De Blas National High School-SHS',
                                    'Sitero Francisco Memorial National High School-SHS',
                                    'Valenzuela National High School-SHS'
                                ];
                            @endphp
                            @foreach($senior as $school)
                                <option value="{{ $school }}" data-level="senior_high">{{ $school }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">SG/Annual Salary:</label>
                <input 
                    type="number" 
                    name="sg_annual_salary" 
                    class="form-control" 
                    value="{{ old('sg_annual_salary') }}" 
                    placeholder="Enter SG or Annual Salary">
                </div>
            </div>

    <div class="mb-3">
  <label class="form-label fw-bold">Level:</label>
  <div class="row ps-5">  {{-- ps-5 ≈ 3rem (~1 inch) --}}
    <div class="col-md-6">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="levels[]" value="kindergarten" id="levelKindergarten">
        <label class="form-check-label" for="levelKindergarten">Kindergarten</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="levels[]" value="elementary" id="levelElementary">
        <label class="form-check-label" for="levelElementary">Elementary</label>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="levels[]" value="junior_high" id="levelJuniorHigh">
        <label class="form-check-label" for="levelJuniorHigh">Junior High School</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="levels[]" value="senior_high" id="levelSeniorHigh">
        <label class="form-check-label" for="levelSeniorHigh">Senior High School</label>
      </div>
    </div>
  </div>
</div>

    <!-- QS TABLE -->
<hr class="mt-2">
<h5 class="text-left fw-bold mt-3">I. QUALIFICATION STANDARDS</h5>

<table class="table table-bordered mt-3 text-center align-middle">
    <thead>
        <tr>
            <th>Elements</th>
            <th>QS of the Position</th>
            <th>QS of the Applicant</th>
             <th>Upload Pdf File</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
       <tr id="education-row">
    <td>Education</td>
    <td id="qs_education">—</td>
    <td contenteditable="true" id="education_input"
        style="border:1px solid #ccc; padding:6px; min-height:38px;">
        <!-- Applicant types education here -->
    </td>
    <td>
        <input type="file" id="education_file"
               accept="application/pdf" class="form-control">
    </td>
    <td id="education_remark"></td>
</tr>

        <tr>
            <td>Training</td>
            <td id="qs_training">—</td>
            <td>
            <input type="text" id="training_name" class="form-control mb-1"
                  placeholder="Training Title / Seminar Name">

            <input type="number" id="training_hours" class="form-control mb-1"
                  placeholder="No. of Hours" min="1">

            <input type="date" id="training_date" class="form-control">
          </td>
            <td>
              <input type="file" id="training_file" accept="application/pdf" class="form-control">
            </td>
            <td id="training_remark"></td>
        </tr>
        <tr>
            <td>Experience</td>
            <td id="qs_experience">—</td>
            <td contenteditable="true"></td>
            <td></td>
        </tr>
        <tr>
            <td>Eligibility</td>
            <td id="qs_eligibility">—</td>
            <td contenteditable="true"></td>
            <td></td>
        </tr>
    </tbody>
</table>

    <p class="text-muted mb-3 fst-italic">
Note: Indicate the QS of the Position Applied for based on the CSC-Approved QS
</p>

    <!-- PERFORMANCE REQUIREMENTS -->
    <h5 class="text-left fw-bold mt-4">II. PERFORMANCE REQUIREMENTS</h5>
    <p>1. Copy of duly approved IPCRF for the school year immediately preceeding the application.</p>
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
        </tbody>
    </table>

    {{-- ========================================================= --}}
{{-- III. SUMMARY OF THE ACHIEVEMENT OF PPST INDICATORS --}}
{{-- ========================================================= --}}
<hr class="mt-4 mb-4">
<h5 class="fw-bold text-uppercase">Summary of the Achievement of PPST Indicators</h5>

<p class="text-muted mb-3 fst-italic">
  *Put a (/) mark if the applicant meets the required PPST indicators; if not, put an (X) mark in both the "O" and "VS" columns.
</p>


<div class="table-responsive mb-4">
  <table class="table table-bordered align-middle">
    <thead class="text-center">
      {{-- ============ Domain 1 ============ --}}
      <tr>
        <th colspan="2">Domain/Strand/Indicators</th>
        <th style="width:10%">O</th>
        <th style="width:10%">VS</th>
      </tr>
      <tr>
        <th style="width:8%">No.</th>
        <th>Domain 1. Content Knowledge and Pedagogy</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>

      
      @php
      $domain1 = [
        '1.1.2 Apply knowledge of content within and across curriculum teaching areas.',
        '1.2.2 Use research-based knowledge and principles of teaching and learning to enhance professional practice.',
        '1.3.2 Ensure the positive use of ICT to facilitate the teaching and learning process.',
        '1.4.2 Use a range of teaching strategies that enhance learner achievement in literacy and numeracy skills.',
        '1.5.2 Apply a range of teaching strategies to develop critical and creative thinking, as well as other higher-order thinking skills.',
        '1.6.2 Display proficient use of Mother Tongue, Filipino and English to facilitate teaching and learning.',
        '1.7.2 Use effective verbal and non-verbal classroom communication strategies to support learner understanding, participation, engagement and achievement.',
      ];
      @endphp
      @foreach ($domain1 as $i => $indicator)
      <tr>
        <td class="text-center">{{ $i+1 }}</td>
        <td>{{ $indicator }}</td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $i+1 }}][O]" value="1"></td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $i+1 }}][VS]" value="1"></td>
      </tr>
      @endforeach

     {{-- ============ Domain 2 ============ --}}
<tr class="fw-semibold">
  <th></th>
  <td colspan="3">Domain 2. Learning Environment</td>
</tr>

@php
  $domain2 = [
    '2.1.2 Establish safe and secure learning environments to enhance learning through the consistent implementation of policies, guidelines and procedures.',
    '2.2.2 Maintain learning environments that promote fairness, respect and care to encourage learning.',
    '2.3.2 Manage classroom structure to engage learners, individually or in groups, in meaningful exploration, discovery and hands-on activities within a range of physical learning environments.',
    '2.4.2 Maintain supportive learning environments that nurture and inspire learners to participate, cooperate and collaborate in continued learning.',
    '2.5.2 Apply a range of successful strategies that maintain learning environments that motivate learners to work productively by assuming responsibility for their own learning.',
    '2.6.2 Manage learner behavior constructively by applying positive and non-violent discipline to ensure learning-focused environments.',
  ];
@endphp

@foreach ($domain2 as $i => $indicator)
  @php 
    $num = $i + 8;  /* 8–13 */
    $isCOI = in_array($num, [1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,23,24]);
    $rowClass = $isCOI ? 'row-coi' : 'row-ncoi';
  @endphp

  <tr class="{{ $rowClass }}">
    <td class="text-center">{{ $num }}</td>
    <td>{{ $indicator }}</td>
    <td class="text-center">
      <input type="checkbox" name="ppst[{{ $num }}][O]" value="1">
    </td>
    <td class="text-center">
      <input type="checkbox" name="ppst[{{ $num }}][VS]" value="1">
    </td>
  </tr>
@endforeach



      {{-- ============ Domain 3 ============ --}}
      <tr class="fw-semibold">
        <th></th>
        <td colspan="4">Domain 3. Diversity of Learners</td>
      </tr>
      @php
      $domain3 = [
        '3.1.2 Use differentiated, developmentally appropriate learning experiences to address learners’ gender, needs, strengths, interests and experiences.',
        '3.2.2 Establish a learner-centered culture by using teaching strategies that respond to learners’ linguistic, cultural, socio-economic and religious backgrounds.',
        '3.3.2 Design, adapt and implement teaching strategies that are responsive to learners with disabilities, giftedness and talents.',
        '3.4.2 Plan and deliver teaching strategies that are responsive to the special educational needs of learners in difficult circumstances, including: geographic isolation; chronic illness; displacement due to armed conflict, urban resettlement or disasters; child abuse and child labor practices. ',
        '3.5.2 Adapt and use culturally appropriate teaching strategies to address the needs of learners from indigenous groups.',
      ];
      @endphp
      @foreach ($domain3 as $i => $indicator)
      @php $num = $i + 14; @endphp
      <tr>
        <td class="text-center">{{ $num }}</td>
        <td>{{ $indicator }}</td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][O]" value="1"></td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][VS]" value="1"></td>
      </tr>
      @endforeach

      {{-- ============ Domain 4 ============ --}}
      <tr class="fw-semibold">
        <th></th>
        <td colspan="4">Domain 4. Curriculum and Planning</td>
      </tr>
      @php
      $domain4 = [
        '4.1.2 Plan, manage and implement developmentally sequenced teaching and learning processes to meet curriculum requirements and varied teaching contexts.',
        '4.2.2 Set achievable and appropriate learning outcomes that are aligned with learning competencies.',
        '4.3.2 Adapt and implement learning programs that ensure relevance and responsiveness to the needs of all learners. ',
        '4.4.2 Participate in collegial discussions that use teacher and learner feedback to enrich teaching practice.',
        '4.5.2 Select, develop, organize and use appropriate teaching and learning resources, including ICT, to address learning goals.',
      ];
      @endphp
      @foreach ($domain4 as $i => $indicator)
      @php $num = $i + 19; @endphp
      <tr>
        <td class="text-center">{{ $num }}</td>
        <td>{{ $indicator }}</td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][O]" value="1"></td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][VS]" value="1"></td>
      </tr>
      @endforeach

      {{-- ============ Domain 5 ============ --}}
      <tr class="fw-semibold">
        <th></th>
        <td colspan="4">Domain 5.  Assessment and Reporting</td>
      </tr>
      @php
      $domain5 = [
        '5.1.2.  Design, select, organize and use diagnostic, formative, and summative assessment strategies consistent with curriculum requirements',
        '5.2.2 Monitor and evaluate learner progress and achievement using learner attainment data.',
        '5.3.2 Use strategies for providing timely, accurate and constructive feedback to improve learner performance. ',
        '5.4.2 Communicate promptly and clearly the learners’ needs, progress and achievement to key stakeholders, including parents/guardians.',
        '5.5.2 Utilize assessment data to inform the modification of teaching and learning practices and programs.',
      ];
      @endphp
      @foreach ($domain5 as $i => $indicator)
      @php $num = $i + 24; @endphp
      <tr>
        <td class="text-center">{{ $num }}</td>
        <td>{{ $indicator }}</td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][O]" value="1"></td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][VS]" value="1"></td>
      </tr>
      @endforeach

      {{-- ============ Domain 6 ============ --}}
      <tr class="fw-semibold">
        <th></th>
        <td colspan="4">Domain 6. Community Linkages and Professional Engagement</td>
      </tr>
      @php
      $domain6 = [
        '6.1.2 Maintain learning environments that are responsive to community contexts.',
        '6.2.2 Build relationships with parents/guardians and the wider school community to facilitate involvement in the educative process.',
        '6.3.2 Review regularly personal teaching practice using existing laws and regulations that apply to the teaching profession and the responsibilities specified in the Code of Ethics for Professional Teachers.',
        '6.4.2 Comply with and implement school policies and procedures consistently to foster harmonious relationships with learners, parents, and other stakeholders.',
      ];
      @endphp
      @foreach ($domain6 as $i => $indicator)
      @php $num = $i + 29; @endphp
      <tr>
        <td class="text-center">{{ $num }}</td>
        <td>{{ $indicator }}</td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][O]" value="1"></td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][VS]" value="1"></td>
      </tr>
      @endforeach

      {{-- ============ Domain 7 ============ --}}
      <tr class="fw-semibold">
        <th></th>
        <td colspan="4">Domain 7. Personal Growth and Professional Development</td>
      </tr>
      @php
      $domain7 = [
        '7.1.2 Apply a personal philosophy of teaching that is learner-centered.',
        '7.2.2 Adopt practices that uphold the dignity of teaching as a profession by exhibiting qualities such as caring attitude, respect and integrity.',
        '7.3.2 Participate in professional networks to share knowledge and to enhance practice.',
        '7.4.2 Develop a personal professional improvement plan based on reflection of one’s practice and ongoing professional learning.',
        '7.5.2 Set professional development goals based on the Philippine Professional Standards for Teachers.',
      ];
      @endphp
      @foreach ($domain7 as $i => $indicator)
      @php $num = $i + 33; @endphp
      <tr>
        <td class="text-center">{{ $num }}</td>
        <td>{{ $indicator }}</td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][O]" value="1"></td>
        <td class="text-center"><input type="checkbox" name="ppst[{{ $num }}][VS]" value="1"></td>
      </tr>
      @endforeach

      {{-- ======= Totals OF O AND VS ======= --}}
      <tr class="fw-semibold">
  <td colspan="2" class="text-center">Total Number of O and VS</td>
  <td>
    O
    <input type="number" id="totalO" name="total_outstanding" readonly class="form-control form-control-sm d-inline-block text-center" style="width:80px">
  </td>
  <td>
    VS
    <input type="number" id="totalVS" name="total_vs" readonly class="form-control form-control-sm d-inline-block text-center" style="width:80px">
  </td>
</tr>

{{-- ======= Totals of NCOI AND COI ======= --}}
<tr class="fw-semibold">
  <td colspan="2" class="text-center">Total Number of COI and NCOI</td>
  <td>
    COI
    <input type="number" id="totalCOI" name="total_coi" readonly class="form-control form-control-sm d-inline-block text-center" style="width:80px">
  </td>
  <td>
    NCOI
    <input type="number" id="totalNCOI" name="total_ncoi" readonly class="form-control form-control-sm d-inline-block text-center" style="width:80px">
  </td>
</tr>
    </tbody>
  </table>
</div>

<!-- === PRINT & SUBMIT BUTTONS === -->
<div class="text-center my-4">
    <button type="button" class="btn btn-secondary me-2" onclick="window.print()">🖨️ Print</button>
    <button type="submit" form="applicantForm" class="btn btn-success">💾 Submit</button>
</div>
        </form>
    </div>

{{-- ========================================================= --}}
{{-- III. COMPARATIVE ASSESSMENT RESULT --}}
{{-- ========================================================= --}}
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
        <th>Total Score</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><input type="number" name="comparative[education]" class="form-control form-control-sm text-center"></td>
        <td><input type="number" name="comparative[training]" class="form-control form-control-sm text-center"></td>
        <td><input type="number" name="comparative[experience]" class="form-control form-control-sm text-center"></td>
        <td><input type="number" name="comparative[performance]" class="form-control form-control-sm text-center"></td>
        <td><input type="number" name="comparative[classroom]" class="form-control form-control-sm text-center"></td>
        <td><input type="number" name="comparative[non_classroom]" class="form-control form-control-sm text-center"></td>
        <td><input type="number" name="comparative[total]" class="form-control form-control-sm text-center"></td>
      </tr>
    </tbody>
  </table>
</div>


<div class="row text-center mb-5">
  <div class="col-md-6">
    <p class="fw-semibold mb-1">Conforme:</p>
    <br><br>
    <p class="fw-bold text-decoration-underline"></p>
    <p class="small mb-0">Teacher Applicant</p>
  </div>
  <div class="col-md-6">
    <p class="fw-semibold mb-1">Attested by:</p>
    <br><br>
    <p class="fw-bold text-decoration-underline">ERNEST JOSEPH C. CABRERA</p>
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
        <td><input type="text" name="division[from_position]" class="form-control form-control-sm"></td>
        <td><input type="text" name="division[from_grade]" class="form-control form-control-sm"></td>
        <td><input type="text" name="division[to_position]" class="form-control form-control-sm"></td>
        <td><input type="text" name="division[to_grade]" class="form-control form-control-sm"></td>
        <td><input type="date" name="division[date_processed]" class="form-control form-control-sm"></td>
        <td><input type="text" name="division[remarks]" class="form-control form-control-sm"></td>
      </tr>
    </tbody>
  </table>
</div>

<div class="row text-center mb-5">
  <div class="col-md-12">
    <p class="fw-semibold mb-1">Evaluated by:</p>
    <p class="fw-bold text-decoration-underline mb-0">MA. CLARINDA L. OMO</p>
    <p class="small mb-0">Administrative Officer IV</p>

    <br>
    <p class="fw-semibold mb-1">Certified Correct:</p>
    <p class="fw-bold text-decoration-underline mb-0">CARMELITA D. MATUS</p>
    <p class="small mb-0">Administrative Officer V</p>

    <br>
    <p class="fw-semibold mb-1">Recommending Approval:</p>
    <p class="fw-bold text-decoration-underline mb-0">NOEL D. BAGANO</p>
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

<div class="row text-center mb-5">
  <div class="col-md-12">
    <p class="fw-semibold mb-1">Evaluated by:</p>
    <p class="fw-bold text-decoration-underline mb-0">Teachers Credential Evaluator</p>

    <br>
    <p class="fw-semibold mb-1">Certified Correct:</p>
    <p class="fw-bold text-decoration-underline mb-0">Chief, Administrative Division</p>

    <br>
    <p class="fw-semibold mb-1">Approved:</p>
    <p class="fw-bold text-decoration-underline mb-0">JOCELYN DR. ANDAYA</p>
    <p class="small mb-0">Regional Director, NCR</p>
    <p class="small mb-0">Concurrent Officer-In-Charge, Office of the Assistant Secretary for Operations</p>
  </div>
</div>


</div> <!-- /.container -->

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

/* Highlight Performance Requirements Row */
function highlightPerformanceRow() {
    let selected = $('#position_applied').val() ? $('#position_applied').val().trim() : '';
    const $rows = $('#performanceTable tbody tr');

    $rows.removeClass('highlight-row');

    if (!selected) return;

    $rows.each(function() {
        const dp = $(this).data('position');
        if (dp && dp.toString().trim() === selected) {
            $(this).addClass('highlight-row');
        }
    });
}

/* ================================
   DOCUMENT READY
================================ */
$(document).ready(function() {

    const $levelCheckboxes = $('input[name="levels[]"]');
    const $schoolSelect = $('#school_id');

    // Ensure all unchecked initially
    $levelCheckboxes.prop('checked', false);

    /* BLOCK MANUAL CLICKING + SWEETALERT */
    $levelCheckboxes.on('click', function(e) {

        // If no school selected yet
        if (!$schoolSelect.val()) {
            e.preventDefault();

            Swal.fire({
                icon: 'info',
                title: 'Select School First',
                text: 'Please select a school/station to automatically determine the level.',
                confirmButtonColor: '#3085d6'
            });

            return false;
        }

        // Even if school is selected, still block manual checking
        e.preventDefault();
        return false;
    });

    /* SCHOOL CHANGE → AUTO-CHECK LEVEL */
    $('#school_id').on('change', function() {
        let level = $(this).find(':selected').data('level');

        // Reset all
        $levelCheckboxes.prop('checked', false);

        // Auto-check ONLY by system
        if (level) {
            $(`input[name="levels[]"][value="${level}"]`).prop('checked', true);
        }

        updateHeaderForPosition();
        highlightPerformanceRow();

        // Refresh QS
        $('#position_applied').trigger('change');
    });

    /* POSITION CHANGE → AJAX QS */
    $('#position_applied').on('change', function() {
        let position = $(this).val();
        let level = getSelectedLevel();

        if (position) {
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
                }
            });
        } else {
            $('#qs_education, #qs_training, #qs_experience, #qs_eligibility').text('—');
        }

        updateHeaderForPosition();
        highlightPerformanceRow();
    });

    // Initial load
    updateHeaderForPosition();
    highlightPerformanceRow();

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

  /* ===============================
     CHECKBOX REFERENCES
     =============================== */
  const oBoxes  = document.querySelectorAll('input[name^="ppst"][name$="[O]"]');
  const vsBoxes = document.querySelectorAll('input[name^="ppst"][name$="[VS]"]');

  const totalO    = document.getElementById("totalO");
  const totalVS   = document.getElementById("totalVS");
  const totalCOI  = document.getElementById("totalCOI");
  const totalNCOI = document.getElementById("totalNCOI");

  /* ===============================
     COI / NCOI REFERENCES
     =============================== */
  const coiNumbers  = [1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,23,24];
  const ncoiNumbers = [2,20,21,22,25,26,27,28,29,30,31,32,33,34,35,36,37];

  /* ===============================
     APPLY ROW COLOR (NO CHECK NEEDED)
     =============================== */
  document.querySelectorAll("table tbody tr").forEach(row => {

    const firstCell = row.querySelector("td:first-child");
    if (!firstCell) return;

    const num = parseInt(firstCell.innerText.trim(), 10);
    if (isNaN(num)) return;

    if (coiNumbers.includes(num)) {
      row.classList.add("row-coi");
    }

    if (ncoiNumbers.includes(num)) {
      row.classList.add("row-ncoi");
    }
  });

  /* ===============================
     O vs VS LOCKING (MUTUAL EXCLUSIVE)
     =============================== */
  function syncOVS(num) {
    const o  = document.querySelector(`input[name='ppst[${num}][O]']`);
    const vs = document.querySelector(`input[name='ppst[${num}][VS]']`);

    if (!o || !vs) return;

    // If O is checked → disable VS
    if (o.checked) {
      vs.checked = false;
      vs.disabled = true;
    } else {
      vs.disabled = false;
    }

    // If VS is checked → disable O
    if (vs.checked) {
      o.checked = false;
      o.disabled = true;
    } else {
      o.disabled = false;
    }
  }

  /* ===============================
     UPDATE TOTALS
     =============================== */
  function updateTotals() {

    const countO  = Array.from(oBoxes).filter(b => b.checked).length;
    const countVS = Array.from(vsBoxes).filter(b => b.checked).length;

    totalO.value  = countO;
    totalVS.value = countVS;

    let coiCount  = 0;
    let ncoiCount = 0;

    coiNumbers.forEach(num => {
      const o  = document.querySelector(`input[name='ppst[${num}][O]']`);
      const vs = document.querySelector(`input[name='ppst[${num}][VS]']`);
      if ((o && o.checked) || (vs && vs.checked)) coiCount++;
    });

    ncoiNumbers.forEach(num => {
      const o  = document.querySelector(`input[name='ppst[${num}][O]']`);
      const vs = document.querySelector(`input[name='ppst[${num}][VS]']`);
      if ((o && o.checked) || (vs && vs.checked)) ncoiCount++;
    });

    totalCOI.value  = coiCount;
    totalNCOI.value = ncoiCount;
  }

  /* ===============================
     EVENT LISTENERS
     =============================== */
  [...oBoxes, ...vsBoxes].forEach(box => {
    box.addEventListener("change", function () {

      // Extract number from name: ppst[8][O]
      const match = this.name.match(/ppst\[(\d+)\]/);
      if (match) {
        syncOVS(match[1]);
      }

      updateTotals();
    });
  });

});
</script>
<!-- EDUCATION JS -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const levelSelect    = document.getElementById("level");
    const positionSelect = document.getElementById("position_applied");
    const applicantCell  = document.getElementById("education_input");
    const remarkCell     = document.getElementById("education_remark");
    const qsCell         = document.getElementById("qs_education");

    let requiredLevel = null;

    function normalize(text = "") {
        return text
            .toLowerCase()
            .replace(/\u00A0/g, " ")   // remove nbsp
            .replace(/\s+/g, " ")
            .trim();
    }

    function detectLevel(text = "") {
        if (/(master|maed|m\.ed|masters)/i.test(text)) return "master";
        if (/(bachelor|bachelors|bachelor’s|bachelor's|bsed|beed|b\.ed|b\.s\.ed)/i.test(text))
            return "bachelor";
        return null;
    }

    function fetchEducationQS() {

        const level = levelSelect.value;
        const position = positionSelect.value;

        if (!level || !position) {
            remarkCell.innerHTML =
                '<span class="text-muted">Select level & position</span>';
            return;
        }

        fetch(`/get-qs?level=${level}&position=${encodeURIComponent(position)}`)
            .then(res => res.json())
            .then(res => {
                if (!res.success) {
                    remarkCell.innerHTML =
                        '<span class="text-muted">QS not found</span>';
                    return;
                }

                qsCell.innerText = res.data.education;
                requiredLevel = detectLevel(normalize(res.data.education));

                evaluateEducation();
            });
    }

    function evaluateEducation() {

        if (!requiredLevel) {
            remarkCell.innerHTML =
                '<span class="text-muted">Waiting for QS</span>';
            return;
        }

        const applicantText = normalize(applicantCell.textContent || "");

        if (!applicantText) {
            remarkCell.innerHTML =
                '<span class="text-muted">Enter education details</span>';
            return;
        }

        const applicantLevel = detectLevel(applicantText);

        let isMet = false;

        if (requiredLevel === "bachelor") {
            isMet = applicantLevel === "bachelor" || applicantLevel === "master";
        }

        if (requiredLevel === "master") {
            isMet = applicantLevel === "master";
        }

        remarkCell.innerHTML = isMet
            ? '<span class="text-success fw-bold">MET</span>'
            : '<span class="text-danger fw-bold">NOT MET</span>';
    }

    // 🔥 FIX: contenteditable events
    ["keyup", "paste", "blur"].forEach(evt => {
        applicantCell.addEventListener(evt, evaluateEducation);
    });

    levelSelect.addEventListener("change", fetchEducationQS);
    positionSelect.addEventListener("change", fetchEducationQS);

    // 🔥 auto-load QS on page load
    if (levelSelect.value && positionSelect.value) {
        fetchEducationQS();
    }

});
</script>

<!-- DATA PRIVACY -->
<script>
document.addEventListener("DOMContentLoaded", function () {

  // Check if user already accepted Data Privacy
  if (!localStorage.getItem("dataPrivacyAccepted")) {

    Swal.fire({
      title: "Data Privacy Notice",
      icon: "info",
      html: `
        <p style="text-align:justify; font-size:14px;">
          By accessing and using this system, you acknowledge and agree that
          all personal information and documents you provide shall be collected,
          processed, stored, and protected in accordance with the
          <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong>.
        </p>
        <p style="text-align:justify; font-size:14px;">
          Your information will be used solely for legitimate purposes related
          to application evaluation, verification, and record management, and
          will not be shared with unauthorized parties.
        </p>
        <p style="text-align:justify; font-size:13px; font-style:italic;">
          By clicking <strong>"I Agree"</strong>, you voluntarily consent to the
          collection and processing of your personal data.
        </p>
      `,
      allowOutsideClick: false,
      allowEscapeKey: false,
      confirmButtonText: "I Agree",
      confirmButtonColor: "#198754"
    }).then((result) => {
      if (result.isConfirmed) {
        localStorage.setItem("dataPrivacyAccepted", "yes");
      }
    });

  }
});
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
<script>
// ================= GLOBAL QS STATE =================
let requiredTrainingHours = 0;
let requiresFiveYearsRule = false;

// ================= HELPERS =================
function extractHours(text) {
    const match = text.match(/(\d+)\s*hours/i);
    return match ? parseInt(match[1]) : 0;
}

function isWithinYears(dateStr, years) {
    const d = new Date(dateStr);
    const now = new Date();

    const limit = new Date(
        now.getFullYear() - years,
        now.getMonth(),
        now.getDate()
    );

    return d >= limit;
}

// ================= FETCH QS =================
function fetchQS(level, position) {

    if (!level || !position) return;

    fetch(`/get-qs?level=${level}&position=${encodeURIComponent(position)}`)
        .then(res => res.json())
        .then(res => {
            if (!res.success) return;

            const trainingText = res.data.training || '';

            requiredTrainingHours = extractHours(trainingText);
            requiresFiveYearsRule =
                trainingText.toLowerCase().includes('5 years');

            checkTrainingQS();
        });
}

// ================= QS CHECKER =================
function checkTrainingQS() {

    const name  = document.getElementById('training_name')?.value.trim();
    const hours = parseInt(
        document.getElementById('training_hours')?.value
    );
    const date  = document.getElementById('training_date')?.value;

    const remark = document.getElementById('training_remark');
    if (!remark) return;

    // ================= NO TRAINING REQUIRED =================
    if (requiredTrainingHours === 0) {
        remark.innerHTML =
          '<span class="text-success">QS MET – No Training Required</span>';
        return;
    }

    // ================= INCOMPLETE INPUT =================
    if (!name || isNaN(hours) || hours <= 0 || !date) {
        remark.innerHTML =
          '<span class="text-muted">Waiting for complete input</span>';
        return;
    }

    // ================= VALIDATION =================
    const hoursOk = hours >= requiredTrainingHours;
    let dateOk = true;

    if (requiresFiveYearsRule) {
        dateOk = isWithinYears(date, 5);
    }

    // ================= MET =================
    if (hoursOk && dateOk) {
        let meta = `${hours}/${requiredTrainingHours} hrs`;
        if (requiresFiveYearsRule) meta += ', within 5 years';

        remark.innerHTML = `
          <span class="text-success">
            QS MET (${meta})
          </span>`;
        return;
    }

    // ================= NOT MET =================
    let reasons = [];

    if (!hoursOk) {
        reasons.push(
            `Insufficient hours (${hours}/${requiredTrainingHours})`
        );
    }

    if (!dateOk) {
        reasons.push('Training date beyond 5 years');
    }

    remark.innerHTML = `
      <span class="text-danger">
        QS NOT MET – ${reasons.join(' & ')}
      </span>`;
}

// ================= EVENT BINDING =================
document.addEventListener('DOMContentLoaded', function () {

    // training inputs
    ['training_name','training_hours','training_date'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', checkTrainingQS);
            el.addEventListener('change', checkTrainingQS);
        }
    });

    // level / position
    const levelSelect = document.getElementById('level');
    const positionSelect = document.getElementById('position_applied');

    function reloadQS() {
        fetchQS(levelSelect.value, positionSelect.value);
    }

    levelSelect?.addEventListener('change', reloadQS);
    positionSelect?.addEventListener('change', reloadQS);
});
</script>
</body>
</html>
