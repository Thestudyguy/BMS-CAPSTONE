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
        
        var serviceValue = $(this).val().split('_');
        var serviceName = serviceValue[0];
        var parentService = $(this).val();
        var servicePrice = parseFloat(serviceValue[1]);
        servicesData = [];
        
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
            serviceName: parentService,
            servicePrice: servicePrice,
            serviceFile: null
        });
        console.log(servicesData);
    });

    function displaySubServices(response) {
        $('.sub-services').find('.loader').addClass('visually-hidden');
        $.each(response.subServices, (index, element) => {
            if (element === 'id') return;
            $('.sub-services-append').append(
                `<li>
                    <input class='sub-service-item' type='checkbox' name='SubService' id='${element.id}' 
                    value='${element.ServiceRequirements}_${element.ServiceRequirementPrice}_subservice_${element.id}' 
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
        var sub_service = $(this).val();
        var isSubServiceAlreadySelected = $(this).attr('id');
        var subServiceName = subService[0];
        var subServicePrice = parseFloat(subService[1]);
        if ($(this).is(':checked')) {
            
            $('.client-service-input').append(
                `<tr id='row-${isSubServiceAlreadySelected}'>
                    <td>${subServiceName}</td>
                    <td>${subServicePrice.toLocaleString()}</td>
                </tr>`
            );
            totalAmount += subServicePrice;
            totalServices++;
            servicesData.push({
                serviceName: sub_service,
                servicePrice: subServicePrice,
                serviceFile: null
            });
        } else {
            const removeSubServiceIndex = servicesData.findIndex(service => service.serviceName === sub_service);
            console.log(sub_service ,'unchecked');
            $(`#row-${isSubServiceAlreadySelected}`).remove();
            totalAmount -= subServicePrice;
            totalServices--;
            // servicesData = servicesData.filter(service => service.subServiceName !== subServiceName);
            if (removeSubServiceIndex !== -1) {
                servicesData.splice(removeSubServiceIndex, 1);
            }
        }
            console.log(servicesData);
            updateTotals();
    });

    function updateTotals() {
        $('.totalAmount').text(`Total Amount: ${totalAmount.toLocaleString()}`);
        $('.totalServices').text(`Total Services: ${totalServices}`);
    }

    function generateFileInput(serviceName, servicePrice, excludeFileInput) {
        const sanitizedServiceRef = serviceName.replace(/\s+/g, '_');
        $('.service-input').append(`
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <table style='font-size: 10px;' class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td>Service</td>
                                    <td>Amount</td>
                                </tr>
                            </thead>
                            <tbody class='client-service-input'>
                                <tr>
                                    <td>${serviceName}</td>
                                    <td>${servicePrice.toLocaleString()}</td>
                                    <td><span class="badge fw-bold text-light bg-danger rounded-1 remove-parent-service" id=${sanitizedServiceRef}><i class="fas fa-trash"></i></span></td>
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

$(document).on('click', '.remove-parent-service', function() {
    var parentService = $(this).attr('id');
    console.log(parentService);
    
    var preparedServiceRef = parentService.replace(/_/g, ' '); // Replace underscores with spaces
    console.log('Prepared Service Ref:', parentService);
    
    // Check if the serviceName exists in the servicesData array
    var serviceToRemove = servicesData.findIndex(service => service.serviceName === preparedServiceRef);
    console.log('Service to remove index:', serviceToRemove);
    
    if (serviceToRemove > -1) {
        // Check if the serviceName is indeed the correct one
        console.log('Service to remove:', servicesData[serviceToRemove]);
        
        // Remove the service from the array
        servicesData.splice(serviceToRemove, 1);
    } else {
        console.log('Service not found in the array!');
    }
    
    // Remove the closest table row
    $(this).closest('tr').remove();
    console.log('Updated Services Data:', servicesData);
});

function submitServices() {
    $('.add-client-service-loader').removeClass('visually-hidden');
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
        $('.add-client-service-loader').addClass('visually-hidden');
        console.log(response);
        window.location.href = 'clients';
        localStorage.setItem('services', true);
    }

    function callFailed(jqXHR, textStatus, errorThrown) {
        $('.add-client-service-loader').addClass('visually-hidden');
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
