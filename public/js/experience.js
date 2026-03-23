let experienceIndex = 0;
let requiredYears = 0;

// ==========================
// LOAD EXPERIENCE TABLE
// ==========================
function loadExperienceTable() {
    window.experienceIncrementTable = [
        { level: 1, from: 0, to: 0.5 },
        { level: 2, from: 0.5, to: 1 },
        { level: 3, from: 1, to: 1.5 },
        { level: 4, from: 1, to: 2 },
        { level: 5, from: 1, to: 2.5 },
        { level: 6, from: 2, to: 3 },
        { level: 7, from: 2, to: 3.5 },
        { level: 8, from: 3, to: 4 },
        { level: 9, from: 3, to: 4.5 },
        { level: 10, from: 4, to: 5 },
        { level: 11, from: 4, to: 5.5 },
        { level: 12, from: 5, to: 6 },
        { level: 13, from: 5, to: 6.5 },
        { level: 14, from: 6, to: 6.5 },
        { level: 15, from: 6, to: 7 },
        { level: 16, from: 7, to: 7.5 },
        { level: 17, from: 7, to: 8 },
        { level: 18, from: 8, to: 8.5 },
        { level: 19, from: 8, to: 9 },
        { level: 20, from: 9, to: 9.5 },
        { level: 21, from: 9, to: 10 },
        { level: 22, from: 10, to: 10.5 },
        { level: 23, from: 10, to: 11 },
        { level: 24, from: 11, to: 11.5 },
        { level: 25, from: 11, to: 12 },
        { level: 26, from: 12, to: 12.5 },
        { level: 27, from: 12, to: 13 },
        { level: 28, from: 13, to: 13.5 },
        { level: 29, from: 13, to: 14 },
        { level: 30, from: 14, to: 14.5 },
        { level: 31, from: 14, to: 15 },
        { level: 32, from: 15, to: 999 }
    ];
}

function formatYearsMonths(decimalYears) {
    const years = Math.floor(decimalYears);
    const months = Math.round((decimalYears - years) * 12);
    return `${years} year${years !== 1 ? 's' : ''}${months > 0 ? ' and ' + months + ' month' + (months !== 1 ? 's' : '') : ''}`;
}

// ==========================
// GET LEVEL FROM YEARS
// ==========================
function getLevelFromYears(years) {
    if (!window.experienceIncrementTable) loadExperienceTable();
    const roundedYears = parseFloat(years.toFixed(2));
    
    for (let i = 0; i < window.experienceIncrementTable.length; i++) {
        const level = window.experienceIncrementTable[i];
        if (roundedYears >= level.from && roundedYears < level.to) {
            return level.level;
        }
    }
    
    if (roundedYears >= 15) return 32;
    return 1;
}

// ==========================
// CALCULATE QS POINTS (2,4,6,8,10 RULE)
// ==========================
function calculateQSPoints(actualLevel, requiredLevel) {
    if (actualLevel <= requiredLevel) {
        return 0;
    }
    
    const levelDifference = actualLevel - requiredLevel;
    let points = 0;
    
    // 2,4,6,8,10 rule
    if (levelDifference >= 10) {
        points = 10;
    } else if (levelDifference >= 8) {
        points = 8;
    } else if (levelDifference >= 6) {
        points = 6;
    } else if (levelDifference >= 4) {
        points = 4;
    } else if (levelDifference >= 2) {
        points = 2;
    }
    
    return points;
}

