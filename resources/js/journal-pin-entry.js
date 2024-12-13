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
        var callFlag = true;

        $.each(pin, (index, data)=>{
            if(data.value === ''){
                Toast.fire({
                    icon: 'warning', 
                    title: 'Missing PIN',
                    text: 'Please enter PIN'
                });
                callFlag = false;
                $(`[name='${data.name}']`).addClass('is-invalid');
            }else{
                $(`[name='${data.name}']`).remove('is-invalid');
            }
        });
        if(callFlag){
            $.ajax({
                type: 'POST',
                url: 'journal-pin-entry',
                data: pin,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response){
                    var blob = new Blob([response], { type: 'application/pdf' });
                    var url = URL.createObjectURL(blob);
                    window.open(url, '_blank');
                    // console.log(response.message);
                    console.log('asdasd');
                    
                },
                error: function(error, status, jqXHR){
                    if(error.status === 400){
                        Toast.fire({
                            icon: 'error',
                            title: error.statusText,
                            text: 'Incorrect PIN'
                        });
                    }else{
                        Toast.fire({
                            icon: 'error',
                            title: 'Something went wrong',
                            text: 'Fatal error. Translated: ' + status
                        });
                    }
                }
            });
        }
    });

    $('.request-journal-pin').click(function(){
        $.ajax({
            type: 'POST',
            url: `request-journal-pin_${$(this).attr('id')}`,
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")},
            success: function(response) {
                Toast.fire({
                    icon: 'success',
                    title: 'User Notified',
                    text: 'Email has been successfully sent to the user.'
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', errorThrown);
                Toast.fire({
                    icon: 'error',
                    title: 'Notification Failed',
                    text: 'Failed to send the email to the user. Please try again later.'
                });
            }
        });        
    });
    $('.remove-journal-entry').click(function(){
        $.ajax({
            type: "POST",
            url: `archive-journal-entry-${$(this).attr('id')}`,
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")},
            success: function(response){
                localStorage.setItem('journal', 'Removed');
                location.reload();
            },
            error: function(error, status, jqXHR){
                ToastError.fire({
                    icon: 'error',
                    title: 'Oops! Something went wrong',
                    text: 'Error Translated: '+ error
                });
            }
        });
    });


    var journal = localStorage.getItem('journal');
    if(journal === 'Removed'){
        localStorage.removeItem('journal');
        Toast.fire({
            icon: 'success',
            title: 'Journal Update',
            text: 'Journal Successfully removed'
        });
    }
    
});