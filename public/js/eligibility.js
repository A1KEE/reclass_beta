let eligibilityRequired = '';
let eligibilitySaved = false;

/* ==========================
   LOAD QS ELIGIBILITY
========================== */
function loadEligibilityQS() {
    const position = $('#position_applied').val();
    const level = $('#school_id').find(':selected').data('level');

    if (!position || !level) {
        $('#modal_eligibility_summary').html(`
            <div class="alert alert-warning p-2">
                <strong>No Position/Level Selected</strong><br>
                <small>Please select a position and school first</small>
            </div>
        `);
        return;
    }

    if (window.qsConfig && window.qsConfig[level] && window.qsConfig[level][position]) {
        const positionConfig = window.qsConfig[level][position];
        eligibilityRequired = positionConfig.eligibility || '—';
        
        $('#modal_eligibility_summary').html(`
            <div class="alert alert-info p-1"><br>
                Required Eligibility: ${eligibilityRequired}
            </div>
        `);
        
        return;
    }

    $('#modal_eligibility_summary').html(`
        <div class="alert alert-warning p-1">
            <strong>No Eligibility Requirement Found</strong>
        </div>
    `);
}

/* ==========================
   VALIDATE EXPIRY DATE
========================== */
function isEligibilityExpired(expiryDateString) {
    if (!expiryDateString) return false;
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const expiryDate = new Date(expiryDateString);
    expiryDate.setHours(0, 0, 0, 0);
    
    return expiryDate < today;
}

/* ==========================
   FORMAT DATE FOR DISPLAY
========================== */
function formatDate(dateString) {
    if (!dateString) return '—';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}

/* ==========================
   FORMAT FILE SIZE
========================== */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

/* ==========================
   VALIDATE FILE TYPE
========================== */
function isValidFileType(file) {
    if (!file) return false;
    
    const imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
    const documentTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.oasis.opendocument.text',
        'text/plain'
    ];
    
    const fileType = file.type.toLowerCase();
    return imageTypes.includes(fileType) || documentTypes.includes(fileType);
}

/* ==========================
   GET FILE ICON
========================== */
function getFileIcon(file) {
    if (!file) return 'fa-file';
    
    const fileType = file.type.toLowerCase();
    
    if (fileType.includes('image')) return 'fa-file-image text-primary';
    if (fileType.includes('pdf')) return 'fa-file-pdf text-danger';
    if (fileType.includes('word') || fileType.includes('document')) return 'fa-file-word text-primary';
    if (fileType.includes('text') || fileType.includes('plain')) return 'fa-file-alt text-secondary';
    
    return 'fa-file text-muted';
}
function updateEligibilitySummary() {
    const name = $('#eligibilityInput').val();
    const expiry = $('#eligibilityExpiry').val();
    const file = $('#eligibilityAttachment')[0]?.files[0];

    let statusText = 'Waiting...';
    let badgeClass = 'bg-secondary';
    let selectedText = '—';

    if (name) selectedText = name;

    if (name && expiry && file) {
        const isExpired = new Date(expiry) < new Date();
        statusText = isExpired ? 'Expired - Upload renewal proof' : 'Valid';
        badgeClass = isExpired ? 'bg-danger' : 'bg-success';
    }

    $('#eligibility_summary_modal').html(`
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <strong>Eligibility Summary</strong><br>
                Status: <span class="badge ${badgeClass}">${statusText}</span><br>
                Selected Eligibility: <span class="fw-bold">${selectedText}</span>
            </div>
        </div>
    `);
}

