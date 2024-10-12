$(document).ready(function(){
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
    $('#account-category').on('change', function(e){
        $.ajax({
            type: 'POST',
            url: `get-account-types-${$(this).val()}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response){
                console.log(response);
            },
            error: function(error, status, jqXHR){
                ToastError.fire({
                    icon: 'error',
                    title: 'Fatal Error',
                    text: `Translated: ${error}`
                });
                return;
            }
        });
    });
});