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

$(document).ready(function(){
    $('.view-journal-btn').on('click', function(e){
        var clientID = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: `view-client-journal-${clientID}`,
            headers: { 
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") 
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                console.log(response);
                
                var blob = new Blob([response], { type: 'application/pdf' });
                var url = URL.createObjectURL(blob);
                window.open(url, '_blank');
            },
            error: function(error, status, jqXHR){
                ToastError.fire({
                    icon: 'error', 
                    title: 'Oops Something went wrong!',
                    text: error.responseJSON ? error.responseJSON.error : 'Unknown error'
                });
            }
        });
    });

    $('.gen-client-billing-pdf').click(function(){
        $.ajax({
            type: "POST",
            url: `client-billing-data_${$(this).attr('id')}`,
            headers:  { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response], { type: 'application/pdf' });
                var url = URL.createObjectURL(blob);
                window.open(url, '_blank');
            },
            error: function(error, status, jqXHR){
                    Toast.fire({
                        icon: 'error',
                        title: 'Fatal Error',
                        text: 'Something went wrong'
                    });
            }
        });
     });
});
