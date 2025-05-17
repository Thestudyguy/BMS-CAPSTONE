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
    
    // $('.search-firm-clients').on('keyup', function() {
    //     var value = $(this).val().toLowerCase();
    //     console.log(value);
        
    //     $('.firm-client-tables tbody tr').filter(function() {
    //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    //     });
    // });

    // $('.search-clients').on('keyup', function() {
    //     var value = $(this).val().toLowerCase();
    //     console.log(value);
        
    //     $('.client-tables tbody tr').filter(function() {
    //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    //     });
    // });

    $('.remove-client-service').on('click', function(){
        $.ajax({
            type: 'POST',
            url: `client-profile/service/remove/${$(this).attr('id')}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response){
                localStorage.setItem('service', 'removed');
                location.reload();
            },
            error: function(error, status, jqXHR){
                ToastError.fire({
                    icon: 'error',
                    title: 'Oops! Something went wrong',
                    text: 'Translated: ' + error
                });
            }
        });
    });

    var companyInfo = localStorage.getItem('company-info');
    var serviceStatus = localStorage.getItem('service');
    if(serviceStatus === 'updated'){
        Toast.fire({
            icon: 'success',
            text: 'Client Service Updated',
        });
        localStorage.removeItem('service');
    }
    if(serviceStatus === 'removed'){
        Toast.fire({
            icon: 'success',
            text: 'Client Service Removed',
        });
        localStorage.removeItem('service');
    }
    if(companyInfo === 'updated'){
        localStorage.removeItem('company-info');
        Toast.fire({
            icon: 'success',
            text: 'Company Info Updated',
        });
        // localStorage.removeItem('service');
    }
    
});