// ==========================
// FETCH QS EXPERIENCE REQUIREMENT (FOR MODAL ONLY)
// ==========================
function loadExperienceQS() {
    // Get values from main page
    const position = $('#position_applied').val();
    const level = getSelectedLevel(); // Use the function from main blade
    
    // Clear muna
    requiredYears = 0;
    
    // Check if may laman ang position at level
    if (!position || !level) {
        $('#expRequirementText').html(`
            <strong>No Position/Level Selected</strong><br>
            <small>Please select a position and school first</small>
        `);
        $('#saveExperienceBtn').prop('disabled', true);
        return;
    }
    
    // Check from qsConfig
    if (window.qsConfig && window.qsConfig[level] && window.qsConfig[level][position]) {
        const positionConfig = window.qsConfig[level][position];
        requiredYears = parseFloat(positionConfig.experience_years) || 0;
        const requiredLevel = getLevelFromYears(requiredYears);
        
        // Gamitin ang 'experience' text hindi lang years number
        const experienceText = positionConfig.experience || `${requiredYears} year(s)`;
         
        $('#saveExperienceBtn').prop('disabled', false);
        return;
    }
    
    // Fallback - walang configuration
    $('#expRequirementText').html(`
        <strong>${position}</strong><br>
        <small class="text-muted">No experience requirement found</small>
    `);
    $('#saveExperienceBtn').prop('disabled', true);
}


$('#addExperience').on('click', function() {
    const html = `
    <div class="col-12">
      <div class="experience-item card shadow-sm p-3 position-relative mb-3">

        <button type="button"
                class="btn btn-sm btn-outline-danger remove-experience
                position-absolute top-0 end-0 m-2">✖</button>

        <!-- SCHOOL TYPE + NAME (2 columns) -->
        <div class="row g-2 mb-2">
          <div class="col-md-6">
            <label class="fw-bold">School Type</label>
            <select name="experiences[${experienceIndex}][school_type]"
                    class="form-select exp_school_type" required>
              <option value="">Select School Type</option>
              <option value="Public">Public School</option>
              <option value="Private">Private School</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="fw-bold">School Name</label>
            <input type="text"
                   name="experiences[${experienceIndex}][school]"
                   class="form-control exp_school"
                   placeholder="Enter School Name"
                   required>
          </div>
        </div>

        <!-- POSITION -->
        <div class="mb-2 position-wrapper">
          <label class="fw-bold">Position</label>
          <select name="experiences[${experienceIndex}][position]"
                  class="form-select exp_position" required>
            <option value="">Select Position</option>
            <option>Teacher I</option>
            <option>Teacher II</option>
            <option>Teacher III</option>
            <option>Teacher IV</option>
            <option>Teacher V</option>
            <option>Teacher VI</option>
            <option>Teacher VII</option>
            <option>Master Teacher I</option>
            <option>Master Teacher II</option>
            <option>Master Teacher III</option>
            <option>Master Teacher IV</option>
            <option>Master Teacher V</option>
          </select>
        </div>

        <!-- START + END DATES (2 columns) -->
        <div class="row g-2 mb-2">
          <div class="col-md-6">
            <label class="fw-bold">Start Date</label>
            <input type="date"
                   name="experiences[${experienceIndex}][start]"
                   class="form-control exp_start"
                   required>
          </div>
          <div class="col-md-6">
            <label class="fw-bold">End Date</label>
            <input type="date"
                   name="experiences[${experienceIndex}][end]"
                   class="form-control exp_end"
                   required>
          </div>
        </div>

        <!-- FILE UPLOAD -->
       <div class="col-md-12">
  <div class="form-group">
    <label class="form-label fw-semibold mb-2">
      <i class="fas fa-file-pdf me-2 text-danger"></i>Certificate (PDF)
    </label>

    <div class="file-upload-area border-dashed rounded-3 p-4 text-center bg-light">
      
      <div class="mb-3">
        <i class="fas fa-cloud-upload-alt fa-2x text-muted"></i>
      </div>

      <input type="file"
             name="experiences[${experienceIndex}][file]"
             class="form-control exp_file d-none"
             accept="application/pdf"
             required>

      <button type="button" class="btn btn-outline-primary btn-sm choose-exp-file-btn">
        <i class="fas fa-upload me-2"></i>Choose PDF File
      </button>

      <div class="form-text mt-2">
        Maximum file size: 5MB. Only PDF files are accepted.
      </div>

      <div class="file-name mt-2 fw-semibold text-success"></div>

    </div>
  </div>
</div>
    `;

    $('#experienceContainer').append(html);
    experienceIndex++;
});

// CLICK BUTTON → OPEN FILE
$(document).on('click', '.choose-exp-file-btn', function() {
    $(this).closest('.file-upload-area').find('.exp_file').click();
});

