document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("applicantForm");
    const submitBtn = document.getElementById("submitBtn");

    let isSubmitting = false;

    submitBtn.addEventListener("click", function () {

        if (isSubmitting) return;

        const name = document.getElementById("name")?.value;
        const position = document.getElementById("position_applied")?.value;
        const school = document.getElementById("school_id")?.value;

        if (!name || !position || !school) {
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete Form',
                text: 'Please fill out all required fields.'
            });
            return;
        }

        Swal.fire({
            title: 'Submit Application?',
            text: "You will not be able to edit this after submission.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            allowOutsideClick: false
        }).then((result) => {

            if (!result.isConfirmed) return;

            isSubmitting = true;

            submitBtn.disabled = true;
            submitBtn.innerHTML = "Submitting... ⏳";

            showSubmitLoading(); // ✅ SHOW LOADER

            $('#applicantForm').trigger('submit'); // ✅ REAL SUBMIT (NO DELAY)

        });

    });

});