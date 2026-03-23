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
      <div class="col-md-4 d-flex">
        <div class="education-rubrics-sticky w-100">

          <!-- RUBRICS CARD -->
          <div class="card border-0 shadow-sm mb-3 border-light-green flex-fill">
            <div class="card-header bg-light-green">
              <h6 class="mb-0 fw-semibold text-green">
                <i class="fas fa-list-check me-2"></i>Education Rubrics
              </h6>
            </div>
            <div class="card-body small">
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <strong>Bachelor’s Degree</strong><br>
                  Required for Teacher I–V
                </li>
                <li class="list-group-item">
                  <strong>Master’s Degree Units</strong><br>
                  Required for Teacher VI–VII
                </li>
                <li class="list-group-item">
                  <strong>Master’s Degree</strong><br>
                  Required for Master Teacher
                </li>
                <li class="list-group-item text-muted">
                  Points are based on excess units earned
                </li>
              </ul>
            </div>
          </div>

          <!-- SUMMARY CARD -->
          <div class="card border-0 shadow-sm border-light-green">
            <div class="card-body bg-light-green">
              <strong>Education Summary</strong><br>
              Degree: <span id="edu_degree_display">—</span><br>
              Education Level: <span id="edu_level_display">—</span><br>
              Status: <span id="edu_status_display" class="text-muted">Waiting..</span><br>
              Score: <span id="edu_points_display" class="fw-bold text-muted">Waiting for input(0-10 points)</span>
            </div>
          </div>

        </div>
      </div>

      <!-- ================= RIGHT SIDE ================= -->
      <!-- EDUCATION FORM -->
      <div class="col-md-8">

        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-light py-3">
            <h6 class="mb-0 fw-semibold text-primary">
              Education Information
            </h6>
          </div>

          <div class="card-body p-4">
            <div class="row g-4">

              <!-- DEGREE -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">
                  <i class="fas fa-book me-2 text-primary"></i>
                  Degree
                </label>
                <input type="text"
                       id="education_name"
                       name="education[degree]"
                       class="form-control education-modal-border"
                       placeholder="e.g. Bachelor of Secondary Education"
                       required>
              </div>

              <!-- SCHOOL GRADUATED -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">
                  <i class="fas fa-school me-2 text-primary"></i>
                  School Graduated
                </label>
                <input type="text"
                       id="education_school"
                       name="education[school]"
                       class="form-control education-modal-border"
                       placeholder="e.g. University of Manila"
                       required>
              </div>

              <!-- DATE GRADUATED -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">
                  <i class="fas fa-calendar-alt me-2 text-primary"></i>
                  Date Graduated
                </label>
                <input type="date"
                       id="education_date"
                       name="education[date_graduated]"
                       class="form-control education-modal-border"
                       required>
              </div>

              <!-- EDUCATION LEVEL -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">
                  <i class="fas fa-certificate me-2 text-primary"></i>
                  Highest Educational Attainment
                </label>
                <select id="education_units_select"
                        name="education[level]"
                        class="form-select education-modal-border">
                  <option value="">Select Education Level</option>
                </select>
              </div>

              <!-- CERTIFICATE UPLOAD -->
              <div class="col-md-12">
                <label class="form-label fw-semibold">
                  <i class="fas fa-file-pdf me-2 text-danger"></i>
                  Certificate (PDF)
                </label>

                <div class="education-modal-border-dashed rounded-3 p-4 text-center bg-light"
                     style="cursor:pointer"
                     onclick="document.getElementById('education_file').click()">

                  <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>

                 <input type="file"
                    id="education_file"
                    name="education[file]"
                    class="d-none"
                    accept="application/pdf"
                    required>

                  <span class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-upload me-2"></i>
                    Choose PDF File
                  </span>

                  <div class="education-modal-form-text mt-2">
                    Max file size: 10MB (PDF only)
                  </div>

                  <div id="education_file_name"
                       class="fw-semibold text-muted mt-2">
                    No file chosen
                  </div>
                </div>
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