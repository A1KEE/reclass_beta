let trainingIndex = 1;
let requiredTrainingHours = window.requiredTrainingHours || 0;
let requiredTrainingLevel = window.requiredTrainingLevel || 0;


// ==========================
// TRAINING LEVEL TABLE MAPPING (from Table 2.b)
// ==========================
const trainingLevels = [
    { from: 0, to: 8, level: 1 },
    { from: 8, to: 16, level: 2 },
    { from: 16, to: 24, level: 3 },
    { from: 24, to: 32, level: 4 },
    { from: 32, to: 40, level: 5 },
    { from: 40, to: 48, level: 6 },
    { from: 48, to: 56, level: 7 },
    { from: 56, to: 64, level: 8 },
    { from: 64, to: 72, level: 9 },
    { from: 72, to: 80, level: 10 },
    { from: 80, to: 88, level: 11 },
    { from: 88, to: 96, level: 12 },
    { from: 96, to: 104, level: 13 },
    { from: 104, to: 112, level: 14 },
    { from: 112, to: 120, level: 15 },
    { from: 120, to: 128, level: 16 },
    { from: 128, to: 136, level: 17 },
    { from: 136, to: 144, level: 18 },
    { from: 144, to: 152, level: 19 },
    { from: 152, to: 160, level: 20 },
    { from: 160, to: 168, level: 21 },
    { from: 168, to: 176, level: 22 },
    { from: 176, to: 184, level: 23 },
    { from: 184, to: 192, level: 24 },
    { from: 192, to: 200, level: 25 },
    { from: 200, to: 208, level: 26 },
    { from: 208, to: 216, level: 27 },
    { from: 216, to: 224, level: 28 },
    { from: 224, to: 232, level: 29 },
    { from: 232, to: 240, level: 30 },
    { from: 240, to: Infinity, level: 31 }
];

// ==========================
// AUTO DETECT NON-TEACHING TRAININGS
// ==========================
const nonTeachingKeywords = [
    "administrative", "accounting", "finance", "management",
    "ict", "computer", "leadership", "seminar", "orientation", "workshop"

];
// ✅ GLOBAL FUNCTION (TOP NG FILE)
function setPointsAndRemarks(points, remarkText, pointsInput, remarksInput) {

    $(pointsInput).val(points);

    let cleanRemark = '';

    if (remarkText.includes('NOT MET')) {
        cleanRemark = 'NOT MET';
    } else if (remarkText.includes('MET')) {
        cleanRemark = 'MET';
    }

    $(remarksInput).val(cleanRemark);
}

function isTeachingRelevant(title) {
    if (!title) return false;
    title = title.toLowerCase();
    for (let kw of nonTeachingKeywords) {
        if (title.includes(kw)) return false; // may keyword → NOT teaching-relevant
    }
    return true; // walang keyword → teaching-relevant
}
// ==========================
// GET QUALIFICATION LEVEL FROM HOURS
// ==========================
function getQualificationLevel(hours) {
    hours = parseFloat(hours);
    if (isNaN(hours) || hours < 0) return 0;
    
    const found = trainingLevels.find(level => hours >= level.from && hours < level.to);
    return found ? found.level : 31;
}

// ==========================
// FIXED POINTS SYSTEM FOR TRAINING (2,4,6,8,10 RULE)
// ==========================
function getTrainingPoints(increment) {
    if (increment >= 10) return 10;
    if (increment >= 8) return 8;
    if (increment >= 6) return 6;
    if (increment >= 4) return 4;
    if (increment >= 2) return 2;
    return 0;
}

