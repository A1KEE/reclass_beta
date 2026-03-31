$(function(){

    // =====================
    // COMPUTE PERFORMANCE
    // =====================
    function computePerf(){
        let val = parseFloat($("#perfInputModal").val());
        let output = $("#perfResultModal");

        if(isNaN(val)){
            output.val('');
            return;
        }

        let result = Math.round((val / 5) * 30);
        output.val(result);
    }

    // =====================
    // LIVE COMPUTE
    // =====================
    $(document).on("input", "#perfInputModal", function(){
        computePerf();
    });

    // =====================
    // APPLY BUTTON
    // =====================
  $(document).on("click", "#applyPerfBtn", function(e){
    e.preventDefault();

    let result = $("#perfResultModal").val();

    if(!result){
        alert("Please enter a score first");
        return;
    }

    // I-set ang value at i-trigger ang calculation
    $("#performanceFinal").val(result).trigger('input');

    // --- ITO ANG IPALIT MO SA PAG-CLOSE NG MODAL ---
    $('#performanceModal').modal('hide'); 
});
});