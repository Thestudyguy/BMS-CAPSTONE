import { NewService, RemoveService, EditService } from "./ajax";
var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000
});
var ToastError = Swal.mixin({
    toast: false,
    position: 'bottom-end',
    showConfirmButton: true
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
    $(".new-sub-service").on('click', function(e){
        e.stopPropagation();
    });
    $(".edit-service").on('click', function(e){
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
    //end of dom manipulation


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
            localStorage.setItem('new-data', 'created');
            location.reload();
        }
        function errorCall(error, status, jqXHR){
            var errorResponse = JSON.parse(error.responseText);
            var errorMessage = errorResponse.error;
            ToastError.fire({
                icon: 'error',
                title: 'Fatal Error',
                text: `Translated: ${errorMessage}`
            });
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
            localStorage.setItem('removed-data', 'removed');
            location.reload();
        }
        function errorCall(error, status, jqXHR){
            ToastError.fire({
                icon: 'error',
                title: 'Fatal Error',
                text: error
            });
        }
    });

    $("#edit-service-form").on('submit', function(e){
        e.preventDefault();
        console.log('form edit logged');
        
        var serializedUpdatedService = $(this).serializeArray();
        var prepareUpdatedService = {};
        var callFlag = false;
        $.each(serializedUpdatedService, (index, element)=>{
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
                prepareUpdatedService[element.name] = element.value;
            }
        });
        if(callFlag){
            return;
        }
        console.log(prepareUpdatedService);
        
        EditService(
            'update-service',
            prepareUpdatedService,
            $('.edit-service').attr('id'),
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            callSuccess,
            callError
        )
        function callSuccess(response){
            console.log(response);
        }
        function callError(errors, status, jqXHR){
            console.log(errors);
            
        }
    });


    //page reload after heavy transactions hehe
    var newData = localStorage.getItem('new-data');
    var removedData = localStorage.getItem('removed-data');
    if(newData === 'created'){
        localStorage.removeItem('new-data');
        Toast.fire({
            icon: 'success',
            title: 'New Record',
            text: 'New record added successfully'
        });
        return;
    }

    if(removedData === 'removed'){
        localStorage.removeItem('removed-data');
        Toast.fire({
            icon: 'success',
            title: 'Record Removed!',
            text: 'Record removed successfully'
        });
        return;
    }
});