// ==========================
// UPDATE TRAINING STATUS (FIXED)
// ==========================
function updateTrainingStatus(updateTable = false) {
    let totalTeachingHours = 0;
    let trainingCount = 0;

    $('.training-item').each(function () {
        const hours = parseFloat($(this).find('input[name*="[hours]"]').val()) || 0;
        const title = $(this).find('input[name*="[title]"]').val() || '';

        if (hours > 0) trainingCount++;

        if (isTeachingRelevant(title)) {
            totalTeachingHours += hours;
        }
    });

    let remarkHTML = '';

    if (!requiredTrainingHours || requiredTrainingHours <= 0) {
        remarkHTML = '<span class="text-muted">Waiting for the QS</span>';
    }
    else if (trainingCount === 0) {
        remarkHTML = '<span class="text-muted">Waiting for the QS</span>';
    }
    else if (totalTeachingHours >= requiredTrainingHours) {
        remarkHTML = '<span class="text-success fw-bold">MET</span>';
    }
    else {
        remarkHTML = '<span class="text-danger fw-bold">NOT MET</span>';
    }

    // ✅ IMPORTANT FIX
    if (updateTable) {
        $('#training_remark').html(remarkHTML);
    }

    // ======================
    // Modal summary
    // ======================
    const modalList = [];
    $('.training-item').each(function () {
        const hours = parseFloat($(this).find('input[name*="[hours]"]').val()) || 0;
        const title = $(this).find('input[name*="[title]"]').val() || '';

        if (isTeachingRelevant(title) && hours > 0) {
            modalList.push(`${title} (${hours} hrs)`);
        }
    });

    if (requiredTrainingHours && requiredTrainingHours > 0) {
        let statusText = '<span class="text-muted">Waiting...</span>';

        if (trainingCount > 0) {
            statusText = totalTeachingHours >= requiredTrainingHours
                ? '<span class="text-success fw-bold">MET</span>'
                : '<span class="text-danger fw-bold">NOT MET</span>';
        }

        $('#modal_training_summary').html(`
            <div class="alert alert-info p-2">
                <strong>Training Summary</strong><br>
                Required Hours: ${requiredTrainingHours} hours<br>
                Current Total (Teaching Only): ${totalTeachingHours} hours<br>
                Status: ${statusText}
                ${modalList.length > 0 ? '<hr>' + modalList.join('<br>') : '<br><small class="text-muted">No teaching-relevant training added</small>'}
            </div>
        `);
    }
}

// ==========================
// LOAD TRAINING QS REQUIREMENT (FOR MODAL ONLY)
// ==========================
function loadTrainingQS() {
    const position = $('#position_applied').val();
    const level = $('#school_id').find(':selected').data('level');

    if (!position || !level) {
        $('#modal_training_summary').html(`
            <div class="alert alert-warning p-2">
                <strong>No Position/Level Selected</strong><br>
                <small>Please select a position and school first</small>
            </div>
        `);
        return;
    }

    if (window.qsConfig && window.qsConfig[level] && window.qsConfig[level][position]) {
        const positionConfig = window.qsConfig[level][position];

        // SET GLOBAL REQUIRED VALUES HERE
        requiredTrainingHours = parseFloat(positionConfig.training_hours) || 0;
        requiredTrainingLevel = getQualificationLevel(requiredTrainingHours);

        updateTrainingStatus(false);

        return;
    }

    $('#modal_training_summary').html(`
        <div class="alert alert-warning p-2">
            <strong>No Training Requirement Found</strong>
        </div>
    `);
}

// ==========================
// RESET TRAINING (FIXED - "No training added" dapat)
// ==========================
function resetTraining() {
    console.log('Resetting training...');
    
    $('#trainingContainer').empty();
    $('#training_summary').html('<span class="text-muted">No training added</span>'); // "No training added"
    
    // ITO ANG TAMA - laging "Waiting for the QS" pag walang laman
    $('#training_remark').html('<span class="text-muted">Waiting...</span>');
    
    $('input[name="comparative[training]"]').val(0);
    $('#total_training_hours').text('0');
    
    const position = $('#position_applied').val();
    const level = $('#school_id').find(':selected').data('level');
    
    if (position && level && window.qsConfig && window.qsConfig[level] && window.qsConfig[level][position]) {
        const trainingHours = parseFloat(window.qsConfig[level][position].training_hours) || 0;
        const trainingLevel = getQualificationLevel(trainingHours);
        
        console.log('resetTraining - Setting required hours:', trainingHours);
        requiredTrainingHours = trainingHours;
        requiredTrainingLevel = trainingLevel;
        
        $('#modal_training_summary').html(`
            <div class="alert alert-info p-2">
                <strong>Training Requirements Loaded</strong><br>
                Required Hours: ${trainingHours} hours (Level ${trainingLevel})
            </div>
        `);
    } else {
        requiredTrainingHours = 0;
        requiredTrainingLevel = 0;
        $('#modal_training_summary').html('<div class="alert alert-warning p-2">Waiting for QS requirements (select position and school first)</div>');
    }
    
    console.log('resetTraining - After reset:', { requiredTrainingHours, requiredTrainingLevel });
}