// DISPLAY FILE NAME
$(document).on('change', '.exp_file', function() {
    const fileName = this.files[0]?.name || '';
    $(this).closest('.file-upload-area').find('.file-name').text(fileName);
});

// ==========================
// REMOVE EXPERIENCE ROW
// ==========================
$(document).on('click', '.remove-experience', function() {
    // Remove the whole card instead of a column
    $(this).closest('.experience-item').remove();
    computeExperienceTotal();
    updateExperienceModalSummary();
});

function resetExperience(){

    $('#experienceContainer').html('');
    $('#experience_summary').html('<span class="text-muted">No experience added.</span>');

    $('#experience_summary_modal').html('');
    
    $('input[name="comparative[experience]"]').val(0);

    $('#experience_remark').html('<span class="text-muted">Waiting for The QS</span>');

    experienceIndex = 0;
}
// ==========================
// COMPUTE TOTAL EXPERIENCE
// ==========================
function computeExperienceTotal() {
    let totalYears = 0;
    let allItemsValid = true;
    
    $('.experience-item').each(function() {
        const start = new Date($(this).find('.exp_start').val());
        const end = new Date($(this).find('.exp_end').val());
        
        if (!isNaN(start) && !isNaN(end) && end >= start) {
            let years = end.getFullYear() - start.getFullYear();
            let months = end.getMonth() - start.getMonth();
            let days = end.getDate() - start.getDate();
            
            if (days < 0) {
                months--;
                days += new Date(end.getFullYear(), end.getMonth(), 0).getDate();
            }
            
            if (months < 0) {
                years--;
                months += 12;
            }
            
            // Convert to decimal years
            const decimalYears = years + (months / 12) + (days / 365);
            totalYears += decimalYears;
        } else {
            allItemsValid = false;
        }
    });
    
    // Get levels
    const actualLevel = getLevelFromYears(totalYears);
    const requiredLevel = getLevelFromYears(requiredYears);
    
    // Calculate QS Points
    const qsPoints = calculateQSPoints(actualLevel, requiredLevel);
    const levelDifference = actualLevel - requiredLevel;
    
    return {
        totalYears: parseFloat(totalYears.toFixed(2)),
        actualLevel: actualLevel,
        requiredLevel: requiredLevel,
        levelDifference: levelDifference,
        qsPoints: qsPoints,
        isValid: allItemsValid
    };
}
// ==========================
// UPDATE EXPERIENCE MODAL SUMMARY WITH LIVE DATE VALIDATION
// ==========================
function updateExperienceModalSummary() {
    const modalSummary = $('#experience_summary_modal');
    const items = $('.experience-item');

    // ==========================
    // DEFAULT (NO EXPERIENCE)
    // ==========================
    if (items.length === 0) {
        modalSummary.html(`
            <div class="alert bg-light-green border-light-green mb-0 p-3">
                <strong>Experience Summary</strong><br>
                Required Years: ${requiredYears} year/s <br>
                Current Total: 0 <br>
                Status: <span class="text-muted">No experiences added</span><br>
                Score: <span class="fw-bold text-muted">Waiting for input (0–10 points)</span>
            </div>
        `);

        $('input[name="comparative[experience]"]').val(0);
        $('#experience_remark').html('<span class="text-muted">Waiting for The QS</span>');
        return;
    }

    let allFilled = true;
    let dateError = false;

    items.each(function() {
        const start = $(this).find('.exp_start').val();
        const end = $(this).find('.exp_end').val();
        const pos = $(this).find('.exp_position').val();

        if (!start || !end || !pos) {
            allFilled = false;
        }

        if (start && end && new Date(end) < new Date(start)) {
            dateError = true;
        }
    });

    // ==========================
    // INCOMPLETE
    // ==========================
    if (!allFilled) {
        modalSummary.html(`
            <div class="alert alert-info p-2">
                <strong>Experience Summary</strong><br>
                Required Years: ${requiredYears} year/s <br>
                Current Total: 0 <br>
                Status: <span class="text-muted">Waiting... (Incomplete Fields)</span><br>
                Score: <span class="fw-bold text-muted">Waiting for input (0–10 points)</span>
            </div>
        `);

        $('input[name="comparative[experience]"]').val(0);
        return;
    }

    // ==========================
    // INVALID DATE
    // ==========================
    if (dateError) {
        modalSummary.html(`
            <div class="alert alert-danger p-2">
                <strong>Experience Summary</strong><br>
                Status: <span class="text-danger fw-bold">Invalid Dates</span>
            </div>
        `);

        $('input[name="comparative[experience]"]').val(0);
        return;
    }

    // ==========================
    // COMPUTE
    // ==========================
    const result = computeExperienceTotal();

    const status = result.totalYears >= requiredYears
        ? '<span class="text-success fw-bold">MET</span>'
        : '<span class="text-danger fw-bold">NOT MET</span>';

    const hasInput = items.length > 0;

    let pointsDisplay = hasInput
        ? `<span class="fw-bold text-primary">${result.qsPoints} points</span>`
        : `<span class="fw-bold text-muted">Waiting for input (0–10 points)</span>`;

    // ==========================
    // FINAL UI (MATCH TRAINING)
    // ==========================
    modalSummary.html(`
        <div class="alert bg-light-green border-light-green mb-0 p-3">
            <strong>Experience Summary</strong><br>
            Required Years: ${requiredYears} <br>
            Current Total: ${formatYearsMonths(result.totalYears)} <br>
            Status: ${status} <br>
            Score: ${pointsDisplay}
        </div>
    `);

    // ==========================
    // SAVE VALUES
    // ==========================
    $('input[name="comparative[experience]"]').val(result.qsPoints);
    $('#experience_points').val(result.qsPoints);

    $('#remarksExperience').val(
        result.totalYears >= requiredYears ? 'MET' : 'NOT MET'
    );

    $('#experience_remark').html(status);
}

