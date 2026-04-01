<!-- EDUCATION MODAL -->

<div class="modal fade" id="educationModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">
  <!-- Modal Header -->
  <div class="modal-header bg-primary text-white">
    <h6 class="modal-title fw-bold">
      <i class="fas fa-graduation-cap me-2"></i>Add Education
    </h6>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
  </div>

  <!-- Modal Body -->
  <div class="modal-body p-4">
    <div class="row g-4">

      <!-- ================= LEFT SIDE ================= -->
      <!-- RUBRICS + SUMMARY -->
      <div class="col-md-4 d-flex border-end pe-3">

  <div class="education-rubrics-sticky w-100 d-flex flex-column gap-2">

    <!-- RUBRICS CARD -->
    <div class="card border-0 shadow-sm border-light-green">
      <div class="card-header">
        <h6 class="mb-0 fw-semibold">
          <i class="fas fa-list-check me-2"></i>Education Rubrics
        </h6>
      </div>

      <div class="card-body p-2">
        <ul class="list-group list-group-flush">

          <li class="list-group-item small">
            <strong>Bachelor’s Degree</strong><br>
            <span class="text-muted">Required for Teacher I–V</span>
          </li>

          <li class="list-group-item small">
            <strong>Master’s Degree Units</strong><br>
            <span class="text-muted">Required for Teacher VI–VII</span>
          </li>

          <li class="list-group-item small">
            <strong>Master’s Degree</strong><br>
            <span class="text-muted">Required for Master Teacher</span>
          </li>

          <li class="list-group-item small text-muted">
            Points are based on excess units earned
          </li>

        </ul>
      </div>
    </div>

    <!-- SUMMARY CARD -->
    <div class="card border-0 shadow-sm border-light-green">
      <div class="card-body p-2">

        <strong class="d-block mb-1">Education Summary</strong>

        <div class="small">
          Degree: <span id="edu_degree_display">—</span><br>
          Level: <span id="edu_level_display">—</span><br>
          Status: <span id="edu_status_display" class="text-muted">Waiting..</span><br>
          Score: <span id="edu_points_display" class="fw-bold text-muted">0 pts</span>
        </div>

      </div>
    </div>

  </div>
</div>

      <!-- ================= RIGHT SIDE ================= -->
      <!-- EDUCATION FORM -->
      <div class="col-md-8">

  <div class="card shadow-sm border-0 mb-3">
    <div class="card-header bg-light py-2">
      <h6 class="mb-0 fw-semibold text-primary">
        Education Information
      </h6>
    </div>

    <div class="card-body p-3">

      <!-- 🔥 BASIC INFO -->
      <div class="row g-3 mb-2">

        <!-- DEGREE -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">
            Degree/Title
          </label>
          <input type="text"
                 id="education_name"
                 name="education[degree]"
                 class="form-control education-modal-border"
                 placeholder="e.g. Bachelor of Secondary Education"
                 required>
        </div>

        <!-- SCHOOL -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">
            School Graduated
          </label>
          <input type="text"
                 id="education_school"
                 name="education[school]"
                 class="form-control education-modal-border"
                 placeholder="e.g. University of Manila"
                 required>
        </div>

      </div>

      <!-- 🔥 EDUCATION DETAILS (BETTER PROPORTION) -->
      <div class="row g-3 align-items-end">

        <!-- DATE -->
        <div class="col-md-3">
          <label class="form-label fw-semibold">
            Date Graduated
          </label>
          <input type="date"
                 id="education_date"
                 name="education[date_graduated]"
                 class="form-control education-modal-border"
                 required>
        </div>

        <!-- EDUC LEVEL -->
        <div class="col-md-5">
          <label class="form-label fw-semibold">
            Highest Educational Attainment
          </label>
          <select id="education_units_select"
                  name="education[level]"
                  class="form-select education-modal-border">
            <option value="">Select Education Level</option>
          </select>
        </div>

        <!-- CTP -->
        <div class="col-md-4 d-none" id="ctp_container">
          <label class="form-label fw-semibold text-warning">
            Non-Education (CTP)
          </label>
          <select id="ctp_units_select"
                  class="form-select education-modal-border border-warning">
            <option value="">Select CTP Units</option>
          </select>
        </div>

      </div>

    </div>
  </div>

  <!-- 🔥 FILE UPLOAD (SEPARATE CARD = MAS MALINIS) -->
  <div class="card shadow-sm border-0">
    <div class="card-body p-3">

      <label class="form-label fw-semibold">
        Certificate (PDF)
      </label>

      <div class="education-modal-border-dashed rounded-3 p-3 text-center bg-light"
           style="cursor:pointer"
           onclick="document.getElementById('education_file').click()">

        <i class="fas fa-cloud-upload-alt fa-lg text-muted mb-1"></i>

        <input type="file"
               id="education_file"
               name="education[file]"
               class="d-none"
               accept="application/pdf"
               required>

        <div>
          <span class="btn btn-outline-primary btn-sm">
            Choose File
          </span>
        </div>

        <small class="text-muted d-block mt-1">
          PDF only • Max 10MB
        </small>

        <div id="education_file_name"
             class="fw-semibold text-muted mt-1">
          No file chosen
        </div>

      </div>

    </div>
  
        </div>

      </div>
    </div>
  </div>

  <!-- Modal Footer -->
  <div class="modal-footer">
    <button type="button"
            class="btn btn-success"
            id="saveEducation">
      <i class="bi bi-check-circle me-1"></i>
      Save Education
    </button>

    <button type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal">
      Cancel
    </button>
  </div>

</div>
  </div>
</div>