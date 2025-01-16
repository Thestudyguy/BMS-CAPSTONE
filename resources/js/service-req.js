var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000
});
var ToastError = Swal.mixin({
    toast: false,
    position: 'bottom-end',
});
// headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
$(document).ready(function(){
    var serviceCategory = '';
    $('.add-new-service-req').on('click', function(e){
        console.log($(this).attr('id'));
        console.log('this sum bulshit');
        
        var id = $(this).attr('id').split('_');
        var idref = id[1];
        var form = $(`.service-req-${id[1]}`).serializeArray();
        var callFlag = true;
        $.each(form, (index, data)=>{
            if(data.value === ''){
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Entry!',
                    text: 'Please Enter Requirement Name'
                });
                callFlag = false;
                $('[name="req-name"]').addClass('is-invalid');
                return;
            }
            $('[name="req-name"]').removeClass('is-invalid');
        });
        if(callFlag){
            $.ajax({
                type: 'POST',
                url: 'requirement-for-service',
                data: {form, idref}, 
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                success: function(response){
                    console.log(response);
                    localStorage.setItem('req', 'added');
                    location.reload();
                    
                },
                error: function(error, status, jqXHR){
                    Toast.fire({
                        icon: 'error',
                        title: 'Oops! Something Went wrong',
                        text: 'Translated: ' + error
                    });
                },
            });
        }
});


$('.add-new-sub-service-req').on('click', function(e){
    var idRef = $('#sub_service_id').val();
    var reqName = $('#sub-service-req-id').val();
    if(idRef === '' || reqName === ''){
        Toast.fire({
            icon: 'warning',
            title: 'Missing Entry!',
            text: 'Please Enter Requirement Name'
        });
    }
    if(reqName === ''){
        $('#sub-service-req-id').addClass('is-invalid');
        return;
    }else{
        $('#sub-service-req-id').removeClass('is-invalid');
        $.ajax({
            type: 'POST',
            url: 'sub-service-requirement',
            data: {reqName, idRef},
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response) {
                console.log(response);
                localStorage.setItem('req', 'added');
                location.reload();
            },
            error: function(error, status, jqXHR) {
                Toast.fire({
                    icon: 'error',
                    title: 'Oops! Something Went Wrong',
                    text: 'Translated: ' + error
                });
            },
        });
    }
});

    $('.get-client-service-req').on('click', function(){
        serviceCategory = '';
        $('.append-service-req').empty();
        console.log($(this).attr('id'));
        let client_service = $(this).attr('id');
        $('.service-reqs-loader').removeClass('visually-hidden');
        var reqTable = '';
        $.ajax({
            type: "POST",
            url: `client/service/requirements/${$(this).attr('id')}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response) {
                serviceCategory = response.category;
                console.log(response);
                console.log('this');
                
                
                // localStorage.setItem('req', 'added');
                // location.reload();
                $('.service-reqs-loader').addClass('visually-hidden');
                $.each(response.serviceReqs, (index, reqs) => {
                    let reqContent = '';
                    if (reqs.gcon) {
                        reqContent = `<td>${reqs.gcon}</td>`;
                    } else {
                        reqContent = `
                        <td><input type="file" class="req-file" id="${reqs.id}_${client_service}" data-id="${reqs.req_name}_${reqs.id}" name="${reqs.req_name}_${reqs.id}"></td>
                    `;
                    }
                
                    reqTable += `
                        <tr>
                            <td>${reqs.req_name}</td>
                            ${reqContent}
                        </tr>
                    `;
                });
                
                $('.append-service-req').append(reqTable);
            },
            error: function(error, status, jqXHR) {
                $('.service-reqs-loader').addClass('visually-hidden');
                Toast.fire({
                    icon: 'error',
                    title: 'Oops! Something Went Wrong',
                    text: 'Translated: ' + error
                });
            },
        });
    });

    $('.save-req-files').on('click', function() {
        let filesArray = [];
        let hasFile = false;
        let formData = new FormData();
        const client_id = $(this).attr('id');
    
        $('.req-file').each(function() {
            if (this.files.length) {
                hasFile = true;
                const serviceRequirementId = $(this).attr('id');
                const fileCategory = serviceCategory;
                const file = this.files[0];
    
                
                filesArray.push({
                    service_id: client_id,
                    sub_service_req_id: serviceRequirementId,
                    category: fileCategory,
                    file: file.name
                });
    
                formData.append(`files[${serviceRequirementId}]`, file);
            }
        });
    
        if (!hasFile) {
            Toast.fire({
                icon: 'error',
                title: 'Please upload at least one file.'
            });
            return;
        }
    
        // Add the filesArray as JSON
        formData.append('metadata', JSON.stringify(filesArray));
    
        $.ajax({
            type: 'POST',
            url: '/service/requirement/document',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response) {
                console.log(response);
                localStorage.setItem('req-docs', 'added');
                location.reload();
            },
            error: function(error, status, jqXHR) {
                Toast.fire({
                    icon: 'error',
                    title: 'Oops! Something Went Wrong',
                    text: 'Translated: ' + error
                });
            },
        });
    });
    
    

    $('.view-client-service-doc').on('click', function(){
        $('.service-docs-loader').removeClass('visually-hidden');
        console.log($(this).attr('id'));
        $('.append-service-docs').empty();
        let docsTable = '';
        $.ajax({
            type: 'POST',
            url: `/service/requirement/documents/${$(this).attr('id')}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response) {
                $('.service-docs-loader').addClass('visually-hidden');
                console.log(response);
                
                $.each(response.docsData, (index, docs) => {
                    console.log(docs);
                    let downloadLink = '';
                    if (docs.grp && docs.grp.trim() !== '') {
                        downloadLink = `
                            <a href="storage/${docs.grp}" 
                               download="${docs.gcon}" 
                               class="badge bg-warning text-dark" 
                               style="font-size: 10px;">
                                <i class="fas fa-cloud-download-alt"></i> Download
                            </a>
                        `;
                    }
                    docsTable += `
                        <tr>
                            <td>${docs.req_name}</td>
                            <td>${docs.gcon || 'No file name'}</td>
                            <td>${docs.gcmt}</td>
                            <td>${downloadLink}</td>
                        </tr>
                    `;
                });
                $('.append-service-docs').html(docsTable);
            },
            error: function(error, status, jqXHR) {
                $('.service-docs-loader').addClass('visually-hidden');
                Toast.fire({
                    icon: 'error',
                    title: 'Oops! Something Went Wrong',
                    text: 'Translated: ' + error
                });
            },
        });
    });
    


    var reqdocs = localStorage.getItem('req-docs');
    var req = localStorage.getItem('req');
    if(req === 'added'){
        Toast.fire({
            icon: 'success',
            title: 'Service Requirement',
            text: 'New requirement added'
        });
        localStorage.removeItem('req');
    }
    if(reqdocs === 'added'){
        Toast.fire({
            icon: 'success',
            title: 'Service Requirement',
            text: 'New requirement document is added'
        });
        localStorage.removeItem('req-docs');
    }
});