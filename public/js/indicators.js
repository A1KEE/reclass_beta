/* =======================================
GLOBAL FUNCTIONS
======================================= */

function syncRow(id){

    const o = document.querySelector(`input[name="ppst[${id}][O]"]`);
    const vs = document.querySelector(`input[name="ppst[${id}][VS]"]`);
    const s = document.querySelector(`input[name="ppst[${id}][S]"]`);

    if(!o || !vs || !s) return;

    // reset disable
    o.disabled = false;
    vs.disabled = false;
    s.disabled = false;

    if(o.checked){
        vs.checked = false;
        s.checked = false;

        vs.disabled = true;
        s.disabled = true;
    }

    if(vs.checked){
        o.checked = false;
        s.checked = false;

        o.disabled = true;
        s.disabled = true;
    }

    if(s.checked){
        o.checked = false;
        vs.checked = false;

        o.disabled = true;
        vs.disabled = true;
    }
}


function updateTotals(){

    let coiO = 0;
    let coiVS = 0;
    let ncoiO = 0;
    let ncoiVS = 0;
    let totalS = 0;
    let totalVS = 0;

    document.querySelectorAll('.ppst-checkbox, .ppst-checkbox-s').forEach(box=>{

        if(box.checked){

            const type = box.dataset.type;
            const col = box.dataset.column;

            if(col === "S") totalS++;
            if(col === "VS") totalVS++;

            if(type === "COI"){
                if(col === "O") coiO++;
                if(col === "VS") coiVS++;
            }

            if(type === "NCOI"){
                if(col === "O") ncoiO++;
                if(col === "VS") ncoiVS++;
            }

        }

    });

    document.getElementById("totalCOI_O").value = coiO;
    document.getElementById("totalCOI_VS").value = coiVS;
    document.getElementById("totalNCOI_O").value = ncoiO;
    document.getElementById("totalNCOI_VS").value = ncoiVS;

    calculateFinalRating(totalS, totalVS);
}


/* =======================================
FINAL RATING
======================================= */

function calculateFinalRating(totalS = 0, totalVS = 0){

    const position = document.getElementById("position_applied")?.value;

    const coiO = parseInt(document.getElementById("totalCOI_O").value) || 0;
    const coiVS = parseInt(document.getElementById("totalCOI_VS").value) || 0;
    const ncoiO = parseInt(document.getElementById("totalNCOI_O").value) || 0;
    const ncoiVS = parseInt(document.getElementById("totalNCOI_VS").value) || 0;

    const finalEl = document.getElementById("finalRating");

    /* =============================
    NO POSITION
    ============================= */
    if(!position){
        finalEl.textContent = "WAITING ⏳";
        return;
    }

    /* =============================
    TOTAL CHECKED
    ============================= */
    const totalChecked = coiO + coiVS + ncoiO + ncoiVS + totalS;

    if(totalChecked === 0){
        finalEl.textContent = "WAITING ⏳";
        return;
    }

    /* =============================
    DISQUALIFICATION RULE
    ============================= */
    if(["Teacher IV","Teacher V","Teacher VI","Teacher VII"].includes(position)){
        if(totalS >= 4){
            finalEl.textContent = "DISQUALIFIED ❌ (Too many S)";
            return;
        }
    }

    if(["Master Teacher I","Master Teacher II","Master Teacher III"].includes(position)){
        if(totalVS >= 4){
            finalEl.textContent = "DISQUALIFIED ❌ (Too many VS)";
            return;
        }
    }

    /* =============================
    PR REQUIREMENTS
    ============================= */
    const prRequirements = {

        "Teacher II": { coiVS:6 , ncoiVS:4 },
        "Teacher III": { coiVS:12 , ncoiVS:8 },
        "Teacher IV": { coiVS:21 , ncoiVS:16 },
        "Teacher V": { coiO:6 , ncoiO:4 },
        "Teacher VI": { coiO:12 , ncoiVS:4 , ncoiO:4 },
        "Teacher VII": { coiO:18 , ncoiVS:6 , ncoiO:6 },
        "Master Teacher I": { coiO:21 , ncoiVS:8 , ncoiO:8 },
        "Master Teacher II": { coiO:10 , ncoiVS:5 , ncoiO:5 },
        "Master Teacher III": { coiO:21 , ncoiVS:8 , ncoiO:8 }

    };

    const req = prRequirements[position];

    if(!req){
        finalEl.textContent = "WAITING ⏳";
        return;
    }

    /* =============================
    CHECK IF PASSED
    ============================= */
    const meetsAllRequirements = (
        (!req.coiO || coiO >= req.coiO) &&
        (!req.coiVS || coiVS >= req.coiVS) &&
        (!req.ncoiO || ncoiO >= req.ncoiO) &&
        (!req.ncoiVS || ncoiVS >= req.ncoiVS)
    );

    if(meetsAllRequirements){
        finalEl.textContent = "QUALIFIED ✅";
        return;
    }
    finalEl.textContent = "IN PROGRESS ⏳";
}
document.addEventListener("change", function(e){

    if(e.target.classList.contains("ppst-checkbox") || 
       e.target.classList.contains("ppst-checkbox-s")){

        const id = e.target.dataset.id;

        syncRow(id);
        updateTotals();
    }

});


/* =======================================
INIT
======================================= */

function initPPST(){
    updateTotals();
}


/* =======================================
POSITION CHANGE (LOAD NEW TABLE)
======================================= */

document.getElementById("position_applied")?.addEventListener("change", function(){

    const position = this.value;

    document.getElementById("finalRating").textContent = "Loading...";

    fetch(`/load-ppst?position=${encodeURIComponent(position)}`)
        .then(res => res.text())
        .then(html => {

            document.getElementById("ppst-container").innerHTML = html;

            // 🔥 re-init after reload
            initPPST();
        });

});


/* =======================================
ON PAGE LOAD
======================================= */

document.addEventListener("DOMContentLoaded", function(){
    initPPST();
});