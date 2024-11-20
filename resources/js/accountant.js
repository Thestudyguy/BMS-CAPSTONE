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
    $('.update-journal-stat').on('click', function(e){
        var JournalID = $(this).attr('id');
        var form = $(`.update-journal-status-${JournalID}`).serializeArray();

        $.ajax({
            type: 'POST',
            url: 'update-journal-custom',
            data: form,
            headers: { 
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") 
            },
            success: function(response){
                console.log(response);
                
            },
            error: function(jqXHR, textStatus, errorThrown){
                try {
                    const response = JSON.parse(jqXHR.responseText);
                    console.log('Parsed Response:', response);
                    ToastError.fire({
                        icon: 'warning',
                        title: 'Conflict',
                        text: response
                    });
                } catch (e) {
                    console.log('Could not parse JSON response:', e);
                }
            }
        });
    });
    
});