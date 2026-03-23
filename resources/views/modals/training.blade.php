<!-- TRAINING MODAL -->
<div class="modal fade" id="trainingModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h6 class="modal-title fw-bold">
          <i class="fas fa-chalkboard-teacher me-2"></i>Training / Seminars Attended
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4">
        <div class="row g-4">

          <!-- ================= LEFT SIDE ================= -->
          <!-- RUBRICS + SUMMARY (30%) -->
          <div class="col-md-4">
            <div class="training-summary-sticky">

              <!-- TRAINING RUBRICS -->
              <div class="card border-0 shadow-sm mb-3 border-light-green">
                <div class="card-header bg-light-green">
                  <h6 class="mb-0 fw-semibold text-green">
                    <i class="fas fa-list-check me-2"></i>Training Rubrics
                  </h6>
                </div>
                <div class="card-body small training-card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                      <strong>Face-to-Face Training</strong><br>
                      8 hours per day
                    </li>
                    <li class="list-group-item">
                      <strong>Online / Virtual Training</strong><br>
                      3 hours per day (As per DepEd guidelines and legal basis for online training)
                    </li>
                    <li class="list-group-item text-muted">
                      Points are based on total accumulated hours
                    </li>
                  </ul>
                </div>
              </div>

              <!-- TRAINING SUMMARY -->
              <div id="modal_training_summary">
                <div class="alert bg-light-green border-light-green mb-0 p-3">
                  <strong>Training Summary</strong><br>
                  Required Hours: <span id="required_hours_display">0</span> hours<br>
                  Current Total: <span id="total_training_hours">0</span> hours<br>
                  Status: <span class="text-muted">No trainings added</span><br>
                  Score: <span class="fw-bold">Waiting for input(0-10 points)</span>
                </div>
              </div>

            </div>
          </div>

          <!-- ================= RIGHT SIDE ================= -->
          <!-- TRAINING FORM (70%) -->
          <div class="col-md-8">

            <div id="trainingContainer">
              <!-- JS will inject training items here -->
            </div>

            <!-- ADD TRAINING BUTTON -->
            <div class="mb-3 mt-2">
              <button type="button" class="btn btn-outline-primary btn-sm" id="addTraining">
                ➕ Add Another Training
              </button>
            </div>

          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer d-flex justify-content-end">
        <button type="button" class="btn btn-success" id="saveTraining">
          <i class="bi bi-check-circle me-1"></i> Save Training
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
      </div>

    </div>
  </div>
</div>