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
function previewImage(event, previewId) {
    const fileInput = event.target;
    const file = fileInput.files[0];
    
    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            const previewElement = document.getElementById(previewId);
            previewElement.src = e.target.result; // Update the image src
        };

        reader.readAsDataURL(file); // Read the file
    }
}
$(document).ready(function() {
    $('.update-client-service-progress-btn').on('click', function() {
        var serviceId = $(this).attr('id');
        var serviceVal = $(`.update-service-progress-${serviceId}`).serializeArray();

        console.log(serviceVal);
        
        $.ajax({
            type: 'POST',
            url: `update-client-service`,
            data: {serviceId, serviceVal},
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response){
                localStorage.setItem('service', 'updated');
                location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown){
                try {
                    const response = JSON.parse(jqXHR.responseText);
                    console.log('Parsed Response:', response);
                    ToastError.fire({
                        icon: 'warning',
                        title: 'Conflict',
                        text: response.Conflict
                    });
                } catch (e) {
                    console.log('Could not parse JSON response:', e);
                }
            }
        });
    });

    $('.edit-company-info').on('click', function(e){
        var updatedCompanyInfo = $(`.edit-company-info-${$(this).attr('id')}`).serializeArray();
        var callFlag = true;
        var CompanyInfoObj = {};
        $.each(updatedCompanyInfo, (index, input)=>{
            if(input.value === ''){
                callFlag = false;
                $(`.edit-company-info-${$(this).attr('id')} [name='${input.name}']`).addClass('is-invalid');
                Toast.fire({
                    icon: 'warning',
                    title: 'Missingg Fields', 
                    text: 'Please fill all fields'
                });
            }
            $(`.edit-company-info-${$(this).attr('id')} [name='${input.name}']`).removeClass('is-invalid');
            CompanyInfoObj[input.name] = input.value;
        });

        if(callFlag){
            $.ajax({
                type: "POST",
                url: 'update-company-info',
                data: CompanyInfoObj,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                success: function(response){
                    localStorage.setItem('company-info', 'updated');
                    location.reload();
                },
                error: function(error, statusm, jqXHR){
                    ToastError.fire({
                        icon: 'error',
                        title: 'Oops! Something went wrong',
                        text: 'Translated: ' + error
                    });
                }
            });
        }
        
    });

    $('.edit-ceo').on('click', function(e){
        var form = $(`.edit-ceo-${$(this).attr('id')}`).serializeArray();
        var UpdatedCEOobj = {};
        var callFlag = true;
        var formID = $(this).attr('id');
        $.each(form, (index, input)=>{
            if(input.value === ''){
                callFlag = false;
                $(`[name='${input.name}']`).addClass('is-invalid');
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please fill all fields'
                });
            }
            $(`[name='${input.name}']`).removeClass('is-invalid');
            UpdatedCEOobj[input.name] = input.value;
        });

        if(callFlag){
            $.ajax({
                type: "POST",
                url: 'edit-ceo',
                data: UpdatedCEOobj,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                success: function(response){
                    localStorage.setItem('company-ceo', 'updated');
                    location.reload();
                },
                error: function(error, statusm, jqXHR){
                    ToastError.fire({
                        icon: 'error',
                        title: 'Oops! Something went wrong',
                        text: 'Translated: ' + error
                    });
                }
            });
        }
    });

    $('.edit-company-rep').on('click', function(){
        var formID = $(this).attr('id');
        var repForm = $(`.edit-rep-${formID}`).serializeArray();
        var callFlag = true;
        var updatedRepObj = {};
        $.each(repForm, (index, input)=>{
                if(input.value === ''){
                    callFlag = false;
                    $(`[name='${input.name}']`).addClass('is-invalid');
                    Toast.fire({
                        icon: 'warning',
                        title: 'Missing Fields',
                        text: 'Please fill all fields'
                    });
                }
                $(`[name='${input.name}']`).removeClass('is-invalid');
                updatedRepObj[input.name] = input.value;
        });

        if(callFlag){
            $.ajax({
                type: "POST",
                url: 'edit-rep',
                data: updatedRepObj,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                success: function(response){
                    localStorage.setItem('company-rep', 'updated');
                    location.reload();
                },
                error: function(error, statusm, jqXHR){
                    ToastError.fire({
                        icon: 'error',
                        title: 'Oops! Something went wrong',
                        text: 'Translated: ' + error
                    });
                }
            });
        }
    });

    var companyInfo = localStorage.getItem('company-info');
    var companyCEO = localStorage.getItem('company-ceo');
    var companyRep = localStorage.getItem('company-rep');
    var serviceStatus = localStorage.getItem('service');
    if(serviceStatus === 'updated'){
        Toast.fire({
            icon: 'success',
            text: 'Client Service Updated',
        });
        localStorage.removeItem('service');
    }
    if(companyInfo === 'updated'){
        Toast.fire({
            icon: 'success',
            text: 'Company Info Updated',
        });
        localStorage.removeItem('company-info');
    }
    if(companyCEO === 'updated'){
        Toast.fire({
            icon: 'success',
            text: 'Company CEO Updated',
        });
        localStorage.removeItem('company-ceo');
    }
    if(companyRep === 'updated'){
        Toast.fire({
            icon: 'success',
            text: 'Company Representative Updated',
        });
        localStorage.removeItem('company-rep');
    }
    
});
