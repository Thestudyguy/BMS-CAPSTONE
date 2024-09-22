import { FetchSubServices } from "./ajax";
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
$(document).ready(function(){
    $('.services').on('change', function(){
        var serviceID = $(this).attr('id');
        $('.sub-services-append').html('<div class="sub-services-append"></div>');
        $('.service-input').html('<div class="services-input"></div>');
        $('.sub-services').find('.loader').removeClass('visually-hidden');
        generateFileInput($(this).val());
        FetchSubServices(
            `fetch-sub-services-${serviceID}`,
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            displaySubServices,
            callFailed
        )
    });
    function displaySubServices(response){
        $('.sub-services').find('.loader').addClass('visually-hidden');
        $.each(response.subServices, (index, element)=>{
            if(element === 'id'){
                return;
            }
            $('.sub-services-append').append(
                `
                <li><input class='sub-service-item'
                type='radio'
                name='SubService'
                id='${element.id}'
                value='${element.ServiceRequirements}-${element.ServiceRequirementPrice}'
                style='font-size: 5px;'>${element.ServiceRequirements}</li>
                `
            );
        });
        
    }
    function callFailed(error, status, jqXHR){
        console.log(error);
        ToastError.fire({
            icon: 'error',
            title: 'Fatal Error',
            text: `Translated: ${error}`
        });
    }

    $(document).on('click', '.sub-service-item', function(){
        var subService = $(this).val().split('-');
        var isSubServiceAlreadySelected = $(this).attr('id');
        var checkService = $(`#${isSubServiceAlreadySelected}`);
        if(checkService.length === 0){
            $('.client-service-input').append(
                `
                 <tr>
                    <td>${subService[0]}</td>
                    <td>${subService[1]}</td>
                    <td>
                    <input type="file" class="" name="clientDocument" id="" accept='file/*'>
                    </td>
                    <td>
                    <span class="badge bg-danger p-1 text-light fw-bold">remove</span>
                    </td>
                </tr>
                `
            );
        }else{
            Toast.fire({
                icon: 'warning',
                title: 'Conflict',
                text: 'Translated: Service Already Exists'
            });
        }

    });

    function generateFileInput(service){
        // console.log(service);
        var selectedService = service.split('-');
        console.log(selectedService[0]);
        $('.service-input').append(`
            <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <table style='font-size: 10px;' class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td>Service</td>
                                    <td>Amount</td>
                                    <td>Document</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody class='client-service-input'>
                                <tr>
                                    <td>${selectedService[0]}</td>
                                    <td>${selectedService[1]}</td>
                                    <td>
                            <input type="file" class="" name="clientDocument" id="" accept='file/*'>
                                    </td>
                                    <td>
                                    <span class="badge bg-danger p-1 text-light fw-bold">remove</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                            </div>
                        </div>
                    </div>
            `);
    }
});