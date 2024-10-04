import { NewService, RemoveService, EditService, NewSubServicec, NewClientRecord } from "./ajax";
var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000
});
var ToastError = Swal.mixin({
    toast: false,
    position: 'bottom-end',
})
$(document).ready(function () {
    //dom manipulations

    $('#createNewSelect').on('change', function() {
        const selectedValue = $(this).val();
        if (selectedValue === 'Journal') {
            const modalTarget = $(this).find('option:selected').data('bs-target');
            const modal = new bootstrap.Modal($(modalTarget));
            modal.show();
        }
    });
    
    $('.search-coa').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#coa-table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $('#filter-coa').on('change', function() {
        var selectedValue = $(this).val();
        $('.coa-table tbody tr').each(function() {
            var rowCategory = $(this).data('category');

            if (selectedValue === "clear") {
                $(this).show(); // Show all rows
            } else if (selectedValue === "" || rowCategory === selectedValue) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    $(".client-table-data").hover(
        function () {
            $(this).find('.action-icons').removeClass('visually-hidden')

        },
        function () {
            $(this).find('.action-icons').addClass('visually-hidden')
        }
    );

    $(".external-services tr").hover(
        function () {
            $(this).find('.action-icons').removeClass('visually-hidden')

        },
        function () {
            $(this).find('.action-icons').addClass('visually-hidden')
        }
    );
    $(".new-sub-service-icon").on('click', function(e){
        e.stopPropagation();
    });
    $(".edit-service-icon").on('click', function(e){
        e.stopPropagation();
    });
    $(".view-service-details").on('click', function(e){
        e.stopPropagation();
    });
    $(".remove-service-icon").on('click', function(e){
        e.stopPropagation();
    });
    $(document).on('mouseenter', '.sub-service', function() {
        $(this).find('.sub-service-action-icons').attr('style', 'visibility: visible;');
    });

    $(document).on('mouseleave', '.sub-service', function() {
        $(this).find('.sub-service-action-icons').attr('style', 'visibility: hidden;');
    });

    function handleServiceOperation(status) {
        localStorage.setItem('transaction-status', status);
        location.reload(); // need i reload angg  page for heavy transactions 
    }
    //end of dom manipulation

    $('.client-sub-table').on('click', function() {
        console.log($(this).attr('id'));
        var loaderClass = $(this).closest('tr').next('.expandable-body').find('.loader-td');
        // loaderClass.removeClass('visually-hidden');
    });
    

    $('.external-service').on('click', function () {
        var loader = $(this).next('.expandable-body').find('.loader');
        loader.removeClass('visually-hidden');
        var serviceRef = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: `sub-services-${serviceRef}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                loader.addClass('visually-hidden');
                var services = response.services;
                var table = `<table class="table table-hover float-left append-sub-services">`;
                table += `
                <thead class="text-left">
                    <tr>
                        <td>Requirements</td>
                        <td>Price</td>
                    </tr>
                </thead> 
                `;
                $.each(services, (index, element) => {
                    var floatAmount = parseFloat(element.ServiceRequirementPrice).toFixed(2).toLocaleString();
                    var formattedAmount = Number(floatAmount).toLocaleString();
                    table += `
                    <tbody class="text-left">
                        <tr class='sub-service' id='${element.id}'>
                            <td>${element.ServiceRequirements}</td>
                            <td>${formattedAmount}
                            <span class="float-right text-sm sub-service-action-icons" style='visibility: hidden;'>
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="float-right mx-2 text-sm sub-service-action-icons" style='visibility: hidden;'>
                                <i class="fas fa-pen"></i>
                            </span>
                            </td>
                        </tr>
                    </tbody>                    
                    `;
                });
                table += `</table>`;
                $(`.append-sub-services-${serviceRef}`).html(table);
            },
            error: function (errors, status, jqXHR) {
                ToastError.fire({
                    icon: 'error',
                    title: 'Fatal Error',
                    text: errors
                });
            },
        });

    });

    $('#new-service-form').on('submit', function(e){
        e.preventDefault();
        $('.conflict-text').text('Service already exists');
        $('.conflict-warning').addClass('visually-hidden');
        $(`#new-service-form [name='Service']`).removeClass('is-invalid');
        var preparedServiceData = {};
        var serviceData = $(this).serializeArray();
        var callFlag = false;
        $.each(serviceData, (index, element)=>{
            if(element.value === ''){
                $(`#new-service-form [name='${element.name}']`).addClass('is-invalid');
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please fill all fields'
                });
                callFlag = true;
                return false;
            }else{
                preparedServiceData[element.name] = element.value;
            }
        });
        if(callFlag){
            return;
        }
        NewService(
            'new-service',
            preparedServiceData,
            successCall,
            errorCall
        );
        function successCall(response){
            handleServiceOperation('created');
        }
        function errorCall(error, status, jqXHR){
            var errorResponse = JSON.parse(error.responseText);
            var errorMessage = errorResponse.error;

            if(errorMessage === 'Service already exists'){
                $('.conflict-warning').removeClass('visually-hidden');
                $(`#new-service-form [name='Service']`).addClass('is-invalid');
                $('.conflict-text').text('Service already exists');
                return;
            }
            else{
                ToastError.fire({
                    icon: 'error',
                    title: 'Fatal Error',
                    text: `Translated: ${errorMessage}`
                });
                $('.conflict-warning').addClass('visually-hidden');
                $(`#new-service-form [name='Service']`).removeClass('is-invalid');
            }
        }
    });
    
    $(".remove-service").on('click', function(){
        RemoveService(
            `remove-service-${$(this).attr('id')}`,
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            successCall,
            errorCall
        )
        function successCall(response){
            handleServiceOperation('removed');
        }
        function errorCall(error, status, jqXHR){
            ToastError.fire({
                icon: 'error',
                title: 'Fatal Error',
                text: error
            });
        }
    });

    $(".edit-service").on('click', function(e) {
        e.preventDefault();
        var serviceId = $(this).attr('id');
        var formId = '#edit-service-form-' + serviceId;
        var serializedUpdatedService = $(formId).serializeArray();
        console.log(serializedUpdatedService);
        var prepareUpdatedService = {};
        var callFlag = false;
        $.each(serializedUpdatedService, (index, element) => {
            if (element.value === '') {
                $(formId + " [name='" + element.name + "']").addClass('is-invalid');
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please fill all fields'
                });
                callFlag = true;
                return false;
            } else {
                prepareUpdatedService[element.name] = element.value;
            }
        });
        if (callFlag) {
            return;
        }
        EditService(
            'update-service',
            prepareUpdatedService,
            serviceId,
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            callSuccess,
            callError
        );
    
        function callSuccess(response) {
            handleServiceOperation('updated');
        }
    
        function callError(error, status, jqXHR) {
            var errorResponse = JSON.parse(error.responseText);
            var errorMessage = errorResponse.error;
            ToastError.fire({
                icon: 'error',
                title: 'Fatal Error',
                text: `Translated: ${errorMessage}`
            });
        }
    });
    
    $(".submit-new-sub-service").on('click', function(e){
        e.preventDefault();
        var callFlag = false;
        $.each(serializedNewSubService, (index, element) => {
            if (element.value === '') {
                $(`#new-sub-service [name='${element.name}']`).toggleClass('is-invalid');
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please fill all fields'
                });
                callFlag = true;
                return false;
            } else {
            }
        });
        if (callFlag) {
            return;
        }
    });
    
    var clientObj = {};   
    var profilePath = '';     
    var profileInput = document.getElementById('fileInput'); // wow we need to access DOM element directly ew!
    $(".next-form").on('click', function() {
        var currentForm = $('.multi-step:visible');
        var serializeCurrentForm = currentForm.serializeArray();
        var hasError = false;
        var isProfileEmpty = false;
        currentForm.find('.form-control').removeClass('is-invalid');
    
        $.each(serializeCurrentForm, (index, element) => {
            if (element.value === '') {
                $(`[name='${element.name}']`).addClass('is-invalid');
                hasError = true;
            }
            clientObj[element.name] = element.value;
        });
        if (currentForm.hasClass('client-profile')) {
            if (!profileInput.files || profileInput.files.length === 0) {
                $('#fileInput').addClass('is-invalid');
                isProfileEmpty = true;
            } else {
                $('#fileInput').removeClass('is-invalid');
            }
        }
        if (hasError) {
            Toast.fire({
                icon: 'warning',
                title: 'Missing Fields',
                text: 'Fill all fields to proceed'
            });
        }else if(isProfileEmpty){
            Toast.fire({
                icon: 'warning',
                title: 'Missing File',
                text: 'Please upload a profile picture before proceeding.'
            });
        }else {
            var nextForm = currentForm.next('.multi-step');
            if (nextForm.hasClass('data-entry-preview')) {
                $(".companyName").text($("[name='CompanyName']").val());
                $(".companyEmail").text($("[name='CompanyEmail']").val());
                $(".companyAddress").text($("[name='CompanyAddress']").val());
                $(".companyCEO").text($("[name='CEO']").val());
                $(".companyCEODob").text($("[name='CEODateOfBirth']").val());
                $(".companyCEOContact").text($("[name='CEOContactInformation']").val());
                
                $(".representativeName").text($("[name='RepresentativeName']").val());
                $(".representativeContact").text($("[name='RepresentativeContactInformation']").val());
                $(".representativeDob").text($("[name='RepresentativeDateOfBirth']").val());
                $(".representativePosition").text($("[name='RepresentativePosition']").val());
                $(".representativeAddress").text($("[name='RepresentativeAddress']").val());
    
                var fileInput = document.getElementById('fileInput');
                if (fileInput.files && fileInput.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#previewImage').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
            }
            if (nextForm.length) {
                currentForm.fadeOut(300, function() {
                    $(this).hide();
                    nextForm.fadeIn(200, function() {
                        $(this).show();
                    });
                });
            }
            $(".previous-form").removeClass('visually-hidden');
            if (!currentForm.next('.multi-step').next('.multi-step').length) {
            $(".next-form").addClass('visually-hidden');
            $(".save").removeClass('visually-hidden');
        }
        }
    });

    $('.previous-form').on('click', function () {
        var currentForm = $('.multi-step:visible');
        var prevForm = currentForm.prev('.multi-step');
    
        currentForm.fadeOut(300, function() {
            $(this).hide();
            prevForm.fadeIn(300, function() {
                $(this).show();
            });
        });
        $(".next-form").removeClass('visually-hidden');
        $(".save").addClass('visually-hidden');
        if (prevForm.prev('.multi-step').length === 0) {
            $(".previous-form").addClass('visually-hidden');
        }
    });

    $('.save').on('click', function() {
        var formData = new FormData();
        console.log(clientObj);
        
        NewClientRecord(
            'new-client-record',
            clientObj,                  
            fileInput.files[0],         
            {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},                    
            function success(response) { 
                console.log('Success:', response);
                handleServiceOperation('created');
                window.location.href = 'clients';
            },
            function error(xhr, status, errors) {  
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    ToastError.fire({
                        icon: 'warning',
                        title: 'Oops Something went wrong!',
                        text: `Translated: ${xhr.responseJSON.message}`,
                    });
                    console.log(xhr.responseJSON.errors);
                }
                // if (xhr.responseJSON && xhr.responseJSON.errors) {
                //     let ValidationError = xhr.responseJSON.errors;
                //     let profileErrors = xhr.responseJSON.errors.profile;
                //     if (profileErrors && profileErrors.length > 0) {
                //         ToastError.fire({
                //             icon: 'warning',
                //             title: 'File Conflict',
                //             text: 'Translated: ' + profileErrors[0],
                //         });
                //     }else{
                //         ToastError.fire({
                //             icon: 'warning',
                //             title: 'Error',
                //             text: 'Translated: ' + ValidationError,
                //         });
                //     }
                // }
            }
        );
    });

    $('#fileInput').on('change', function(event) {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result).show();
                console.log(e.target.result);
                
            };
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview').attr('src', '#').hide();
        }
    });


    $('.service-category').on('click', function(){
        var tabRef = $(this).attr('id');
        
    });


    var activeLink = localStorage.getItem('activeNavItem');
    if (activeLink) {
        $('.nav-item a[href="' + activeLink + '"]').addClass('active');
    }

    $('.nav-item a').on('click', function() {
        $('.nav-item a').removeClass('active');
        $(this).addClass('active');

        localStorage.setItem('activeNavItem', $(this).attr('href'));
    });

    const status = localStorage.getItem('transaction-status');
    if (status) {
        localStorage.removeItem('transaction-status');
        const messages = {
            created: { icon: 'success', title: 'New Record', text: 'New record added successfully' },
            removed: { icon: 'success', title: 'Record Removed!', text: 'Record removed successfully' },
            updated: { icon: 'success', title: 'Record Updated!', text: 'Record updated successfully' }
        };
        if (messages[status]) {
            Toast.fire(messages[status]);
        }
    }

});