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

    $('#serviceprogress').on('change', function(){
        if($(this).val() === 'Rejected' || $(this).val() === 'Canceled'){
            $('.journal-draft-note').removeClass('visually-hidden')
        }else{
            $('.journal-draft-note').addClass('visually-hidden').val('');
        }
    });
   $('.update-journal-status').on('click', function(){
    console.log($(this).attr('id'));
    var selectedStatus = $('#serviceprogress').val();
    var textarea = $('.journal-draft-note');
    if ((selectedStatus === 'Canceled' || selectedStatus === 'Rejected') && textarea.val().trim() === '') {
            Toast.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please provide a note when the status is "Canceled" or "Rejected".'
            });
            textarea.addClass('is-invalid');
            return;
        }
    var form = $(`.update-journal-status-${$(this).attr('id')}`).serializeArray();
    
    $.ajax({
        type: 'POST',
        url: `/update-journal-status`,
        data: form,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
        success: function(responmse){
            console.log(responmse);
            localStorage.setItem('journal-status', 'updated');
            location.reload();
        },
        error: function(error, status, jqXHR){
            console.log(error);
            
        }
    });
   });

   var journalStat = localStorage.getItem('journal-status');
   if(journalStat === 'updated'){
    Toast.fire({
        icon: 'success',
        title: 'Journal Status Update',
        text: 'Journal status updated successfully'
    });
    localStorage.removeItem('journal-status');
   }
});

