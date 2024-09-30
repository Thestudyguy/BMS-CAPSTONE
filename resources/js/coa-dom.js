import { NewAccountType } from "./ajax";
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
$(document).ready(function(){
    $('.save-account-type').on('click', function(){
        var form = $('.new-account-type-form').serializeArray();
        var AccountTypeObj = {};
        var callFlag = true;
        $.each(form, (index, element)=>{
            if(element.value === ''){
                callFlag = false;
                $(`.new-account-type-form [name='${element.name}']`).addClass('is-invalid');
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields!',
                    text: 'Please fill all fields'
                });
                return;
            }else{
                AccountTypeObj[element.name] = element.value;
                $(`.new-account-type-form [name='${element.name}']`).removeClass('is-invalid');
                console.log(AccountTypeObj);
            }
        });
        
        if(callFlag){
            NewAccountType(
                'new-account-type',
                AccountTypeObj,
                { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                CallSuccess,
                CallFailed
            )
            function CallSuccess(response){
                console.log(response);
                $('.new-account-type-form')[0].reset();
                localStorage.setItem('account-type', 'created');
                location.reload();
            }
            function CallFailed(jqXHR, textStatus, errorThrown) {
                try {
                    const response = JSON.parse(jqXHR.responseText);
                    console.log('Parsed Response:', response);
                    ToastError.fire({
                        icon: 'warning',
                        title: 'Conflict',
                        text: response.error
                    });
                } catch (e) {
                    console.log('Could not parse JSON response:', e);
                }
            }
        }
    });
    var accountType = localStorage.getItem('account-type');
    if(accountType === 'created'){
        Toast.fire({
            icon: 'success',
            title: 'New Account Type created!',
        });
        localStorage.removeItem('account-type');
    }
});