// ==========================
// COMPUTE TOTAL HOURS + LEVEL + POINTS (FIXED - "No training added" pag walang laman)
// ==========================
    function computeTotalHours() {
    let totalTeachingHours = 0;
    let tableList = [];
    let modalList = [];

    $('.training-item').each(function () {
        const hours = parseFloat($(this).find('input[name*="[hours]"]').val()) || 0;
        const title = $(this).find('input[name*="[title]"]').val() || '';

        if (hours > 0) {
            if (isTeachingRelevant(title)) {
                totalTeachingHours += hours;
                tableList.push(`${title} (${hours} hrs)`); // teaching goes to table
                modalList.push(`${title} (${hours} hrs)`); // teaching goes to modal
            } else {
                // non-teaching still shown in table but marked
                tableList.push(`${title} (${hours} hrs) <small class="text-muted">(Not Teaching Relevant)</small>`);
            }
        }
    });

    // ===== Table summary =====
    const tableSummary = $('#training_summary');
    if (tableList.length > 0) {
        tableSummary.html(tableList.join('<br>'));
    } else {
        tableSummary.html('<span class="text-muted">No training added</span>');
    }

    // ===== Total hours & points =====
    $('#total_training_hours').text(totalTeachingHours);
    const applicantLevel = getQualificationLevel(totalTeachingHours);
    const increments = Math.max(0, applicantLevel - requiredTrainingLevel);
    const trainingPoints = getTrainingPoints(increments);
    $('input[name="comparative[training]"]').val(trainingPoints);

    // ===== Update remarks & modal =====
    updateTrainingStatus(false);

    // ===== Modal summary (teaching only) =====
    if (requiredTrainingHours && requiredTrainingHours > 0) {
        $('#modal_training_summary').html(`
            <div class="alert alert-info p-2">
                <strong>Training Summary</strong><br>
                Required Hours: ${requiredTrainingHours} hours<br>
                Current Total (Teaching Only): ${totalTeachingHours} hours<br>
                Status: <span class="text-${totalTeachingHours >= requiredTrainingHours ? 'success' : 'danger'} fw-bold">
                    ${totalTeachingHours >= requiredTrainingHours ? 'MET' : 'NOT MET'}
                </span>
                ${modalList.length > 0 ? '<hr>' + modalList.join('<br>') : '<br><small class="text-muted">No teaching-relevant training added</small>'}
            </div>
        `);
    }
}
// ==========================
// ADD TRAINING ROW
// ==========================
$('#addTraining').on('click', function () {
    let currentCount = $('#trainingContainer .training-item').length;
    let displayNumber = currentCount + 1;
    
    let html = `
    <div class="training-item card shadow-sm border-0 mb-4 overflow-hidden position-relative">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <div class="d-flex align-items-center">
                <span class="badge bg-primary me-2">Training #${displayNumber}</span>
            </div>
            <button type="button" class="btn btn-sm btn-danger remove-training"
                    style="width: 30px; height: 30px; padding: 0; border-radius: 50%;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-graduation-cap me-2 text-primary"></i>Training Title
                        </label>
                        <input type="text" name="trainings[${trainingIndex}][title]" 
                               class="form-control border-primary-subtle" 
                               placeholder="Enter training title" required>
                        <div class="form-text">Enter the complete title of the training program</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>Training Type
                        </label>
                        <select name="trainings[${trainingIndex}][type]" 
                                class="form-select border-primary-subtle training_type" required>
                            <option value="" disabled selected>Select training type</option>
                            <option value="Face-to-Face">Face-to-Face</option>
                            <option value="Online">Online</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>Start Date
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-primary-subtle">
                                <i class="fas fa-clock text-primary"></i>
                            </span>
                            <input type="date" name="trainings[${trainingIndex}][start_date]" 
                                   class="form-control border-primary-subtle training_date" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-calendar-check me-2 text-primary"></i>End Date
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-primary-subtle">
                                <i class="fas fa-clock text-primary"></i>
                            </span>
                            <input type="date" name="trainings[${trainingIndex}][end_date]" 
                                   class="form-control border-primary-subtle training_date" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="alert alert-info bg-primary bg-opacity-10 border-primary border-opacity-25">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold mb-1">
                                    <i class="fas fa-clock me-2 text-primary"></i>No. of Hours
                                </label>
                                <div class="form-text">Automatically computed from start and end dates</div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-primary">
                                        <i class="fas fa-hourglass-half text-primary"></i>
                                    </span>
                                    <input type="number" name="trainings[${trainingIndex}][hours]" 
                                           class="form-control border-primary fw-bold text-primary training_hours" 
                                           readonly placeholder="0">
                                    <span class="input-group-text bg-white border-primary">hours</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-file-pdf me-2 text-danger"></i>Certificate (PDF)
                        </label>
                        <div class="file-upload-area border-dashed rounded-3 p-4 text-center bg-light">
                            <div class="mb-3">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted"></i>
                            </div>
                            <input type="file" name="trainings[${trainingIndex}][file]" 
                                   class="form-control training_file d-none" 
                               accept="application/pdf" required>
                            <button type="button" class="btn btn-outline-primary btn-sm choose-file-btn">
                                <i class="fas fa-upload me-2"></i>Choose PDF File
                            </button>
                            <div class="form-text mt-2">Maximum file size: 5MB. Only PDF files are accepted.</div>
                            <div class="file-name mt-2 fw-semibold text-success"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`;

    $('#trainingContainer').append(html);
    
    $(document).on('click', '.choose-file-btn', function() {
        $(this).closest('.file-upload-area').find('.training_file').click();
    });
    
    $(document).on('change', '.training_file', function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).closest('.file-upload-area').find('.file-name').text(fileName || 'No file chosen');
    });
    
    trainingIndex++;
});