// Real-time update
$('#eligibilityInput, #eligibilityExpiry, #eligibilityAttachment').on('change input', function() {
    updateEligibilitySummary();
});
/* ==========================
   SAVE ELIGIBILITY (UPDATED - Simple summary lang)
========================== */
function saveEligibility() {

    const name = document.getElementById('eligibilityInput').value;
    const expiry = document.getElementById('eligibilityExpiry').value;
    const fileInput = document.getElementById('eligibilityAttachment');
    const file = fileInput.files[0];

    if (!name || !expiry) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Information',
            text: 'Please select eligibility and expiry date.'
        });
        return;
    }

    if (!file) {
        Swal.fire({
            icon: 'warning',
            title: 'Attachment Required',
            text: 'Please upload your eligibility document.'
        });
        return;
    }

    // ==========================
    // 🔥 ADD TO MAIN FORM
    // ==========================
    const container = document.getElementById('eligibilityHiddenContainer');

    // CLEAR OLD DATA (single entry muna)
    container.innerHTML = '';

    // TEXT DATA
    container.insertAdjacentHTML('beforeend', `
        <input type="hidden" name="eligibility_files[0][eligibility]" value="${name}">
        <input type="hidden" name="eligibility_files[0][expiry_date]" value="${expiry}">
    `);

    // FILE INPUT (CLONE TRICK)
    const clonedInput = fileInput.cloneNode(true);
    clonedInput.name = "eligibility_files[0][file]";

    container.appendChild(clonedInput);

    // ==========================
    // UI UPDATE
    // ==========================
    $('#eligibility_summary').html(`
        <span class="text-muted">${name} (${formatDate(expiry)})</span>
    `);

    $('#eligibility_remark').html(`
        <span class="text-success fw-bold">MET</span>
    `);

    document.getElementById("eligibility_remarks").value = "MET";
    eligibilitySaved = true;

    Swal.fire({
        icon: 'success',
        title: 'Eligibility Saved',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    });

    $('#eligibilityModal').modal('hide');
}
/* ==========================
   RESET ELIGIBILITY
========================== */
function resetEligibility() {
    $('#eligibilityInput').val('');
    $('#eligibilityExpiry').val('');
    $('#eligibilityAttachment').val('');
    
    // Reset styles
    $('#eligibilityExpiry').removeClass('is-valid is-invalid');
    $('#eligibilityAttachment').removeClass('is-valid is-invalid');
    
    // Remove messages
    $('.expiryWarning, .expiryInfo, .fileInfo').remove();

    $('#eligibility_summary').html('<span class="text-muted">No eligibility added</span>');
    $('#eligibility_remark').html('<span class="text-muted">Waiting for the QS</span>');

    document.getElementById("eligibility_remarks").value = "NOT MET";
    eligibilitySaved = false;
}

/* ==========================
   REAL-TIME VALIDATION
========================== */
function validateAndUpdateUI() {
    const expiry = $('#eligibilityExpiry').val();
    const file = $('#eligibilityAttachment')[0]?.files[0];
    const saveBtn = $('#saveEligibilityBtn');
    
    if (!expiry) {
        $('#eligibilityExpiry').removeClass('is-valid is-invalid');
        return;
    }
    
    const isExpired = isEligibilityExpired(expiry);
    
    // Expiry date validation
    if (isExpired) {
        $('#eligibilityExpiry').removeClass('is-valid').addClass('is-invalid');
        
        let warningMsg = $('#expiryWarning');
        if (!warningMsg.length) {
            warningMsg = $('<div>', {
                id: 'expiryWarning',
                class: 'invalid-feedback d-block',
                html: '<i class="fas fa-exclamation-triangle me-1"></i> Expired - Renewal proof required'
            });
            $('#eligibilityExpiry').after(warningMsg);
        }
        $('#expiryInfo').remove();
        
    } else {
        $('#eligibilityExpiry').removeClass('is-invalid').addClass('is-valid');
        
        let infoMsg = $('#expiryInfo');
        if (!infoMsg.length) {
            infoMsg = $('<div>', {
                id: 'expiryInfo',
                class: 'valid-feedback d-block',
                html: '<i class="fas fa-check-circle me-1"></i> Valid - ID proof required'
            });
            $('#eligibilityExpiry').after(infoMsg);
        }
        $('#expiryWarning').remove();
    }
    
    // File validation
    if (file) {
        $('#eligibilityAttachment').removeClass('is-invalid').addClass('is-valid');
        
        let fileInfo = $('#fileInfo');
        if (!fileInfo.length) {
            fileInfo = $('<div>', {
                id: 'fileInfo',
                class: 'valid-feedback d-block',
                html: `<i class="far ${getFileIcon(file)} me-1"></i> ${file.name} (${formatFileSize(file.size)})`
            });
            $('#eligibilityAttachment').after(fileInfo);
        }
        
        // Enable save button
        saveBtn.prop('disabled', false)
            .removeClass('btn-secondary')
            .addClass(isExpired ? 'btn-warning' : 'btn-success')
            .html(`<i class="bi ${isExpired ? 'bi-exclamation-triangle' : 'bi-check-circle'} me-1"></i> Save Eligibility`);
        
    } else {
        $('#eligibilityAttachment').removeClass('is-valid').addClass('is-invalid');
        $('#fileInfo').remove();
        
        // Disable save button
        saveBtn.prop('disabled', true)
            .removeClass('btn-success btn-warning')
            .addClass('btn-secondary')
            .html('<i class="bi bi-upload me-1"></i> Upload Proof First');
    }
}