// ==========================
// SAVE EXPERIENCE
// ==========================
$('#saveExperienceBtn').on('click', function() {

    const result = computeExperienceTotal();

    let incomplete = false;
    let missingFile = false;

    $('.experience-item').each(function() {

        const pos = $(this).find('.exp_position').val();
        const start = $(this).find('.exp_start').val();
        const end = $(this).find('.exp_end').val();
        const file = $(this).find('.exp_file')[0]?.files[0];

        // Required fields validation
        if (!pos || !start || !end) {
            incomplete = true;
        }

        // ❗ DATE VALIDATION (ilagay dito)
        if (start && end && new Date(end) < new Date(start)) {

            Swal.fire({
                icon:'error',
                title:'Invalid Dates',
                text:'End date cannot be earlier than start date.'
            });

            incomplete = true;
            return false; // stop loop
        }

        // Certificate validation
        if (!file) {
            missingFile = true;
        }

    });

    if (incomplete) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Data',
            text: 'Please complete all experience fields.'
        });
        return;
    }

    if (missingFile) {
        Swal.fire({
            icon: 'error',
            title: 'Missing Certificate',
            text: 'Please upload the Certificate of Employment / Service Record.'
        });
        return;
    }

    
   // Build summary for main table (POSITION, PERIOD, YEAR only)
        let summary = '';
        let experienceList = [];

        $('.experience-item').each(function() {
            const pos = $(this).find('.exp_position').val();
            const start = $(this).find('.exp_start').val();
            const end = $(this).find('.exp_end').val();
            
            if (pos && start && end) {
                const startDate = new Date(start);
                const endDate = new Date(end);
                
                // Basic difference
                let years = endDate.getFullYear() - startDate.getFullYear();
                let months = endDate.getMonth() - startDate.getMonth();
                let days = endDate.getDate() - startDate.getDate();

                // Adjust if days are negative
                if (days < 0) {
                    months--;
                }

                // Adjust if months are negative
                if (months < 0) {
                    years--;
                    months += 12;
                }

                // Build clean readable text
                let yearText = '';
                let monthText = '';

                if (years > 0) {
                    yearText = `${years} year${years > 1 ? 's' : ''}`;
                }

                if (months > 0) {
                    monthText = `${months} month${months > 1 ? 's' : ''}`;
                }

                let finalText = '';

                if (yearText && monthText) {
                    finalText = `${yearText} and ${monthText}`;
                } else if (yearText) {
                    finalText = yearText;
                } else if (monthText) {
                    finalText = monthText;
                } else {
                    finalText = 'Less than 1 month';
                }

                // Format dates like "Jan 2020"
                const startFormatted = startDate.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                const endFormatted = endDate.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });

                experienceList.push({
                position: pos,
                school: $(this).find('.exp_school').val(),
                period: `${startFormatted} - ${endFormatted}`,
                years: finalText
                });
            }
        });

        // Create simple list summary - WITHOUT POINTS
        if (experienceList.length > 0) {
           summary = experienceList.map(exp => `
                <div class="mb-2">

                <strong>${exp.position}</strong><br>

                <small class="text-muted">${exp.school}</small><br>

                <small>${exp.period} (${exp.years})</small>

                </div>
                `).join('');
        } else {
            summary = '<span class="text-muted">No experience added.</span>';
        }
    
    // Update main table experience summary (list of experiences)
    $('#experience_summary').html(summary || '<span class="text-muted">No experience added.</span>');
    
    // Update comparative points input
    $('input[name="comparative[experience]"]').val(result.qsPoints);
    
    // Update remark in main table
    const remark = $('#experience_remark');
    if (result.totalYears >= requiredYears) {
        remark.html('<span class="text-success fw-bold">MET</span>');
    } else {
        remark.html('<span class="text-danger fw-bold">NOT MET</span>');
    }
    
    // Show success message
    Swal.fire({
        icon: 'success',
        title: 'Experience Saved',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    
    // Hide modal
    setTimeout(() => {
        bootstrap.Modal.getInstance(document.getElementById('experienceModal')).hide();
    }, 3100);
});

