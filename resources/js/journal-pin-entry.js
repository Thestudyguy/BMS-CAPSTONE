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
    

    $('.journal-pin-entry').on('submit', function(e){
        e.preventDefault();
        var pin = $(this).serializeArray();
        console.log(pin);
        
        $.ajax({
            type: 'POST',
            url: 'journal-pin-entry',
            data: pin,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response){
                console.log(response);
            },
            error: function(error, status, jqXHR){
                ToastError.fire({
                    icon: 'error',
                    title: 'Oops',
                    text: 'Something went wrong. Try reloading the page'
                })
            }
        });
    });
});