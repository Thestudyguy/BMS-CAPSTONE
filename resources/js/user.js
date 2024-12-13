import { NewUser } from "./ajax";
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
    });
    

    $('.new-user-save').on('click', function(e){
        var userForm = $('.new-user-form').serializeArray();
        var userObj = {};
        var callFlag = true;
        $.each(userForm, (index, elements)=>{
            if(elements.value === ''){
                callFlag = false;
                $(`.new-user-form [name='${elements.name}']`).addClass('is-invalid');
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please fill all fields'
                });
                return;
            }else{
                userObj[elements.name] = elements.value;
                $(`.new-user-form [name='${elements.name}']`).removeClass('is-invalid');
            }
      
        });        
        if(callFlag){
            NewUser(
                'new-user',
                userObj,
                { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                CallSuccess,
                CallFailed
            )
            function CallSuccess(response){
                localStorage.setItem('user', 'created');
                location.reload();
            }
            function CallFailed(jqXHR, textStatus, errorThrown){
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
        }
    });

    $('.remove-user').on('click', function(){
        var idRef = $(this).attr('id');
        $.ajax({
            url: `remove-user-${idRef}`,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response){
                console.log(response);
                localStorage.setItem('user', 'removed');
                location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log(jqXHR);
            },
        });
        
    });

    $('.edit-user-button').on('click', function(){
        // var userUpdateForm = $('.edit-user-form').serializeArray();
        var userUpdateForm = $(this).closest('.modal').find('form').serializeArray();
        console.log(userUpdateForm);
        var callFlag = true;
        var userUpdatedObj = {};
        $.each(userUpdateForm, (index, elements)=>{
            if(elements.value === ''){
                callFlag = false;
                $(`[name='${elements.name}']`).addClass('is-invalid');
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please fill all fields'
                });
                return;
            }else{
                userUpdatedObj[elements.name] = elements.value;
                $(`[name='${elements.name}']`).removeClass('is-invalid');
            }
        });
        if(callFlag){
            $.ajax({
                url: 'update-user',
                type: 'POST',
                data: userUpdatedObj,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
                success: function(resposne){
                    localStorage.setItem('user', 'updated');
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown){
                    ToastError.fire({
                        icon: 'error',
                        title: 'Fatal Error!',
                        text: 'Try reloading the page, if issue persist contact developer'
                    });
                },
            });
        }
    });

    var user = localStorage.getItem('user');
    if(user === 'created'){
        Toast.fire({
            icon: 'success',
            title: 'User Created!',
            text: 'User created successfuly'
        });
        localStorage.removeItem('user');
    }
    if(user === 'removed'){
        Toast.fire({
            icon: 'success',
            title: 'User Removed!',
            text: 'User removed successfuly'
        });
        localStorage.removeItem('user');
    }
    if(user === 'updated'){
        Toast.fire({
            icon: 'success',
            title: 'User Updated!',
            text: 'User Updated successfuly'
        });
        localStorage.removeItem('user');
    }

    $('.search-users').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.users-table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $('#filter-users').on('change', function() {
        var selectedValue = $(this).val();
        $('.users-table tbody tr').each(function() {
            var rowCategory = $(this).data('category');
            if (selectedValue === "clear") {
                $(this).show();
            } else if (selectedValue === "" || rowCategory === selectedValue) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});