// ==========================
// AUTO COMPUTE WHEN DATES CHANGE
// ==========================
$(document).on('change', '.exp_start, .exp_end, .exp_position', updateExperienceModalSummary);
$(document).on('change', '.exp_school_type', function () {

    const container = $(this).closest('.experience-item');
    const type = $(this).val();
    const index = $(this).attr('name').match(/\d+/)[0];

    if (type === 'Public') {

        container.find('.position-wrapper').html(`

        <label class="fw-bold">Position</label>

        <select name="experiences[${index}][position]"
        class="form-control exp_position" required>

        <option value="">Select Position</option>

        <option>Teacher I</option>
        <option>Teacher II</option>
        <option>Teacher III</option>
        <option>Teacher IV</option>
        <option>Teacher V</option>
        <option>Teacher VI</option>
        <option>Teacher VII</option>

        <option>Master Teacher I</option>
        <option>Master Teacher II</option>
        <option>Master Teacher III</option>
        <option>Master Teacher IV</option>
        <option>Master Teacher V</option>

        </select>

        `);
    }

    if (type === 'Private') {

        container.find('.position-wrapper').html(`

        <label class="fw-bold">Position</label>

        <input type="text"
        name="experiences[${index}][position]"
        class="form-control exp_position"
        placeholder="Enter Position (ex. Science Teacher)"
        required>

        `);
    }

});
// ==========================
// INITIAL LOAD AND EVENT LISTENERS
// ==========================
$(document).ready(function() {
    loadExperienceTable();
    
    // Initial reset ng experience
    resetExperience();
    
    // Kapag nagbago ang position o school, i-reset ang experience at i-update ang QS
    $('#position_applied, #school_id').on('change', function() {
        resetExperience();
        
        // If both have values, update the modal requirement text
        const position = $('#position_applied').val();
        const school = $('#school_id').val();
        if (position && school) {
            loadExperienceQS();
        }
    });
    
    // When modal opens
    $('#experienceModal').on('show.bs.modal', function() {
        loadExperienceQS();
        
        // Create modal summary container if not exists
        if ($('#experience_summary_modal').length === 0) {
            $('#experienceContainer').after('<div id="experience_summary_modal" class="mt-3"></div>');
        }
        
        // Rebuild summary dynamically
        updateExperienceModalSummary();
    });
});