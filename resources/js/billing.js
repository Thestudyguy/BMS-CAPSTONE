$(document).ready(function () {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
    
    
    
    
    var servicesToBilling = window.servicesData;
    var adsToBilling = window.ads;
    var serviceObj = { Service: [] };
    var subServiceObj = { SubService: [] };
    var accountDescriptions = { accounts: [] };
    $.each(servicesToBilling, (index, services) => {
        $.each(services.Service, (serviceName, service) => {
            serviceObj.Service.push({
                service_name: serviceName,
                service_id: Object.keys(service.parent_service_id)[0]
            });
            if (service.sub_service) {
                $.each(service.sub_service, (subServiceName, subService) => {
                    $.each(subService.sub_service_id, (subId) => {
                        subServiceObj.SubService.push({
                            sub_service_name: subServiceName,
                            sub_service_id: subId
                        });
                    });
                });
            }
        });
    });
    
    $.each(adsToBilling, (index, ad) => {
        accountDescriptions.accounts.push({
            Account: ad.Description,
            account_id: ad.id
        });
    });
    
    console.log(serviceObj);
    console.log(subServiceObj);
    console.log(accountDescriptions);

    
    
    $('.mail-client-bs').on('click', function(){
        var refID = $(this).attr('id');
        console.log(refID);
        
        $.ajax({
            type: 'POST',
            url: `mail-client-bs-${refID}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response){
                console.log(response);
                
            },
            error: function(error, status, jqXHR){
                console.log(error);
                
            },
        });
    });

   $('.remove-parent-service-billing-action').click(function(){
    console.log($(this).attr('id'));
    
   });
   $('.remove-sub-service-billing-action').click(function(){
    console.log($(this).attr('id'));
    
   });
   $('.remove-account-description-billing-action').click(function(){
    console.log($(this).attr('id'));
    
   });
});