// ==========================
// REMOVE TRAINING ROW (FIXED)
// ==========================
$(document).on('click', '.remove-training', function() {
    $(this).closest('.training-item').remove();
    renumberTrainingItems();
    
    // ===== FIXED: I-compute ulit para ma-update ang summary =====
    computeTotalHours();
    
    // ===== FIXED: Check kung walang natirang training =====
    if ($('.training-item').length === 0) {
        $('#training_summary').html('<span class="text-muted">No training added</span>');
        $('#total_training_hours').text('0');
        $('input[name="comparative[training]"]').val(0);
    }
});

function renumberTrainingItems() {
    $('.training-item').each(function(index) {
        $(this).find('.badge').text('Training #' + (index + 1));
    });
}

// ==========================
// AUTO HOURS BASED ON TYPE AND DATE
// ==========================
$(document).on('change', '.training_type, .training_date', function () {
    const container = $(this).closest('.training-item');
    const type = container.find('.training_type').val();
    const start = container.find('input[name*="[start_date]"]').val();
    const end   = container.find('input[name*="[end_date]"]').val();

    if (!type || !start || !end) return;

    const startDate = new Date(start);
    const endDate   = new Date(end);
    const dayCount = Math.floor((endDate - startDate) / (1000*60*60*24)) + 1;

    let hours = 0;
    if (type === 'Face-to-Face') hours = dayCount * 8;
    else if (type === 'Online') hours = dayCount * 3;

    container.find('.training_hours').val(hours);
    computeTotalHours(); // Use computeTotalHours para ma-update din ang summary
});

// ==========================
// FILE SELECT TOAST
// ==========================
$(document).on('change', '.training_file', function () {
    const file = this.files[0];
    if (!file) return;

    Swal.fire({
        icon: 'success',
        title: 'Certificate Selected',
        text: file.name,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    });
});

