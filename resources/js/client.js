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

$(document).ready(function() {
    $('.update-client-service-progress-btn').on('click', function() {
        var serviceId = $(this).attr('id');
        var serviceVal = $(`.update-service-progress-${serviceId}`).serializeArray();

        console.log(serviceVal);
        
        $.ajax({
            type: 'POST',
            url: `update-client-service`,
            data: {serviceId, serviceVal},
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response){
                localStorage.setItem('service', 'updated');
                location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown){
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
        });
    });

    var serviceStatus = localStorage.getItem('service');
    if(serviceStatus === 'updated'){
        Toast.fire({
            icon: 'success',
            text: 'Client Service Updated',
        });
        localStorage.removeItem('service');
    }
});
