import { ClientServices, FetchSubServices } from "./ajax";

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

var totalServices = 0;
var totalAmount = 0;
var servicesData = [];

$(document).ready(function() {
    $('.services').on('change', function() {
        var serviceID = $(this).attr('id');
        var serviceValue = $(this).val().split('-');
        var serviceName = serviceValue[0];
        var servicePrice = parseFloat(serviceValue[1]);
        
        $('.sub-services-append').html('');
        $('.service-input').html('');
        $('.sub-services').find('.loader').removeClass('visually-hidden');
        $('.services-total').show();

        totalAmount = servicePrice;
        totalServices = 1;
        generateFileInput(serviceName, servicePrice, true);
        updateTotals();

        FetchSubServices(
            `fetch-sub-services-${serviceID}`,
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            displaySubServices,
            callFailed
        );

        servicesData.push({
            serviceName: serviceName,
            servicePrice: servicePrice,
            serviceFile: null
        });
    });

    function displaySubServices(response) {
        $('.sub-services').find('.loader').addClass('visually-hidden');
        $.each(response.subServices, (index, element) => {
            if (element === 'id') return;
            $('.sub-services-append').append(
                `<li>
                    <input class='sub-service-item' type='checkbox' name='SubService' id='${element.id}' 
                    value='${element.ServiceRequirements}_${element.ServiceRequirementPrice}' 
                    style='font-size: 5px;'>${element.ServiceRequirements}
                </li>`
            );
        });
    }

    function callFailed(error, status, jqXHR) {
        console.log(error);
        ToastError.fire({
            icon: 'error',
            title: 'Fatal Error',
            text: `Translated: ${error}`
        });
    }

    $(document).on('click', '.sub-service-item', function() {
        var subService = $(this).val().split('_');
        var isSubServiceAlreadySelected = $(this).attr('id');
        var subServiceName = subService[0];
        var subServicePrice = parseFloat(subService[1]);

        if ($(this).is(':checked')) {
            $('.client-service-input').append(
                `<tr id='row-${isSubServiceAlreadySelected}'>
                    <td>${subServiceName}</td>
                    <td>${subServicePrice.toLocaleString()}</td>
                    <td>
                        <input type="file" class="clientDocument" name="clientDocument-${isSubServiceAlreadySelected}" accept='file/*'>
                    </td>
                </tr>`
            );
            totalAmount += subServicePrice;
            totalServices++;
            servicesData.push({
                serviceName: subServiceName,
                servicePrice: subServicePrice,
                serviceFile: null
            });
        } else {
            $(`#row-${isSubServiceAlreadySelected}`).remove();
            totalAmount -= subServicePrice;
            totalServices--;
            servicesData = servicesData.filter(service => service.subServiceName !== subServiceName);
        }

        updateTotals();
    });

    function updateTotals() {
        $('.totalAmount').text(`Total Amount: ${totalAmount.toLocaleString()}`);
        $('.totalServices').text(`Total Services: ${totalServices}`);
    }

    function generateFileInput(serviceName, servicePrice, excludeFileInput) {
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
                                </tr>
                            </thead>
                            <tbody class='client-service-input'>
                                <tr>
                                    <td>${serviceName}</td>
                                    <td>${servicePrice.toLocaleString()}</td>
                                    <td>${excludeFileInput ? 'No file required' : `<input type="file" class="clientDocument" name="clientDocument" accept='file/*'>`}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `);
    }

$(document).on('change', '.clientDocument', function() {
    var fileInput = $(this);
    var row = fileInput.closest('tr');
    var subServiceName = row.find('td').first().text();
    var files = Array.from(fileInput[0].files);
    servicesData = servicesData.map(service => {
        if (service.serviceName === subServiceName) {
            return { ...service, serviceFile: files.length > 0 ? files[0] : null };
        }
        return service;
    });
});
function submitServices() {
    var clientID = $('.hidden-client-id').attr('id'); 
    var formData = new FormData();
    formData.append('client_id', clientID);
    servicesData.forEach((service, index) => {
        formData.append(`services[${index}][serviceName]`, service.serviceName);
        formData.append(`services[${index}][servicePrice]`, service.servicePrice);

        if (service.serviceFile) {
            formData.append(`services[${index}][serviceFile]`, service.serviceFile);
        }
    });

    for (const [key, value] of formData.entries()) {
        console.log(key, value);
    }

    ClientServices(
        `client-services-${clientID}`,
        formData,
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
        redirectUser,
        callFailed
    );

    function redirectUser(response) {
        console.log(response);
        window.location.href = 'clients';
        localStorage.setItem('services', true);
    }

    function callFailed(error, status, jqXHR) {
        console.log(error);
        ToastError.fire({
            icon: 'error',
            title: 'Fatal Error',
            text: `Translated: ${error}`
        });
    }
}


    $('.submit-services').on('click', function() {
        submitServices();
    });

    var operation = localStorage.getItem('services');
    if(operation){
        Toast.fire({
            icon: 'success',
            title: 'Services Added to Client',
        });
        localStorage.removeItem('services');
    }
});
