$(document).ready(function() {
    $('.update-client-service-progress-btn').on('click', function() {
        var serviceId = $(this).attr('id');
        var form = $('#update-form-' + serviceId);
        console.log(serviceId);
        
    });
});
