$(function () {

let uploadedIPCRFs = [];
let savedMetaIPCRFs = [];
let draftMetaIPCRFs = [];
let removedIndexes = [];

const ipcrfContainer   = document.getElementById('ipcrfContainer');
const ipcrfInstruction = document.getElementById('ipcrfInstruction');
const positionSelect   = document.getElementById('position_applied');
const saveBtn          = document.getElementById('saveIpcrfBtn');
const ipcrfModalEl     = document.getElementById('ipcrfModal');
const statusEl         = document.getElementById('ipcrfStatus');
const perfDiv          = document.getElementById('performanceRequirements');


// =====================
// ALWAYS SHOW 3 BOXES
// =====================

function getRequiredIPCRFs() {
    return 3;
}


// =====================
// FORMAT FILE SIZE
// =====================

function formatSize(bytes){
    return (bytes / (1024*1024)).toFixed(2)+' MB';
}


// =====================
// STATUS INDICATOR
// =====================

function updateIPCRFStatus(){

    const count = draftMetaIPCRFs.filter(f=>f).length;

    if(!statusEl) return;

    statusEl.classList.remove('d-none');

    if(count === 0){

        statusEl.innerHTML =
        `<i class="bi bi-exclamation-circle-fill text-danger me-1"></i>
        <span class="text-danger">No IPCRF uploaded</span>`;

    }else{

        statusEl.innerHTML =
        `<i class="bi bi-check-circle-fill text-success me-1"></i>
        <span class="text-success">${count} IPCRF uploaded</span>`;

    }
}

// =====================
// RENDER UPLOAD BOXES WITH YEAR LABEL
// =====================

function renderIPCRFBoxes(){

    const required = getRequiredIPCRFs();

    // DepEd-style: last 3 years
    const currentYear = new Date().getFullYear();
    const years = [currentYear-2, currentYear-1, currentYear];

    ipcrfInstruction.textContent =
    `Upload up to 3 IPCRF files. At least one IPCRF is required.`;

    ipcrfContainer.innerHTML = '';

    if(uploadedIPCRFs.length === 0){
        uploadedIPCRFs = Array(required).fill(null);
    }

    if(draftMetaIPCRFs.length === 0){
        draftMetaIPCRFs = Array(required).fill(null);
    }

    for(let i=0;i<required;i++){

        const col = document.createElement('div');
        col.className = 'col-md-4';

        col.innerHTML=`

        <div class="ipcrf-upload-card">

            <div class="ipcrf-upload-body">

                <i class="bi bi-cloud-arrow-up ipcrf-main-icon"></i>

                <div class="ipcrf-title">IPCRF ${years[i]}</div>

                <input type="file" class="d-none" id="ipcrf_file${i}" accept=".pdf">

                <div class="ipcrf-preview mt-2 small text-muted" id="preview${i}">
                    Click or drag PDF here
                </div>

                <div class="ipcrf-actions mt-2 d-flex gap-2 justify-content-center">

                    <button type="button" class="btn btn-sm btn-outline-primary d-none" id="view${i}">View</button>
                    <button type="button" class="btn btn-sm btn-outline-danger d-none" id="remove${i}">Remove</button>

                </div>

            </div>

        </div>`;

        ipcrfContainer.appendChild(col);

        const card = col.querySelector('.ipcrf-upload-card');
        const input = col.querySelector('input');
        const preview = col.querySelector(`#preview${i}`);
        const viewBtn = col.querySelector(`#view${i}`);
        const removeBtn = col.querySelector(`#remove${i}`);
        const icon = col.querySelector('.ipcrf-main-icon');


        card.addEventListener('click',()=>input.click());


        card.addEventListener('dragover',e=>{
            e.preventDefault();
            card.classList.add('dragover');
        });

        card.addEventListener('dragleave',()=>card.classList.remove('dragover'));

        card.addEventListener('drop',e=>{
            e.preventDefault();
            card.classList.remove('dragover');
            handleFile(e.dataTransfer.files[0]);
        });


        input.addEventListener('change',e=>handleFile(e.target.files[0]));


        function handleFile(file){

            if(!file) return;

            if(file.type!=='application/pdf'){
                safeSwal({icon:'error',title:'Invalid File',text:'PDF only allowed'});
                return;
            }

            if(file.size>5*1024*1024){
                safeSwal({icon:'warning',title:'File too large',text:'Max 5MB only'});
                return;
            }

            uploadedIPCRFs[i] = file;

            draftMetaIPCRFs[i] = {
                name: file.name,
                size: file.size,
                url: URL.createObjectURL(file)
            };

            const removedIndex = removedIndexes.indexOf(i);
            if(removedIndex>-1) removedIndexes.splice(removedIndex,1);

            preview.innerHTML =
            `<span title="${file.name}">
            ${file.name.length>20?file.name.substring(0,18)+'...':file.name}
            </span><br>
            <small>${formatSize(file.size)}</small>`;

            preview.classList.remove('text-muted');

            viewBtn.classList.remove('d-none');
            removeBtn.classList.remove('d-none');

            card.classList.add('uploaded');

            icon.classList.replace('bi-cloud-arrow-up','bi-check-circle-fill');

            updateIPCRFStatus();

        }


        viewBtn.onclick = e=>{
            e.preventDefault();
            e.stopPropagation();
            const meta = draftMetaIPCRFs[i] || savedMetaIPCRFs[i];
            if(meta) window.open(meta.url,'_blank');
        }


        removeBtn.onclick = e=>{
            e.stopPropagation();

            uploadedIPCRFs[i] = null;
            draftMetaIPCRFs[i] = null;

            preview.textContent='Click or drag PDF here';
            preview.classList.add('text-muted');

            viewBtn.classList.add('d-none');
            removeBtn.classList.add('d-none');

            card.classList.remove('uploaded');

            icon.classList.replace('bi-check-circle-fill','bi-cloud-arrow-up');

            if(!removedIndexes.includes(i)) removedIndexes.push(i);

            updateIPCRFStatus();
        }


        const meta = (!removedIndexes.includes(i)) && (draftMetaIPCRFs[i] || savedMetaIPCRFs[i]);

        if(meta){

            preview.innerHTML =
            `<span title="${meta.name}">
            ${meta.name.length>20?meta.name.substring(0,18)+'...':meta.name}
            </span><br>
            <small>${formatSize(meta.size)}</small>`;

            preview.classList.remove('text-muted');

            viewBtn.classList.remove('d-none');
            removeBtn.classList.remove('d-none');

            card.classList.add('uploaded');

            icon.classList.replace('bi-cloud-arrow-up','bi-check-circle-fill');

        }

    }

    updateIPCRFStatus();

}

function validateIPCRF(){

    const count = draftMetaIPCRFs.filter((f, i) => f && !removedIndexes.includes(i)).length;

    if(count === 0){
        Swal.fire({
            icon:'warning',
            title:'Missing IPCRF',
            text:'Please upload at least one IPCRF before submitting'
        });
        return false;
    }

    return true;
}
// =====================
// SAVE BUTTON
// =====================

if(saveBtn){

    saveBtn.addEventListener('click',()=>{

       if(!validateIPCRF()) return;

        savedMetaIPCRFs = draftMetaIPCRFs.map((f,i)=> removedIndexes.includes(i)?null:f);

        if(statusEl){

            statusEl.classList.remove('d-none');

            statusEl.innerHTML =
            `<i class="bi bi-check-circle-fill me-1"></i> Uploaded`;

        }

        Swal.fire({
            icon:'success',
            title:'IPCRFs Saved',
            timer:1200,
            showConfirmButton:false
        })
        .then(()=>{

            bootstrap.Modal.getInstance(ipcrfModalEl)?.hide();

            if(perfDiv){

                perfDiv.style.display='block';

                perfDiv.scrollIntoView({behavior:'smooth'});

            }

        });

    });

}


// =====================
// MAIN FORM SUBMIT
// =====================

$('#applicantForm').on('submit', function(e){

    e.preventDefault();

    if(!validateIPCRF()){
    hideSubmitLoading();
    return;
}

    let formData = new FormData(this);

    // 🔥 append files
    uploadedIPCRFs.forEach((file, index) => {
        if(file){
            formData.append(`ipcrf_files[${index}][file]`, file);
            formData.append(
                `ipcrf_files[${index}][title]`,
                `IPCRF ${new Date().getFullYear() - (2 - index)}`
            );
        }
    });

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,

        success: function(res){

            hideSubmitLoading(); // ✅ REMOVE LOADER

            Swal.fire({
                icon:'success',
                title:'Saved successfully'
            }).then(()=> location.reload());
        },

        error: function(err){

            hideSubmitLoading(); // ✅ REMOVE LOADER

            console.error(err);

            Swal.fire({
                icon:'error',
                title:'Upload failed'
            });
        }
    });

});


// =====================
// RESET
// =====================

window.resetIPCRF=function(){

    uploadedIPCRFs=[];
    draftMetaIPCRFs=[];
    savedMetaIPCRFs=[];
    removedIndexes=[];

    if(statusEl) statusEl.classList.add('d-none');

    if(perfDiv) perfDiv.style.display='none';

};


// =====================
// MODAL OPEN
// =====================

let ipcrfInitialized = false;

ipcrfModalEl.addEventListener('show.bs.modal', () => {
    if(!ipcrfInitialized){
        renderIPCRFBoxes();
        ipcrfInitialized = true;
    } else {
        renderIPCRFBoxes(); // re-render UI only, no reset
    }
});
});