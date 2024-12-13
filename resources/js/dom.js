import { NewService, RemoveService, EditService, NewSubService, NewClientRecord, EditSubService } from "./ajax";
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

    // $(".external-services tr").hover(
    //     function () {
    //         $(this).find('.action-icons').removeClass('visually-hidden')

    //     },
    //     function () {
    //         $(this).find('.action-icons').addClass('visually-hidden')
    //     }
    // );
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
    // $(document).on('mouseenter', '.sub-service', function() {
    //     $(this).find('.sub-service-action-icons').attr('style', 'visibility: visible;');
    // });

    // $(document).on('mouseleave', '.sub-service', function() {
    //     $(this).find('.sub-service-action-icons').attr('style', 'visibility: hidden;');
    // });

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
                            <span id='${element.id}' class="badge mx-2 bg-warning float-right text-sm sub-service-action-icons-remove" data-bs-target='#remove-sub-service' data-bs-toggle='modal' >
                                <i class="fas fa-trash" style="font-size: .8em;"></i>
                            </span>
                            <span id='${element.id}' data-bs-target='#edit-sub-service-modal' data-bs-toggle='modal' class="badge bg-warning float-right text-sm sub-service-action-icons-edit">
                                <i class="fas fa-pen" style="font-size: .8em;"></i>
                            </span>
                            <span id='${element.id}' data-bs-target='#add-sub-service-req' data-bs-toggle='modal' class="mx-2 badge bg-warning float-right text-sm sub-service-action-icons-req">
                                <i class="fas fa-file" style="font-size: .8em;"></i>
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

    $(document).on('click', '.sub-service-action-icons-req', function(){
        var refID = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: `retrieve-sub-service-data-${refID}`,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response){
            console.log(response.sub_service);
            
            $('#sub_service_id').attr('id', response.sub_service.id);
            $('#sub_service_id').val(response.sub_service.id);
            $('.sub-service-req-service-name').text(response.sub_service.ServiceRequirements);
            },
            error: function(error, status, jqXHR){
                ToastError.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                    text: `Translated: ${error}`
                });
            }
        });
    });

    $(document).on('click', '.sub-service-action-icons-edit', function(e){
        $('.sub-service-loader').removeClass('visually-hidden');
        var refID = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: `retrieve-sub-service-data-${refID}`,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response){
            $('.sub-service-loader').addClass('visually-hidden');
            console.log(response.sub_service.ServiceRequirementPrice);
                $('#service-edit-field').val(response.sub_service.ServiceRequirements);
                $('#serviceprice-edit-field').val(response.sub_service.ServiceRequirementPrice);
                $('#sub-service-edit-id').val(response.sub_service.id);
            },
            error: function(error, status, jqXHR){
                ToastError.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                    text: `Translated: ${error}`
                });
            }
        });
    });

    $(document).on('click', '.sub-service-action-icons-remove', function(e){
        e.preventDefault();
        var subServiceID = $(this).attr('id');
        var subService = $(this).closest('tr').find('td:eq(0)').text().trim();
        console.log(subService);
        $('.sub-service-app').text(subService);
        $('.remove-sub-service').attr('id', subServiceID);
    });

    $(document).on('click', '.remove-sub-service', function(){
        $.ajax({
            type: 'POST',
            url: `remove-sub-service/${$(this).attr('id')}`,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response){
                localStorage.setItem('sub-service', 'removed');
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

    $('#edit-sub-service').on('click', function(e){
        e.preventDefault();
        var subServiceEditForm = $('#edit-sub-service-form').serializeArray();
        var callFlag = true;
        var editedSubServiceObj = {};
        $.each(subServiceEditForm, (index, inputs)=>{
            if(inputs.value === ''){
                callFlag = false;
                $(`[name='${inputs.name}']`).addClass('is-invalid');
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please fill all fields'
                });
            }else{
                $(`[name='${inputs.name}']`).removeClass('is-invalid');
                editedSubServiceObj[inputs.name] = inputs.value;
            }
        }); 

        if(callFlag){
            EditSubService(
                'edit-sub-service',
                editedSubServiceObj,
                {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                callSuccess,
                callFailed
            )
        function callSuccess(response){
            console.log(response);
            localStorage.setItem('sub_service', 'updated');
            location.reload();
        }
        function callFailed(error, status, jqXHR){
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
        }
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
    
    $(".submit-new-sub-service").on('click', function(e) {
        e.preventDefault();
        var callFlag = true;
        var subServiceObj = {};
        var id = $(this).attr('id');
        var serializedNewSubService = $(`#new-sub-service-${id}`).serializeArray();
        console.log(serializedNewSubService);
        // return;
        $.each(serializedNewSubService, (index, element) => {
            if (element.value === '') {
                $(`[name='${element.name}']`).addClass('is-invalid');
                callFlag = false;
            } else {
                subServiceObj[element.name] = element.value;
                $(`[name='${element.name}']`).removeClass('is-invalid');
            }
        });
    
        if (!callFlag) {
            Toast.fire({
                icon: 'warning',
                title: 'Missing Fields',
                text: 'Please fill all fields'
            });
            return;
        }
    
        NewSubService(
            'new-sub-service',
            subServiceObj,
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            callSuccess,
            callFailed
        );
    
        function callSuccess(response) {
            console.log(response);
            localStorage.setItem('sub_service', 'created');
            location.reload();
        }
    
        function callFailed(xhr, status, errors) {  
            console.log(xhr);
    
            if (xhr.responseJSON && xhr.responseJSON.message) {
                ToastError.fire({
                    icon: 'warning',
                    title: 'Oops Something went wrong!',
                    text: `Translated: ${xhr.responseJSON.message}`,
                });
                console.log(xhr.responseJSON.errors);
            } else {
                ToastError.fire({
                    icon: 'warning',
                    title: 'Oops Something went wrong!',
                    text: `Translated: ${xhr.responseJSON.error}`,
                });
            }
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
            var currentStep = $(".client-step.active");
                var currentLine = currentStep.next(".client-form-indicator-line");
    
                currentLine.addClass("active");
                currentLine.next(".client-step").addClass("active");
            $(".previous-form").removeClass('visually-hidden');
            if (!currentForm.next('.multi-step').next('.multi-step').length) {
            $(".next-form").addClass('visually-hidden');
            $(".save").removeClass('visually-hidden');
        }
        }
    });
    
    $(".previous-form").on("click", function () {
        var currentForm = $(".multi-step:visible");
        var prevForm = currentForm.prev(".multi-step");
    
        if (prevForm.length) {
            // Transition to the previous form
            currentForm.fadeOut(300, function () {
                $(this).hide();
                prevForm.fadeIn(300, function () {
                    $(this).show();
                });
            });
    
            // Update step indicator
            var currentStep = $(".client-step.active");
            var currentLine = currentStep.prev(".client-form-indicator-line");
    
            currentStep.removeClass("active");
            // currentLine.removeClass("active"); // Deactivate the current indicator line
            currentLine.prev(".client-step").addClass("active"); // Activate the previous step circle
    
            // Manage buttons visibility
            $(".next-form").removeClass("visually-hidden");
            $(".save").addClass("visually-hidden");
            if (!prevForm.prev(".multi-step").length) {
                $(".previous-form").addClass("visually-hidden");
            }
        }
    });
    

    $('.get-ceo-info').click(function(){
        $("#repName").val(clientObj.CEO);
        $("#repcontact").val(clientObj.CEOContactInformation);
        $("#repdob").val(clientObj.CEODateOfBirth);
        $("#position").val('CEO');
        $("#repaddress").val(clientObj.CompanyAddress);
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

    $('.pdf-services').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        window.open(url, 'services/pdf');
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
    var subService = localStorage.getItem('sub_service');
    var sub_service = localStorage.getItem('sub-service');
    if(subService === 'created'){
        Toast.fire({
            icon: 'success',
            title: 'New Requirement added'
        });
        localStorage.removeItem('sub_service');
    }
    if(subService === 'updated'){
        Toast.fire({
            icon: 'success',
            title: 'Service Updated!'
        });
        localStorage.removeItem('sub_service');
    }
    if(sub_service === 'removed'){
        Toast.fire({
            icon: 'success',
            title: 'Service Removed!'
        });
        localStorage.removeItem('sub-service');
    }
});