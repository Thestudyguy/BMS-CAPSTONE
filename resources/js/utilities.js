var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000
});
var ToastError = Swal.mixin({
    toast: false,
    position: 'bottom-end',
    showConfirmButton: true
})
$(document).ready(function(){
    
    $(".client-table-data").hover(
        function() {
                $(this).find('.action-icons').removeClass('visually-hidden')
            
        },
        function() {
                $(this).find('.action-icons').addClass('visually-hidden')
        }
    );

    $(".external-services-action-icons tr").hover(
        function() {
                $(this).find('.action-icons').removeClass('visually-hidden')
            
        },
        function() {
                $(this).find('.action-icons').addClass('visually-hidden')
        }
    );
    
});