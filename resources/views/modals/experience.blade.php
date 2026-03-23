<!-- EXPERIENCE MODAL -->
<div class="modal fade" id="experienceModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h6 class="modal-title fw-bold">Add Work Experience</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-0 d-flex">

        <!-- LEFT SIDE: RUBRICS + SUMMARY (30%) -->
        <div class="col-md-4 p-4 border-end" style="flex: 0 0 30%; max-width: 30%;">
          <div class="experience-summary-sticky">

            <!-- EXPERIENCE RUBRICS -->
            <div class="card border-0 shadow-sm mb-3 border-light-green">
              <div class="card-header bg-light-green">
                <h6 class="mb-0 fw-semibold text-green">
                  <i class="fas fa-list-check me-2"></i>Experience Rubrics
                </h6>
              </div>
              <div class="card-body small">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    <strong>1-5 years</strong><br>
                    Level 1-10
                  </li>
                  <li class="list-group-item">
                    <strong>6-10 years</strong><br>
                    Level 11-20
                  </li>
                  <li class="list-group-item">
                    <strong>11-15 years</strong><br>
                    Level 21-32
                  </li>
                </ul>
              </div>
            </div>

            <!-- EXPERIENCE SUMMARY -->
            <div id="experience_summary_modal">
              <div class="alert bg-light-green border-light-green mb-0 p-3">
                <strong>Experience Summary</strong><br>
                Required Years: <span id="required_exp_years_display">0</span><br>
                Current Total: <span id="total_exp_years_display">0</span><br>
                Status: <span class="text-muted">No experiences added</span><br>
                Score: <span id="qsPoints" class="fw-bold text-muted">Waiting for input(0-10 points)</span>
              </div>
            </div>

          </div>
        </div>

        <!-- RIGHT SIDE: FORM (70%) SCROLLABLE -->
        <div class="col-md-8 p-4" style="flex: 1; max-height: 70vh; overflow-y: auto;">

          <!-- EXPERIENCE CONTAINER -->
          <div id="experienceContainer" class="mb-3 row g-3"></div>

          <!-- ADD EXPERIENCE BUTTON -->
          <div class="mb-3">
            <button type="button" class="btn btn-outline-primary btn-sm" id="addExperience">
              ➕ Add Another Experience
            </button>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="saveExperienceBtn">
          <i class="bi bi-check-circle me-1"></i> Save Experience
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>