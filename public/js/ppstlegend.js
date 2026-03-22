document.addEventListener("DOMContentLoaded", function () {

    // HARD LOCK: isang beses lang lumabas
    if (window.ppstLegendShown) return;
    window.ppstLegendShown = true;

    const ppstHeading = document.getElementById('ppst-summary');
    if (!ppstHeading) return;

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {

                Swal.fire({
                    title: 'PPST Indicators Legend',
                    html: `
<div style="text-align:left; margin-top:1rem; margin-bottom:2rem; display:flex; flex-direction:column; gap:0.5rem;">
    <div style="display:flex; align-items:center; gap:10px;">
        <div style="width:4px; height:24px; background-color:yellow;"></div>
        <span style="font-weight:bold; color:yellow;">COI</span> - Classroom Observation Indicators
    </div>

    <div style="display:flex; align-items:center; gap:10px;">
        <div style="width:4px; height:24px; background-color:green;"></div>
        <span style="font-weight:bold; color:green;">NCOI</span> - Non-Classroom Observation Indicators
    </div>

    <p style="margin-top:0.5rem; color:#555; font-size:0.9rem;">
        These indicators summarize your professional achievements. Please review them carefully after submitting your IPCRF.
    </p>
</div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Got it!',
                    timer: 10000,
                    timerProgressBar: true
                });

                observer.unobserve(ppstHeading); // stop observing
            }
        });
    }, { threshold: 0.1 }); // maliit lang para mabilis lumabas

    observer.observe(ppstHeading);
});