/* ==========================
   MODERN FILE UPLOAD HANDLER
========================== */
$(document).ready(function() {
    
    $('#eligibilityAttachment').on('change', function() {
        const file = this.files[0];
        
        if (!file) {
            validateAndUpdateUI();
            return;
        }
        
        // Validate file type
        if (!isValidFileType(file)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please upload PDF, Word, or Image files only.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            
            this.value = '';
            validateAndUpdateUI();
            return;
        }
        
        // Validate file size
        if (file.size > 5 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Maximum file size is 5MB.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            
            this.value = '';
            validateAndUpdateUI();
            return;
        }
        
        // Valid file - show success
        const icon = getFileIcon(file);
        Swal.fire({
            icon: 'success',
            title: 'File Attached',
            html: `
                <div class="text-center">
                    <i class="far ${icon} fa-3x mb-3"></i>
                    <p class="mb-1"><strong>${file.name}</strong></p>
                    <p class="text-muted small">${formatFileSize(file.size)}</p>
                </div>
            `,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500
        });
        
        validateAndUpdateUI();
    });

    // Position/School change
    $('#position_applied, #school_id').on('change', function() {
        resetEligibility();
        loadEligibilityQS();
    });

    // Modal open event
    $('#eligibilityModal').on('show.bs.modal', function() {
        loadEligibilityQS();
        
        // Reset validation
        $('#eligibilityExpiry, #eligibilityAttachment').removeClass('is-valid is-invalid');
        $('.expiryWarning, .expiryInfo, .fileInfo').remove();
        
        // Reset save button
        $('#saveEligibilityBtn')
            .prop('disabled', false)
            .removeClass('btn-success btn-warning btn-secondary')
            .addClass('btn-success')
            .html('<i class="bi bi-check-circle me-1"></i> Save Eligibility');
    });

    // Modal hidden event - reset modal summary
    $('#eligibilityModal').on('hidden.bs.modal', function() {
        const position = $('#position_applied').val();
        const level = $('#school_id').find(':selected').data('level');
        
        if (position && level && window.qsConfig && window.qsConfig[level] && window.qsConfig[level][position]) {
            $('#modal_eligibility_summary').html(`
                <div class="alert alert-info p-2">
                    <strong>Eligibility Summary</strong><br>
                    Required: ${eligibilityRequired}
                </div>
            `);
        }
    });

    // Expiry date validation
    $('#eligibilityExpiry').on('change input', validateAndUpdateUI);

    // Initial load
    console.log('Eligibility script initialized');
    $('#eligibility_summary').html('<span class="text-muted">No eligibility added</span>');
    $('#eligibility_remark').html('<span class="text-muted">Waiting for the QS</span>');
});