// ==========================
// SAVE TRAINING (FIXED)
// ==========================
$('#saveTraining').on('click', function () {

    let trainingList = [];
    let totalHours = 0;
    let missingFile = false;

    $('.training-item').each(function () {
        const title = $(this).find('input[name*="[title]"]').val();
        const hours = parseFloat($(this).find('input[name*="[hours]"]').val());
        const file  = $(this).find('input[type="file"]')[0].files[0];
        const type = $(this).find('.training_type').val();
        const start = $(this).find('input[name*="[start_date]"]').val();
        const end = $(this).find('input[name*="[end_date]"]').val();

        if (!file) missingFile = true;

        if (title && hours && file) {
            const relevant = isTeachingRelevant(title);
            if (relevant) totalHours += hours;

            trainingList.push({
                title: title,
                hours: hours,
                relevant: relevant,
                type: type,
                start: start,
                end: end
            });
        }
    });

    if (trainingList.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Training Added',
            text: 'Please add at least one training.'
        });
        return;
    }

    if (missingFile) {
        Swal.fire({
            icon: 'error',
            title: 'Missing Certificate',
            text: 'Please upload PDF certificates for all trainings.'
        });
        return;
    }

    // =========================
    // COMPUTE POINTS
    // =========================
    const applicantLevel = getQualificationLevel(totalHours);
    const increments = Math.max(0, applicantLevel - requiredTrainingLevel);
    const trainingPoints = getTrainingPoints(increments);

    // =========================
    // SUMMARY
    // =========================
    let summary = trainingList.map(t => 
        `${t.title} (${t.hours} hrs)` +
        (t.relevant ? '' : ' <small class="text-muted">(Not Teaching Relevant)</small>')
    ).join('<br>');

    $('#training_summary').html(summary);
    $('input[name="comparative[training]"]').val(trainingPoints);
    $('#total_training_hours').text(totalHours);

    // =========================
    // STATUS (IMPORTANT)
    // =========================
    updateTrainingStatus(true);

    // 👉 GET REMARK FROM UI
    let trainingRemark = $('#training_remark').text();

    // =========================
    // SAVE TO HIDDEN INPUTS ✅
    // =========================
    setPointsAndRemarks(
        trainingPoints,
        trainingRemark,
        '#training_points',
        '#remarksTraining'
    );

    $('#trainingModal').modal('hide');

    Swal.fire({
        icon: 'success',
        title: 'Training Saved',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
});

// ==========================
// POSITION/SCHOOL CHANGE (UPDATED)
// ==========================
$('#position_applied, #school_id').on('change', function() {
    console.log('Position/School changed');
    resetTraining();
    
    const position = $('#position_applied').val();
    const level = $('#school_id').find(':selected').data('level');
    
    if (position && level) {
        loadTrainingQS();
    }
});

// ==========================
// TRAINING MODAL OPEN EVENT
// ==========================
$('#trainingModal').on('show.bs.modal', function() {
    console.log('Training modal opening...');
    loadTrainingQS();
});

// ==========================
// TRAINING MODAL HIDDEN EVENT
// ==========================
$('#trainingModal').on('hidden.bs.modal', function() {
    console.log('Training modal closed');
    
    // I-load ulit ang QS para ma-reset ang modal summary sa simple state
    const position = $('#position_applied').val();
    const level = $('#school_id').find(':selected').data('level');
    
    if (position && level && window.qsConfig && window.qsConfig[level] && window.qsConfig[level][position]) {
        const trainingHours = parseFloat(window.qsConfig[level][position].training_hours) || 0;
        
        $('#modal_training_summary').html(`
            <div class="alert alert-info p-2">
                <strong>Training Summary</strong><br>
                Required Hours: ${trainingHours} hours<br>
                <span class="text-muted">Waiting for the QS</span>
            </div>
        `);
    }
});

// ==========================
// INITIALIZE ON PAGE LOAD (FIXED - gaya sa image)
// ==========================
$(document).ready(function () {
    console.log('Training script initializing...');
    console.log('window.qsConfig:', window.qsConfig ? 'Loaded' : 'Not loaded');
    
    const initialRequiredLevel = getQualificationLevel(requiredTrainingHours);
    requiredTrainingLevel = initialRequiredLevel;
    
    console.log('Initial values:', {
        requiredTrainingHours: requiredTrainingHours,
        requiredTrainingLevel: requiredTrainingLevel
    });

    // FIXED: Initial state - "No training added" sa summary (gaya ng nasa image)
    $('#training_summary').html('<span class="text-muted">No training added</span>');
    
    if (!requiredTrainingHours || requiredTrainingHours <= 0) {
        $('#training_remark').html('<span class="text-muted">Waiting for the QS</span>');
        $('#modal_training_summary').html('<div class="alert alert-warning p-2">Waiting for The QS</div>');
    } else {
        $('#modal_training_summary').html(`
            <div class="alert alert-info p-2">
                <strong>Training Summary</strong><br>
                Required Hours: ${requiredTrainingHours} hours<br>
                Required Level: ${requiredTrainingLevel}<br>
                <span class="text-muted">Open modal to add trainings</span>
            </div>
        `);
        $('#training_remark').html('<span class="text-muted">Waiting for the QS</span>');
    }

    updateTrainingStatus(false);
});