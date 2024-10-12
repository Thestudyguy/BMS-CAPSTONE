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
        $('#billing-account-type').attr('disabled');
        $.ajax({
            type: 'POST',
            url: `get-account-types-${$(this).val()}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response) {
                $('#billing-account-type').removeAttr('disabled');
                var select = '<select name="Type" class="form-control" id="type" disabled>';
                var hasOptions = false;
                $.each(response, (index, element) => {
                    $.each(element, (index, at) => {
                        if (Object.keys(at).length > 0) {
                            select += `<option value="${at.id}">${at.ServiceRequirements}</option>`;
                            hasOptions = true;
                        }
                    });
                });
                if (!hasOptions) {
                    select = `<select name="Type" class="form-control" id="billing-account-type" disabled>
                                <option value="" selected hidden>Category has no type</option>
                              </select>`;
                } else {
                    select += '</select>';
                }
                $('#billing-account-type').html(select);
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
    $('.save-account-description').on('click', function(e){
        var batdForm = $('.account-description-form').serializeArray();
        var formObj = {};
        var callFlag = true;
        $.each(batdForm, (index, inputs)=>{
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
            
        }
    });
});