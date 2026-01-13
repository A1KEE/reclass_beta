/* ==========================================================
   SUCCESS / ERROR ALERTS
========================================================== */
$(document).ready(function () {

    if (window.APP?.success) {
        Swal.fire({
            icon: 'success',
            title: 'Saved Successfully!',
            text: window.APP.success,
            confirmButtonColor: '#3085d6'
        });
    }

    if (window.APP?.errors?.length) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Form!',
            html: `
              <ul style="text-align:left;">
                ${window.APP.errors.map(e => `<li>${e}</li>`).join('')}
              </ul>
            `,
            confirmButtonColor: '#d33'
        });
    }

});


/* ==========================================================
   UTILITY FUNCTIONS
========================================================== */
function getLevelDisplay(levelKey) {
    if (!levelKey) return '';
    if (levelKey === 'elementary' || levelKey === 'kindergarten') return 'Elementary';
    if (levelKey === 'junior_high') return 'Junior High';
    if (levelKey === 'senior_high') return 'Senior High';
    return '';
}

function getSelectedLevel() {
    return $('#school_id').find(':selected').data('level') || '';
}

function updateHeaderForPosition() {
    let pos = $('#position_applied').val()?.trim();
    let levelText = getLevelDisplay(getSelectedLevel());

    $('#forPosition').text(
        pos ? `For ${pos}${levelText ? ` (${levelText})` : ''}` : 'For —'
    );
}

function highlightPerformanceRow() {
    let selected = $('#position_applied').val()?.trim();
    const $rows = $('#performanceTable tbody tr');

    $rows.removeClass('highlight-row');

    if (!selected) return;

    $rows.each(function () {
        if ($(this).data('position')?.trim() === selected) {
            $(this).addClass('highlight-row');
        }
    });
}


/* ==========================================================
   LEVEL AUTO ASSIGN
========================================================== */
$(document).ready(function () {

    const $levels = $('input[name="levels[]"]');
    const $school = $('#school_id');

    $levels.prop('checked', false);

    $levels.on('click', function (e) {
        e.preventDefault();
        Swal.fire({
            icon: 'info',
            title: 'Select School First',
            text: 'Please select a school/station to automatically determine the level.',
            confirmButtonColor: '#3085d6'
        });
    });

    $school.on('change', function () {

        const level = $(this).find(':selected').data('level');
        $levels.prop('checked', false);

        if (level) {
            $(`input[value="${level}"]`).prop('checked', true);
        }

        updateHeaderForPosition();
        highlightPerformanceRow();
        $('#position_applied').trigger('change');
    });

});


/* ==========================================================
   FETCH QS
========================================================== */
$(document).ready(function () {

    $('#position_applied').on('change', function () {

        let position = this.value;
        let level = getSelectedLevel();

        if (!position || !level) {
            clearQS();
            return;
        }

        $.get(window.APP.qsUrl, { position, level })
            .done(res => {
                if (!res.success) return clearQS();

                $('#qs_education').text(res.data.education);
                $('#qs_training').text(res.data.training);
                $('#qs_experience').text(res.data.experience);
                $('#qs_eligibility').text(res.data.eligibility);
            })
            .fail(clearQS);

        updateHeaderForPosition();
        highlightPerformanceRow();
    });

    function clearQS() {
        $('#qs_education,#qs_training,#qs_experience,#qs_eligibility').text('—');
    }

});


/* ==========================================================
   PPST O / VS LOGIC
========================================================== */
document.addEventListener("DOMContentLoaded", function () {

    const oBoxes  = document.querySelectorAll('[name$="[O]"]');
    const vsBoxes = document.querySelectorAll('[name$="[VS]"]');

    function sync(num) {
        const o  = document.querySelector(`[name="ppst[${num}][O]"]`);
        const vs = document.querySelector(`[name="ppst[${num}][VS]"]`);
        if (!o || !vs) return;

        vs.disabled = o.checked;
        o.disabled  = vs.checked;
    }

    [...oBoxes, ...vsBoxes].forEach(box => {
        box.addEventListener('change', function () {
            const num = this.name.match(/\[(\d+)\]/)?.[1];
            if (num) sync(num);
        });
    });

});


/* ==========================================================
   DATA PRIVACY
========================================================== */
document.addEventListener("DOMContentLoaded", function () {

    if (localStorage.getItem("dataPrivacyAccepted")) return;

    Swal.fire({
        title: "Data Privacy Notice",
        icon: "info",
        confirmButtonText: "I Agree",
        allowOutsideClick: false
    }).then(() => {
        localStorage.setItem("dataPrivacyAccepted", "yes");
    });

});


/* ==========================================================
   UNSAVED FORM WARNING
========================================================== */
document.addEventListener("DOMContentLoaded", function () {

    let changed = false;
    const form = document.querySelector('form');
    if (!form) return;

    form.querySelectorAll('input, textarea, select').forEach(el => {
        el.addEventListener('input', () => changed = true);
        el.addEventListener('change', () => changed = true);
    });

    window.addEventListener('beforeunload', function (e) {
        if (!changed) return;
        e.preventDefault();
        e.returnValue = '';
    });

    form.addEventListener('submit', () => changed = false);

});
