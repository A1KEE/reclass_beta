// ============================
// RESET APPLICANT FORM
// ============================
function resetApplicantForm() {
    const form = document.getElementById('applicantForm');
    if (form) form.reset();

    const remarkFields = ['education_remark', 'training_remark', 'experience_remark', 'eligibility_remark'];
    remarkFields.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.innerText = 'Waiting for The QS';
    });

    const applicantQSMap = {
        'education_summary': 'No education added',
        'training_summary': 'No training added',
        'experience_summary': 'No experience added',
        'eligibility_summary': 'No eligibility added'
    };
    Object.entries(applicantQSMap).forEach(([id, text]) => {
        const el = document.getElementById(id);
        if (el) el.innerText = text;
    });

    const positionQSMap = ['qs_education', 'qs_training', 'qs_experience', 'qs_eligibility'];
    positionQSMap.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.innerText = '—';
    });

    const perfDiv = document.getElementById('performanceRequirements');
    const submitBtn = document.getElementById('submitApplication');
    if (perfDiv) perfDiv.style.display = 'none';
    if (submitBtn) submitBtn.setAttribute('disabled', true);

    const rows = document.querySelectorAll('#performanceTable tbody tr');
    rows.forEach(r => r.classList.remove('highlight-row'));

    if (form) {
        const allSelects = form.querySelectorAll('select');
        allSelects.forEach(s => s.selectedIndex = 0);
        const allInputs = form.querySelectorAll('input');
        allInputs.forEach(i => {
            if (i.type === 'checkbox' || i.type === 'radio') i.checked = false;
            else i.value = '';
        });
    }
}

// ============================
// AUTO CHECK QS (JS-ONLY)
// ============================
async function autoCheckQS() {
    const perfDiv = document.getElementById('performanceRequirements');
    const submitBtn = document.getElementById('submitApplication');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // ----------------------------
    // CREATE BLUR OVERLAY + SPINNER
    // ----------------------------
   // ----------------------------
// SHOW EVALUATING LOADER
// ----------------------------
const overlay = document.getElementById('evalLoadingOverlay');
overlay.classList.add('active');

    // ----------------------------
    // Step-by-step animation
    // ----------------------------
    const steps = ['education_remark', 'training_remark', 'experience_remark', 'eligibility_remark'];
    for (let i = 0; i < steps.length; i++) {
        const remarkEl = document.getElementById(steps[i]);
        if (remarkEl) remarkEl.style.fontWeight = 'bold';
        await new Promise(res => setTimeout(res, 1500));
        if (remarkEl) remarkEl.style.fontWeight = 'normal';
    }

    // ----------------------------
    // Check if any remark is "not met"
    // ----------------------------
    const remarks = steps.map(id => document.getElementById(id)?.innerText || '');
    const anyNotMet = remarks.some(r => r.toLowerCase().includes('not met'));

    // Remove overlay
   overlay.classList.remove('active');

    function togglePerformance(show) {
        if (perfDiv) perfDiv.style.display = show ? 'block' : 'none';
        if (submitBtn) submitBtn.toggleAttribute('disabled', !show);
    }

    if (!anyNotMet) {
        togglePerformance(true);
        await Swal.fire({
            icon: 'success',
            title: 'All Requirements MET',
            text: 'You are eligible to proceed to the next step.',
            confirmButtonText: 'Continue'
        });
        if (perfDiv) perfDiv.scrollIntoView({ behavior: 'smooth' });
        const ipcrfModalEl = document.getElementById('ipcrfModal');
        if (ipcrfModalEl) new bootstrap.Modal(ipcrfModalEl).show();
        return;
    }

    // ----------------------------
    // Some not met → request email
    // ----------------------------
    togglePerformance(false);
    const { value: email, dismiss } = await Swal.fire({
        icon: 'info',
        title: 'Some Requirements NOT MET',
        html: `<p>Please provide your email to receive a notification:</p>
               <input type="email" id="depedEmail" class="swal2-input" placeholder="Your Email" required>`,
        showCancelButton: true,
        confirmButtonText: 'Send Notification',
        cancelButtonText: 'Review',
        allowOutsideClick: false,
        allowEscapeKey: false,
        focusConfirm: false,
        preConfirm: () => {
            const val = document.getElementById('depedEmail')?.value.trim();
            if (!val) Swal.showValidationMessage('Email is required!');
            return val;
        }
    });

    if (dismiss === Swal.DismissReason.cancel) return;

    // ----------------------------
    // Sending email loader
    // ----------------------------
    const sendingOverlay = document.createElement('div');
    sendingOverlay.style.position = 'fixed';
    sendingOverlay.style.inset = '0';
    sendingOverlay.style.background = 'rgba(255,255,255,0.65)';
    sendingOverlay.style.backdropFilter = 'blur(6px)';
    sendingOverlay.style.display = 'flex';
    sendingOverlay.style.alignItems = 'center';
    sendingOverlay.style.justifyContent = 'center';
    sendingOverlay.style.zIndex = '9999';
    sendingOverlay.innerHTML = `
        <div style="display:flex;flex-direction:column;align-items:center;gap:15px;">
            <div class="loader" style="border:5px solid rgba(0,0,0,0.05);border-top:5px solid #1E3F66;border-radius:50%;width:55px;height:55px;animation:spin 0.8s linear infinite;"></div>
            <p style="margin-top:18px;font-size:14px;color:#1E3F66;font-weight:500;text-align:center;opacity:0.85;animation:fadePulse 1.5s ease-in-out infinite;">
                Sending your notification...
            </p>
        </div>
    `;
    document.body.appendChild(sendingOverlay);

    try {
        const response = await fetch('/notify-unqualified', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin',
            body: JSON.stringify({ email, remarks })
        });
        if (!response.ok) throw new Error(`Network error: ${response.status}`);
        const data = await response.json();
        if (!data.success) throw new Error(data.message || 'Failed to send email');

        sendingOverlay.remove();

        await Swal.fire({
            icon: 'success',
            title: 'Email Sent!',
            html: `<p>Notification successfully sent to <strong>${email}</strong>.</p>`,
            confirmButtonText: 'OK'
        });

        resetApplicantForm();
    } catch (err) {
        sendingOverlay.remove();
        const { isConfirmed } = await Swal.fire({
            icon: 'error',
            title: 'Failed to send email',
            html: `<p>${err.message}</p>`,
            showCancelButton: true,
            confirmButtonText: 'Retry',
            cancelButtonText: 'Cancel'
        });
        if (isConfirmed) autoCheckQS();
    }
}