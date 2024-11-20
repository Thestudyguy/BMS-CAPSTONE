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
   $('.update-journal-status').on('click', function(){
    console.log($(this).attr('id'));
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