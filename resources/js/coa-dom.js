import { NewAccountType, NewAccount } from "./ajax";
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
        $('.loader-container').removeClass('visually-hidden');
            NewAccountType(
                'new-account-type',
                AccountTypeObj,
                { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                CallSuccess,
                CallFailed
            )
            function CallSuccess(response){
                console.log(response);
                // $('.new-account-type-form')[0].reset();
                localStorage.setItem('account-type', 'created');
                location.reload();
            }
            function CallFailed(jqXHR, textStatus, errorThrown) {
            $('.loader-container').addClass('visually-hidden');
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


    $('.save-account').on('click', function(){
        var accForm = $('#account-form').serializeArray();
        var AccObj = {};
        var callFlag = true;
        console.log(accForm);
        
        $.each(accForm, (index, element)=>{
            $(`#account-form [name='${element.name}']`).addClass('is-invalid');
            if(element.value === ''){
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields!',
                    text: 'Please fill all fields'
                });
                callFlag = false;
                return;
            }else{
                $(`#account-form [name='${element.name}']`).removeClass('is-invalid');
                AccObj[element.name] = element.value;
            }
        });

        if(callFlag){
            $('.loader-container').removeClass('visually-hidden');
            NewAccount(
                'new-account',
                AccObj,
                { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                CallSuccess,
                CallFailed
            )

            function CallSuccess(response){
                localStorage.setItem('account', 'created');
                location.reload();
            }
            function CallFailed(jqXHR, textStatus, errorThrown){
                $('.loader-container').addClass('visually-hidden');
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

    $('.edit-coa').click(function(e){
        e.preventDefault();
        var coaRef = $(this).attr('id');
        var updatedCoaForm = $(`#edit-coa-form-${coaRef}`).serializeArray();
        var callFlag = true;
        var updatedCoaOj = {};
        $.each(updatedCoaForm, (index, input)=>{
            if(input.value === ''){
                $(`.edit-coa-form-${coaRef} [name='${input.name}']`).addClass('is-invalild');
                callFlag = false;
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Field!',
                    text: 'Please fill all fields',
                });
                return;
            }
            $(`.edit-coa-form-${coaRef} [name='${input.name}']`).removeClass('is-invalild');
            updatedCoaOj[input.name] = input.value;
        });
        if(callFlag){
            $.ajax({
                type: 'POST',
                url: 'edit-coa',
                data: updatedCoaOj,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                success: function(response){
                    localStorage.setItem('account', 'updated');
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown){
                    try {
                        const response = JSON.parse(jqXHR.responseText);
                        console.log('Parsed Response:', response);
                        ToastError.fire({
                            icon: 'warning',
                            title: 'Conflict',
                            text: response.message
                        });
                    } catch (e) {
                        console.log('Could not parse JSON response:', e);
                    }
                }
            });
        }
    });

    var accountType = localStorage.getItem('account-type');
    var account = localStorage.getItem('account');
    if(accountType === 'created'){
        Toast.fire({
            icon: 'success',
            title: 'New Account Type created!',
        });
        localStorage.removeItem('account-type');
        return;
    }
    if(account === 'created'){
        localStorage.removeItem('account');
        Toast.fire({
            icon: 'success',
            title: 'New Account created!',
        });
        localStorage.removeItem('account-type');
        return;
    }
    if(account === 'updated'){
        localStorage.removeItem('account');
        Toast.fire({
            icon: 'success',
            title: 'Account updated!',
        });
        localStorage.removeItem('account-type');
        return;
    }
});