import { MailClientBilling } from "./ajax";
$(document).ready(function () {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
    
    
    var defaultTotal = 0;
    var servicesToBilling = window.servicesData;
    var adsToBilling = window.ads;
    var serviceObj = { Service: [] };
    var subServiceObj = { SubService: [] };
    var accountDescriptions = { accounts: [] };
    $.each(servicesToBilling, (index, services) => {
        $.each(services.Service, (serviceName, service) => {
            serviceObj.Service.push({
                "Service" : {service_name: serviceName,
                service_id: Object.keys(service.parent_service_id)[0]}
            });
            if (service.sub_service) {
                $.each(service.sub_service, (subServiceName, subService) => {
                    $.each(subService, (index, ad)=>{
                        $.each(ad, (index, ads)=>{
                            $.each(ads.account_descriptions, (index, ads)=>{
                                accountDescriptions.accounts.push({
                                    'Accounts': {
                                        Account: ads.Description,
                                        account_id: ads.adID.toLocaleString()
                                    }
                                });
                            });
                        });
                    });
                    $.each(subService.sub_service_id, (subId) => {
                        
                        subServiceObj.SubService.push({
                           "SubService": {
                            sub_service_name: subServiceName,
                            sub_service_id: subId
                           }
                        });
                    });
                });
            }
        });
    });
    $('.price').each(function(){
        var price = $(this).text();
        var sanitize = price.replace(/[^\d.-]/g, '');
        var preparedPrice = parseFloat(sanitize);
        defaultTotal += preparedPrice;
    });
    
    
    $('.remove-parent-service-billing-action').click(function() {
        var serviceID = $(this).attr('id').split('_')[1];
        var price = $(this).closest('tr').find('td:nth-last-child(2)').text();
        var sanitizePrice = price.replace(/[^\d.-]/g, '');            
        var preparedPrice = parseFloat(sanitizePrice);
        defaultTotal -= preparedPrice;
        $('.overall-due').text(defaultTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $('.total-printed-price').text(defaultTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        var serviceToRemove = serviceObj.Service.findIndex(services => services.Service.service_id === serviceID);
        console.log("Service ID to remove:", serviceID);
        if (serviceToRemove !== -1) {
            serviceObj.Service.splice(serviceToRemove, 1);
            $(this).closest('tr').remove();
        } else {
            console.log("Service not found");
        }
    });
   $('.remove-sub-service-billing-action').click(function(){
        var subServiceID = $(this).attr('id').split('_')[1];
        var price = $(this).closest('tr').find('td:nth-last-child(2)').text();
        var sanitizePrice = price.replace(/[^\d.-]/g, '');            
        var preparedPrice = parseFloat(sanitizePrice);
        defaultTotal -= preparedPrice;
        $('.overall-due').text(defaultTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $('.total-printed-price').text(defaultTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        var subServiceToRemove = subServiceObj.SubService.findIndex(subService => subService.SubService.sub_service_id === subServiceID);
        if(subServiceToRemove !== -1){
            subServiceObj.SubService.splice(subServiceToRemove, 1);
            $(this).closest('tr').remove();
            
        }    
   });
   
   $(document).on('click', '.remove-account-description-billing-action', function(){
    var accountID = $(this).attr('id').split('_')[1];
    var price = $(this).closest('tr').find('td:nth-last-child(2)').text();
    var sanitizePrice = price.replace(/[^\d.-]/g, '');            
    var preparedPrice = parseFloat(sanitizePrice);
    var totalAd = 0;
    totalAd = preparedPrice - preparedPrice;
    defaultTotal -= preparedPrice;
    $('#additional-description-subtotal').text(totalAd.toLocaleString(undefined, { minimumFractionDigits: 2 }));
    $('.overall-due').text(defaultTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
    $('.total-printed-price').text(defaultTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
    var accountToRemove = accountDescriptions.accounts.findIndex(findAccount => findAccount.Accounts.account_id === accountID);
    if (accountToRemove !== -1) {
        accountDescriptions.accounts.splice(accountToRemove, 1);
        $(this).closest('tr').remove();
        console.log(accountDescriptions);
        
    } else {
        console.log("Account not found");
    }
   });

console.log(accountDescriptions);

$('.add-description').on('click', function () {
    var totalAd = 0;
    var row = $(this).closest('tr');
    var service = row.data('service');
    var requirements = row.data('requirements');
    var category = row.data('category');
    var description = row.data('description');
    var taxType = row.data('taxtype');
    var formType = row.data('formtype');
    var price = row.data('price');
    var id = row.data('id');
    var sanitizePrice = price.replace(/[^\d.-]/g, '');
    var preparedPrice = parseFloat(sanitizePrice);
    var adPriceDisplay = preparedPrice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    var isAdExisting = accountDescriptions.accounts.some(account => account.Accounts.Account === description);
    if(isAdExisting){
        Toast.fire({
            icon: 'warning',
            title: 'Conflict',
            text: 'Account Description already exists!'
        });
    }else{
        accountDescriptions.accounts.push({
            'Accounts': {
                Account: description,
                account_id: row.attr('id').toLocaleString()
            }
        });
       defaultTotal += preparedPrice;
       totalAd += preparedPrice;
       $('#additional-description-subtotal').text(totalAd.toLocaleString(undefined, { minimumFractionDigits: 2 }));
       $('.overall-due').text(defaultTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }))
       var newRow = `
            <tr>
                <td>${service}</td>
                <td>${requirements}</td>
                <td>${category}</td>
                <td>${description}</td>
                <td>${taxType}</td>
                <td>${formType}</td>
                <td>₱${adPriceDisplay}</td>
                <td>
                    <span class="badge fw-bold text-light bg-danger remove-account-description-billing-action" id='${id}'><i class="fas fa-trash"></i></span>
                </td>
            </tr>
        `;
        $('.append-ad').append(newRow);
    }
});

$('.mail-client-bs').click(function(){
    var dueDate = $('#dd').val();
    var billingId = $('.billing-id').text();
    var date = $('.date').text();
    var refID = $(this).attr('id');
    if(dueDate === '' || dueDate === undefined){
        $('#dd').addClass('is-invalid');
        Toast.fire({
            icon: 'warning',
            title: 'Missing Field',
            text: 'Please add due date before proceeding'
        });
        return;
    }
    $('#dd').removeClass('is-invalid');
    var billingData = {
        dueDate,
        date,
        serviceObj,
        subServiceObj,
        accountDescriptions,
        refID
    }
    MailClientBilling('mail-client-billing', billingData,
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
        function(response){
            Toast.fire({
                icon: 'success',
                title: 'Billing Created',
                text: 'Billing successfully created'
            });
        },
        function(jqXHR, textStatus, errorThrown){
            try {
                const response = JSON.parse(jqXHR.responseText);
                ToastError.fire({
                    icon: 'warning',
                    title: 'Conflict',
                    text: response.Message
                });
            } catch (e) {
                console.log('Could not parse JSON response:', e);
            }
        }
    )
});
});


