import { NewAccountDescription } from "./ajax";
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
    $('#account-category').on('change', function(e) {
        
        $('#billing-account-type').prop('disabled', false); // Correct way to enable the select element
        $.ajax({
            type: 'POST',
            url: `get-account-types-${$(this).val()}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response) {
                console.log(response);
    
                let options = '<option value="" selected hidden>Select Type</option>';
                let hasOptions = false;
    
                // Populate options dynamically
                $.each(response, (index, element) => {
                    $.each(element, (index, at) => {
                        if (Object.keys(at).length > 0) {
                            options += `<option value="${at.id}">${at.ServiceRequirements}</option>`;
                            hasOptions = true;
                        }
                    });
                });
    
                if (!hasOptions) {
                    options = `<option value="" selected hidden>Category has no type</option>`;
                }
    
                $('#billing-account-type').html(options);
            },
            error: function(error) {
                console.error(error);
                ToastError.fire({
                    icon: 'error',
                    title: 'Error',
                    text: `Could not load account types: ${error.responseText || error.statusText}`
                });
            }
        });
    });

    $(document).on('change', '[id^="edit-account-category-"]', function(e) {
        let modalId = $(this).attr('id').split('-').pop();
        let billingAccountTypeSelector = `#edit-billing-account-type-${modalId}`;
    
        console.log($(this).val());
        console.log('Fetching types for modal:', modalId);
    
        $.ajax({
            type: 'POST',
            url: `get-account-types-${$(this).val()}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response) {
                console.log(response);
    
                let options = '<option value="" selected hidden>Select Type</option>';
                let hasOptions = false;
    
                // Populate options dynamically
                $.each(response, (index, element) => {
                    $.each(element, (index, at) => {
                        if (Object.keys(at).length > 0) {
                            options += `<option value="${at.id}">${at.ServiceRequirements}</option>`;
                            hasOptions = true;
                        }
                    });
                });
    
                if (!hasOptions) {
                    options = `<option value="" selected hidden>Category has no type</option>`;
                }
    
                $(billingAccountTypeSelector).html(options);
            },
            error: function(error) {
                console.error(error);
                ToastError.fire({
                    icon: 'error',
                    title: 'Error',
                    text: `Could not load account types: ${error.responseText || error.statusText}`
                });
            }
        });
    });
    

    $('.edit-description').on('click', function(e){
        var modal = $(this).attr('id').split('-').pop();
        e.preventDefault();
        var updatedDescription = {};
        var descriptionForm = $(`.update-description-form-${modal}`).serializeArray();
        var callFlag = true;
        
        $.each(descriptionForm, (index, input)=>{
            if(input.value === ''){
                callFlag = false;
                Toast.fire({
                    icon: 'warning', 
                    title: 'Missing Fields!',
                    text: 'Please fill all fields'
                });
                $(`.update-description-form-${modal} [name='${input.name}']`).addClass('is-invalid');
            }
            $(`.update-description-form-${modal} [name='${input.name}']`).removeClass('is-invalid');
            updatedDescription[input.name] = input.value;
        });
        console.log(updatedDescription);
        
        if(callFlag){
            $.ajax({
                type: 'POST',
                url: 'update/account-description',
                data: updatedDescription,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                success: function(response){
                    localStorage.setItem('account_description', 'updated');
                    location.reload();
                },
                error: function(error, status, jqXHR){
                    ToastError.fire({
                        icon: 'error',
                        title: 'Oops! Something went wrong',
                        text: 'Translated: ' + error.responseText
                    });
                }
            });
        }
    });
    
    $('.remove-description').on('click', function(){
        $.ajax({
            type: 'POST',
            url: `remove/account-description/${$(this).attr('id')}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                success: function(response){
                    localStorage.setItem('account_description', 'removed');
                    location.reload();
                },
                error: function(error, status, jqXHR){
                    ToastError.fire({
                        icon: 'error',
                        title: 'Oops! Something went wrong',
                        text: 'Translated: ' + error.responseText
                    });
                }
        });
    });

    $('.remove-coa').on('click', function(){
        $.ajax({
            type: 'POST',
            url: `remove/coa/${$(this).attr('id')}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                success: function(response){
                    localStorage.setItem('coa', 'removed');
                    location.reload();
                },
                error: function(error, status, jqXHR){
                    ToastError.fire({
                        icon: 'error',
                        title: 'Oops! Something went wrong',
                        text: 'Translated: ' + error.responseText
                    });
                }
        });
    });

    $('.save-account-description').on('click', function(e){
        var batdForm = $('.account-description-form').serializeArray();
        var formObj = {};
        var callFlag = true;
        $.each(batdForm, (index, inputs)=>{
            // console.log(inputs);
            // return;
            
            if(inputs.value === ''){
                $(`.account-description-form [name="${inputs.name}"]`).addClass('is-invalid');
                Toast.fire({
                    icon: 'warning', 
                    title: 'Missing Fields',
                    text: 'Please fill all fields'
                });
                callFlag = false;
                return;
            }else{
                formObj[inputs.name] = inputs.value;
                console.log(formObj);
                $(`.account-description-form [name="${inputs.name}"]`).removeClass('is-invalid');
            }
        });

        if(callFlag){
            NewAccountDescription(
                'new-account-description',
                formObj,
                { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                callSucccess,
                callFailed
            )

            function callSucccess(response){
                // console.log(response);
                localStorage.setItem('account_description', 'created');
                location.reload();
                
            }
            function callFailed(error, status, jqXHR){
                try {
                    const response = JSON.parse(error.responseText);
                    console.log('Parsed Response:', response);
                    ToastError.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                } catch (e) {
                    console.log('Could not parse JSON response:', e);
                }
                // ToastError.fire({
                //     icon: 'error',
                //     title: "Fatal Error!",
                //     text: `Translated: ${error}`
                // });
                
            }
        }
    });

    $('.edit-sys-profile').on('click', function(e) {
        e.preventDefault();
        var callFlag = true;
        var profileObj = {};
        var form = $('#sys-profile-form').serializeArray();
    
        $.each(form, (index, input) => {
            if (input.value.trim() === '') {
                callFlag = false;
                $(`.sys-profile-form [name='${input.name}']`).addClass('is-invalid');
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing fields',
                    text: 'Please fill all fields'
                });
            } else {
                $(`.sys-profile-form [name='${input.name}']`).removeClass('is-invalid');
            }
            profileObj[input.name] = input.value;
        });

        if (callFlag) {
            $.ajax({
                type: "POST",
                url: 'edit-sys-info',
                data: profileObj,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                success: function(response){
                    localStorage.setItem('system-profile', 'updated');
                    location.reload();
                },
                error: function(error, status, jqXHR){
                    try {
                        const response = JSON.parse(error.responseText);
                        console.log('Parsed Response:', response);
                        ToastError.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    } catch (e) {
                        console.log('Could not parse JSON response:', e);
                    }
                }
            });
        }
    });

    $('.edit-user-privilege').on('click', function(e) {
        e.preventDefault();
        var userId = $(this).attr('id');
        console.log(userId);
        $.ajax({
            url: `/users/toggle-privilege/${userId}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                localStorage.setItem('user_privilege_updated', response.message);
                location.reload();
            },
            error: function(error) {
                Toast.fire({
                    icon: 'error',
                    title: 'An error occurred while updating the privilege.'
                });
            }
        });
    });
    

    var accountDescription = localStorage.getItem('account_description');
    var sysProfile = localStorage.getItem('system-profile');
    var coa = localStorage.getItem('coa');
    var privilegeMessage = localStorage.getItem('user_privilege_updated');
    if(accountDescription === 'created'){
        Toast.fire({
            icon: 'success',
            title: 'Account Description Created!'
        });
        localStorage.removeItem('account_description');
    }
    if(accountDescription === 'removed'){
        Toast.fire({
            icon: 'success',
            title: 'Account Description Removed!'
        });
        localStorage.removeItem('account_description');
    }
    if(accountDescription === 'updated'){
        Toast.fire({
            icon: 'success',
            title: 'Account Description Updated!'
        });
        localStorage.removeItem('account_description');
    }
    if(sysProfile === 'updated'){
        Toast.fire({
            icon: 'success',
            title: 'System Profile Updated'
        });
        localStorage.removeItem('system-profile');
    }
    if(coa === 'removed'){
        Toast.fire({
            icon: 'success',
            title: 'Account Removed'
        });
        localStorage.removeItem('coa');
    }
    if (privilegeMessage) {
        Toast.fire({ icon: 'success', title: privilegeMessage });
        localStorage.removeItem('user_privilege_updated');
    }
});