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
    

    // $('.next-form').on('click', function() {
    //     var currentForm = $(".step:visible");
    //     var form = currentForm.find('form').serializeArray();
    //     var isCurrentFormValidated = false;
    //     $.each(form, (index, element)=>{
    //         var fields = currentForm.find(`[name='${element.name}']`);
    //         if(element.value === ''){
    //             fields.addClass('is-invalid');
    //             Toast.fire({
    //                 icon: 'warning',
    //                 title: 'Missing Fields',
    //                 text: 'Fill all fields to proceed'
    //             });
    //             // isCurrentFormValidated = true;
    //             return false;
    //         }
    //         fields.removeClass('is-invalid');
    //     });
        
    //     if(isCurrentFormValidated){
    //         return;
    //     }else{
    //         console.log({...form});
    //         currentForm.animate({
    //             // marginLeft: '-100%',
    //             opacity: 0
    //         }, function () {
    //             currentForm.hide();
    //             currentForm.next('.step').css({
    //                 // marginLeft: '100%',
    //                 opacity: 0
    //             }).show().animate({
    //                 // marginLeft: '0%',
    //                 opacity: 1
    //             });
    //         });
    //         $(".prev-form").removeClass('visually-hidden');
    //         if (!currentForm.next('.step').next('.step').length) {
    //             $(".next-form").addClass('visually-hidden');
    //             $(".finish").removeClass('visually-hidden');
    //         }
    //     }
    // });
    // $('.prev-form').on('click', function () {
    //     var currentForm = $('.step:visible');
    //     var prevForm = currentForm.prev('.step');

    //     currentForm.animate({
    //         // marginLeft: '100%',
    //         opacity: 0
    //     }, function () {
    //         currentForm.hide();
    //         prevForm.css({opacity: 0 }).show().animate({
    //             // marginLeft: '0%',
    //             opacity: 1
    //         });
    //     });
    //     $(".next-form").removeClass('visually-hidden');
    //     $(".finish").addClass('visually-hidden');
    //     if (prevForm.prev('.step').length === 0) {
    //         console.log(prevForm.prev('.step').length);
    //         $(".prev-form").addClass('visually-hidden');
    //     }
    // });

    // $('#submit-new-client').on('click', function(){
    //     var clientForm = $(".step:visible").find('form');
    //     var serializeClientForm = clientForm.serializeArray();
    //     $.each(serializeClientForm, (index, element)=>{
    //         if(element.value == ''){
    //             $(`[name='${element.name}']`).addClass('is-invalid');
    //             return false;
    //         }
    //         else{
    //             console.log('proceed now');
    //         }
    //     });        
    // });
    
    $('.submit-new-client').on('click', function(){
        var company = {};
        var clientRep = {};
        var service = [];
        var serializeCompany = $('.company-info').serializeArray();
        var serializeClientRep = $('.client-rep').serializeArray();
        var serializeServices = $('.services').serializeArray();
        $.each(serializeCompany, (index, element)=>{
            console.log(element.name);
            
                console.log('not suppose to log');
                if(element.value == ''){
                    $(`[name='${element.name}']`).addClass('is-invalid');
                    Toast.fire({
                        icon: 'warning',
                        title: 'Missing Fields',
                        text: 'All fields are required'
                    });
                }
                else{
                    $(`[name='${element.name}']`).removeClass('is-invalid');
                    company[element.name] = element.value;
                    console.log(company);
                    
                }
        });
        $.each(serializeClientRep, (index, element)=>{
                console.log('not suppose to log');
                if(element.value == ''){
                $(`[name='${element.name}']`).addClass('is-invalid');
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'All fields are required'
                });
            }
            else{
                $(`[name='${element.name}']`).removeClass('is-invalid');
                clientRep[element.name] = element.value;
            }
        });
        $('#services input[name="Service[]"]:checked').each(function() {
            service.push($(this).val());
        });

        if(clientRep.length == 0 || company.length == 0){
            Toast.fire({
                icon: 'error',
                title: 'Fatal Error',
                text: 'Something went wrong, check your inputs or try reloading the page'
            });
            return false;
        }else{
            NewClientRecord(
                'new-client-record',
                company,
                clientRep,
                service,
                {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")},
                successCall,
                failedCall
            )
            function successCall(response){
                console.log(response);
            }
            function failedCall(errors, status, jqXHR){
                console.log(errors);
            }
        }
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