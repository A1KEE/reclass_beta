/* =======================================
GLOBAL FUNCTIONS
======================================= */

function syncRow(id){

    const o = document.querySelector(`input[name="ppst[${id}][O]"]`);
    const vs = document.querySelector(`input[name="ppst[${id}][VS]"]`);
    const s = document.querySelector(`input[name="ppst[${id}][S]"]`);

    if(!o || !vs || !s) return;

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


/* =======================================
TOTALS
======================================= */

function updateTotals(){

    let coiO = 0, coiVS = 0, ncoiO = 0, ncoiVS = 0;
    let totalS = 0, totalVS = 0;

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
    const finalEl = document.getElementById("finalRating");
    const warningEl = document.getElementById("ncoiWarning");
    const progressEl = document.getElementById("ppstProgress");
    const resultInput = document.getElementById("ppst_result"); // ✅ hidden input

    const coiO = +document.getElementById("totalCOI_O").value || 0;
    const coiVS = +document.getElementById("totalCOI_VS").value || 0;
    const ncoiO = +document.getElementById("totalNCOI_O").value || 0;
    const ncoiVS = +document.getElementById("totalNCOI_VS").value || 0;

    // =============================
    // INITIAL STATES
    // =============================
    if(!position){
        finalEl.textContent = "WAITING ⏳";
        if(resultInput) resultInput.value = "draft";
        return;
    }

    const totalChecked = coiO + coiVS + ncoiO + ncoiVS + totalS;

    if(totalChecked === 0){
        finalEl.textContent = "WAITING ⏳";
        if(resultInput) resultInput.value = "draft";
        return;
    }

    // =============================
    // DISQUALIFICATION RULES
    // =============================
    if(["Teacher IV","Teacher V","Teacher VI","Teacher VII"].includes(position)){
        if(totalS >= 3){
            finalEl.textContent = "DISQUALIFIED ❌ - 3 Satisfactor(S) reached";
            if(resultInput) resultInput.value = "disqualified";
            return;
        }
    }

    if(["Master Teacher I","Master Teacher II","Master Teacher III"].includes(position)){
        if(totalVS >= 3){
            finalEl.textContent = "DISQUALIFIED ❌ - 3 Very Satisfactor(VS) reached";
            if(resultInput) resultInput.value = "disqualified";
            return;
        }
    }

    // =============================
    // REQUIREMENTS
    // =============================
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
        finalEl.textContent = "WAITING... ⏳";
        if(resultInput) resultInput.value = "draft";
        return;
    }

    let meetsAllRequirements = true;

    // =============================
    // COI CHECK
    // =============================
   if(position === "Teacher V"){

    const totalCOI = coiO + coiVS;
    const totalNCOI = ncoiO + ncoiVS;

    // =============================
    // HARD REQUIREMENTS (AUTO DQ)
    // =============================
    if(coiO < 6){
        finalEl.textContent = "DISQUALIFIED ❌ - Need at least 6 COI Outstanding";
        if(resultInput) resultInput.value = "disqualified";
        return;
    }

    if(ncoiO < 4){
        finalEl.textContent = "DISQUALIFIED ❌ - Need at least 4 NCOI Outstanding";
        if(resultInput) resultInput.value = "disqualified";
        return;
    }

    // =============================
    // BASELINE TOTAL (Teacher IV)
    // =============================
    if(totalCOI < 21 || totalNCOI < 16){
        finalEl.textContent = "IN PROGRESS ⏳";
        if(resultInput) resultInput.value = "draft";
        return;
    }

    // =============================
    // PASOK NA
    // =============================
    finalEl.textContent = "QUALIFIED ✅";
    if(resultInput) resultInput.value = "qualified";
    return;
}

    // =============================
    // NCOI LOGIC
    // =============================
    const requiredNCOI = (req.ncoiO || 0) + (req.ncoiVS || 0);
    const actualNCOI = ncoiO + ncoiVS;

    const isHighPosition = ["Teacher VI","Teacher VII","Master Teacher I","Master Teacher II","Master Teacher III"].includes(position);

    // =============================
    // WARNING
    // =============================
    let maxAllowedVS = requiredNCOI - ncoiO;

    if(isHighPosition && ncoiVS > maxAllowedVS){
        warningEl.textContent = "⚠ Too many Very Satisfactory(VS) compared to Outstanding(O)";
    }else{
        warningEl.textContent = "";
    }

    // =============================
    // PROGRESS
    // =============================
    let remaining = Math.max(requiredNCOI - actualNCOI, 0);

    progressEl.textContent = remaining > 0 
        ? `Remaining NCOI needed: ${remaining}` 
        : `NCOI requirement complete ✔`;

    // =============================
    // CHECK IF COMPLETE
    // =============================
    const isComplete = actualNCOI >= requiredNCOI;

    if(!isComplete){
        finalEl.textContent = "IN PROGRESS ⏳";
        if(resultInput) resultInput.value = "draft";
        return;
    }

    // =============================
    // FINAL DISQUALIFICATION
    // =============================
    if(isHighPosition && ncoiVS > maxAllowedVS){
        finalEl.textContent = "DISQUALIFIED ❌ - NCOIs Very Satisfactor higher than Outstanding";
        if(resultInput) resultInput.value = "disqualified";
        return;
    }

    // =============================
    // FINAL RESULT
    // =============================
    if(meetsAllRequirements){
        finalEl.textContent = "QUALIFIED ✅";
        if(resultInput) resultInput.value = "qualified";
    }else{
        finalEl.textContent = "IN PROGRESS ⏳";
        if(resultInput) resultInput.value = "draft";
    }
}
document.addEventListener("change", function(e){
    if(e.target.classList.contains("ppst-checkbox") || 
       e.target.classList.contains("ppst-checkbox-s")){
        const id = e.target.dataset.id;
        syncRow(id);
        updateTotals();
    }
});
function initPPST(){
    updateTotals();
}
/* =======================================
POSITION CHANGE
======================================= */
document.getElementById("position_applied")?.addEventListener("change", function(){

    const position = this.value;
    document.getElementById("finalRating").textContent = "Loading...";

    fetch(`/load-ppst?position=${encodeURIComponent(position)}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById("ppst-container").innerHTML = html;
            initPPST();
        });

});

/* =======================================
ON LOAD
======================================= */
document.addEventListener("DOMContentLoaded", function(){
    initPPST();
});