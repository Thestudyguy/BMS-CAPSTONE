$(document).ready(function () {
    let currentStep = 1;

    // Toast configurations
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });

    var ToastError = Swal.mixin({
        toast: false,
        position: 'bottom-end',
    });
    
    // let selectedMonths = [];
    let selectedAccount = '';
    let expensesObj = {};
    let selectedIncomeAccount = '';
    let incomeObj = {};
    let assetObj = {};
    $('#expense-category').on('change', function () {
        selectedAccount = $('#expense-category').val();
        $('.expense-form').removeClass('visually-hidden');
        $('.months-container').empty();
        $('.save-expense').addClass('visually-hidden');
    });

    $('.start-date, .end-date').on('change', function () {
        const startDate = $('.start-date').val();
        const endDate = $('.end-date').val();
        if($('#expense-category').val() === ''){
            Toast.fire({
                icon: 'warning',
                title: 'Missing account',
                text: 'Please select an account'
            });
            return;
        }
        if (startDate && endDate) {
            // $('.save-expense').removeClass('visually-hidden');
            const start = new Date(startDate);
            const end = new Date(endDate);
            const monthsContainer = $('.months-container');
            var monthInputs = `<div class="row">`;

            const monthDifference = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start.getMonth());
            if (monthDifference > 12) {
                ToastError.fire({
                    icon: 'error',
                    title: 'The selected period exceeds 12 months.'
                });
                return;
            }

            const fiscalYearEnd = new Date(start);
            fiscalYearEnd.setMonth(fiscalYearEnd.getMonth() + 11);

            if (end > fiscalYearEnd) {
                ToastError.fire({
                    icon: 'error',
                    title: 'The selected period must not exceed the fiscal year based on the start date.'
                });
                return;
            }

            monthsContainer.empty();
            monthInputs += ``;
            let current = new Date(start);
            console.log(current);

            while (current <= end) {
                const monthYear = current.toLocaleString('default', { month: 'long', year: 'numeric' });
                monthInputs += `<div class="col-sm-6 my-2">
                    <div class="input-group">
                    <input type="text" class="form-control month-input" name="${monthYear}" id="" placeholder='${monthYear}' value="">
                    </div>
                    </div>`;
                current.setMonth(current.getMonth() + 1);
            }
            monthInputs += `
            <div class="col-sm-6 my-2 text-right">
                <button type='button' class="btn btn-primary save-months form-control">Save</button>
            </div>`;
            monthInputs += `</div>`;

            monthsContainer.html(monthInputs);

           
        }
        $('.months-container').on('input', '.month-input', function () {
            formatValueInput(this);
        });
        
    
        
    });
    $(document).on('click', '.save-months', function (e) {
        e.preventDefault();
        let selectedMonths = [];
        let hasValue = true;
        console.log('selected account', selectedAccount);
        if (expensesObj.hasOwnProperty(selectedAccount)) {
            Toast.fire({
                icon: 'warning',
                title: 'Account Already Added',
                text: 'You have already added this account.'
            });
            $('.end-date').val('');
            $('.start-date').val('');
            $('.months-container').empty();
            return false;
        }
    
        $('.month-input').each(function () {
            var monthName = $(this).attr('name');
            var monthVal = $(this).val();
            console.log(`Month: ${monthName}, Value: ${$(this).val()}`);
            if (monthVal !== '') {
                hasValue = false;
                $(`[name='${$(this).attr('name')}']`).addClass('is-invalid');
                selectedMonths.push({
                            monthName,
                            value: $(this).val(),
                            account: selectedAccount,
                            startDate: $('.start-date').val(),
                            endDate: $('.end-date').val()
                        });
            }
        });
        
        if(hasValue){
            $('.month-input').each(function(){
                $(`[name='${$(this).attr('name')}']`).addClass('is-invalid');
            });
            Toast.fire({
                icon: 'warning',
                title: 'Missing fields',
                text: 'Please fill in at least one month input.'
            });
            return;
        }

        expensesObj[selectedAccount] = {
            months: selectedMonths,
            startDate: $('.start-date').val(),
            endDate: $('.end-date').val()
        };
        $('#saved-months').empty();
        $.each(expensesObj, (account, element) => {
            var tableHTML = `<table class="table table-hover client-journal-accounts">`;
            var accountParts = account.split('_');
            tableHTML += `
                <tr class="client-journal-accounts" data-widget="expandable-table" aria-expanded="false">
                    <td>
                        ${accountParts[1]} - ${element.startDate} - ${element.endDate}
                        <span class="text-sm fw-bold float-right remove-saved-account" id="${account}"><i class="fas fa-times"></i></span>
                    </td>
                </tr>
                <tr class="expandable-body bg-light client-journal-accounts">
                    <td>
                        <div class="p-0 text-center">
                            <table class="table table-hover float-left">
                            <thead>
                             <tr>
                            <td class='text-left'>Month</td>
                            <td class='text-left'>Amount</td>
                           </tr>
                            </thead>
            `;
            $.each(element.months, (index, month) => {
                tableHTML += `
                    <tr>
                        <td class='text-left'>${month.monthName}</td>
                        <td class='text-left'>${month.value}</td>
                    </tr>
                `;
            });
            tableHTML += `
                            </table>
                        </div>
                    </td>
                </tr>
            `;
            tableHTML += `</table>`;
            $('#saved-months').append(tableHTML);
        });
        console.log(expensesObj);
        $('.months-container').empty();
        $('#expense-category').val('');
        $('.end-date').val('');
        $('.start-date').val('');
        selectedAccount = '';
        hasValue = true;
    });
    
    
        $(document).on('click', '.remove-saved-account', function(e){
        const accountId = $(this).attr('id');
    
        delete expensesObj[accountId];
        console.log('Current expensesObj:', expensesObj);
        
        $('#saved-months').empty();
        $.each(expensesObj, (account, element) => {
            var tableHTML = `<table class="table table-hover client-journal-accounts">`;
            var accountParts = account.split('_');
            tableHTML += `
                <tr class="client-journal-accounts" data-widget="expandable-table" aria-expanded="false">
                    <td>
                        ${accountParts[1]} - ${element.startDate} - ${element.endDate}
                        <span class="text-sm fw-bold float-right remove-saved-account" id="${account}"><i class="fas fa-times"></i></span>
                    </td>
                </tr>
                <tr class="expandable-body bg-light client-journal-accounts">
                    <td>
                        <div class="p-0 text-center">
                            <table class="table table-hover float-left">
                            <thead>
                             <tr>
                                <td>Month</td>
                                <td>Amount</td>
                             </tr>
                            </thead>
            `;
            $.each(element.months, (index, month) => {
                tableHTML += `
                    <tr>
                        <td>${month.monthName}</td>
                        <td>${month.value}</td>
                    </tr>
                `;
            });
            tableHTML += `
                            </table>
                        </div>
                    </td>
                </tr>
            `;
            tableHTML += `</table>`;
            $('#saved-months').append(tableHTML);
        });
        
    });


    //income
    $('#income-category').on('change', function(){
        $('.income-form').removeClass('visually-hidden');
        selectedIncomeAccount = $('#income-category').val();
        $('.income-months-container').empty();
        // $('.save-expense').addClass('visually-hidden');
    });

    $('.income-start-date, .income-end-date').on('change', function(){
        const startDate = $('.income-start-date').val();
        const endDate = $('.income-end-date').val();

        if($('#income-category').val() === ''){
            Toast.fire({
                icon: 'warning',
                title: 'Missing account',
                text: 'Please select an account'
            });
            return;
        }
        if(startDate && endDate){
            const start = new Date(startDate);
            const end = new Date(endDate);
            const monthsContainer = $('.income-months-container');
            var monthInputs = `<div class="row">`;

            const monthDifference = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start.getMonth());
            if (monthDifference > 12) {
                ToastError.fire({
                    icon: 'error',
                    title: 'The selected period exceeds 12 months.'
                });
                return;
            }

            const fiscalYearEnd = new Date(start);
            fiscalYearEnd.setMonth(fiscalYearEnd.getMonth() + 11);

            if (end > fiscalYearEnd) {
                ToastError.fire({
                    icon: 'error',
                    title: 'The selected period must not exceed the fiscal year based on the start date.'
                });
                return;
            }

            monthsContainer.empty();
            monthInputs += ``;
            let current = new Date(start);
            console.log(current);

            while (current <= end) {
                const monthYear = current.toLocaleString('default', { month: 'long', year: 'numeric' });
                monthInputs += `<div class="col-sm-6 my-2">
                    <div class="input-group">
                    <input type="text" class="form-control income-month-input" name="${monthYear}" id="" placeholder='${monthYear}' value="">
                    </div>
                    </div>`;
                current.setMonth(current.getMonth() + 1);
            }
            monthInputs += `
            <div class="col-sm-6 my-2 text-right">
                <button type='button' class="btn btn-primary save-expense-months form-control">Save</button>
            </div>`;
            monthInputs += `</div>`;

            monthsContainer.html(monthInputs);
        }
        $('.income-months-container').on('input', '.income-month-input', function () {
            formatValueInput(this);
        });
    });

    $(document).on('click', '.save-expense-months', function(e){
        e.preventDefault();
        let selectedMonths = [];
        let hasValue = true;
        if(incomeObj.hasOwnProperty(selectedIncomeAccount)){
            Toast.fire({
                icon: 'warning',
                title: 'Account Already Added',
                text: 'You have already added this account.'
            });
            $('.income-end-date').val('');
            $('.income-start-date').val('');
            $('.income-months-container').empty();
            return;
        }

        $('.income-month-input').each(function(){
            var incomeMonthName = $(this).attr('name');
            var incomeMonthVal = $(this).val();
            if(incomeMonthVal !== ''){
                hasValue = false;
                $(`[name='${$(this).attr('name')}']`).addClass('is-invalid');
                selectedMonths.push({
                    incomeMonthName,
                    value: $(this).val(),
                    account: selectedIncomeAccount,
                    startDate: $('.income-start-date').val(),
                    endDate: $('.income-end-date').val()
                });
            }
        });
        if(hasValue){
            $('.income-month-input').each(function(){
                $(`[name='${$(this).attr('name')}']`).addClass('is-invalid');
            });
            Toast.fire({
                icon: 'warning',
                title: 'Missing fields',
                text: 'Please fill in at least one month input.'
            });
            return;
        }
        incomeObj[selectedIncomeAccount] = {
            months: selectedMonths,
            startDate: $('.income-start-date').val(),
            endDate: $('.income-end-date').val()
        };
        $('#saved-income-months').empty();
        $.each(incomeObj, (account, element) => {
            var tableHTML = `<table class="table table-hover client-journal-accounts">`;
            var accountParts = account.split('_');
            tableHTML += `
                <tr class="client-journal-accounts" data-widget="expandable-table" aria-expanded="false">
                    <td>
                        ${accountParts[1]} - ${element.startDate} - ${element.endDate}
                        <span class="text-sm fw-bold float-right remove-saved-account" id="${account}"><i class="fas fa-times"></i></span>
                    </td>
                </tr>
                <tr class="expandable-body bg-light client-journal-accounts">
                    <td>
                        <div class="p-0 text-center">
                            <table class="table table-hover float-left">
                            <thead>
                             <tr>
                            <td class='text-left'>Month</td>
                            <td class='text-left'>Amount</td>
                           </tr>
                            </thead>
            `;
            $.each(element.months, (index, month) => {
                tableHTML += `
                    <tr>
                        <td class='text-left'>${month.incomeMonthName}</td>
                        <td class='text-left'>${month.value}</td>
                    </tr>
                `;
            });
            tableHTML += `
                            </table>
                        </div>
                    </td>
                </tr>
            `;
            tableHTML += `</table>`;
            $('#saved-income-months').append(tableHTML);
        });
        console.log(incomeObj);
        $('.income-months-container').empty();
        $('#income-category').val('');
        $('.income-end-date').val('');
        $('.income-start-date').val('');
        selectedAccount = '';
        hasValue = true;
    });

    $(document).on('click', '.remove-saved-account', function(e){
        const accountId = $(this).attr('id');
    
        delete incomeObj[accountId];
        console.log('Current incomeObj:', incomeObj);
        
        $('#saved-income-months').empty();
        $.each(incomeObj, (account, element) => {
            var tableHTML = `<table class="table table-hover client-journal-accounts">`;
            var accountParts = account.split('_');
            tableHTML += `
                <tr class="client-journal-accounts" data-widget="expandable-table" aria-expanded="false">
                    <td>
                        ${accountParts[1]} - ${element.startDate} - ${element.endDate}
                        <span class="text-sm fw-bold float-right remove-saved-account" id="${account}"><i class="fas fa-times"></i></span>
                    </td>
                </tr>
                <tr class="expandable-body bg-light client-journal-accounts">
                    <td>
                        <div class="p-0 text-center">
                            <table class="table table-hover float-left">
                            <thead>
                             <tr>
                                <td>Month</td>
                                <td>Amount</td>
                             </tr>
                            </thead>
            `;
            $.each(element.months, (index, month) => {
                tableHTML += `
                    <tr>
                        <td>${month.incomeMonthName}</td>
                        <td>${month.value}</td>
                    </tr>
                `;
            });
            tableHTML += `
                            </table>
                        </div>
                    </td>
                </tr>
            `;
            tableHTML += `</table>`;
            $('#saved-income-months').append(tableHTML);
        });
        
    });
    //income

    //asset 
    $(document).on('change', '#asset_account', function(){
        let at = $(this).val();
        $.ajax({
            type: 'POST',
            url: `get-account-types-${at}`,
            // data: at,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response){
                let atElement = '';   
                $.each(response, (index, ats)=>{
                    $.each(ats, (index, atsData)=>{
                        console.log(atsData.AccountName);
                        atElement += `
                            <option value="${atsData.AccountName}_${atsData.id}">${atsData.AccountName}</option>
                        `;
                    });                    
                });
                
                // atElement += '</select>'; not recommended
                $('#asset_account_name').html(atElement);
            },
            error: function(error, stat, jqXHR){
                console.error(error);
            }
        });
    });
    // let assetFlag = true;
    // $('.save-asset-info').click(function(e){
    //     assetFlag = true;
    //     e.preventDefault();
    //     let assetForm = $('.journal-asset-form').serializeArray();
    //     $.each(assetForm, (index, assetData)=>{
    //         if(assetData.value === ''){
    //             assetFlag = false;
    //             $(`.journal-asset-form [name='${assetData.name}']`).addClass('is-invalid');
    //             Toast.fire({
    //                 icon: 'warning',
    //                 title: 'Missing Fields',
    //                 text: 'Please fill all fields'
    //             });
    //             return false;
    //         }
    //         $(`.journal-asset-form [name='${assetData.name}']`).removeClass('is-invalid');
    //         assetObj[assetData.name] = assetData.value;
    //     });        
        
    // });
    let assetFlag = true;
    $('.next-btn').on('click', function () {
        console.log(assetObj);
        
        // if (currentStep === 1) {
        //     if (Object.keys(expensesObj).length === 0) {
        //         Toast.fire({
        //             icon: 'warning',
        //             title: 'Missing Data',
        //             text: 'Please fill and save data for at least one entry.'
        //         });
        //         return;
        //     }
        // }

        // if (currentStep === 2) {
        //     if (Object.keys(incomeObj).length === 0) {
        //         Toast.fire({
        //             icon: 'warning',
        //             title: 'Missing Data',
        //             text: 'Please fill and save data for at least one entry.'
        //         });
        //         return;
        //     }
        // }

         if (currentStep === 3) {
            let assetForm = $('.journal-asset-form').serializeArray();
            $.each(assetForm, (index, assetData)=>{
                if(assetData.value === ''){
                    assetFlag = false;
                    $(`.journal-asset-form [name='${assetData.name}']`).addClass('is-invalid');
                    Toast.fire({
                        icon: 'warning',
                        title: 'Missing Fields',
                        text: 'Please fill all fields'
                    });
                    return false;
                }
                $(`.journal-asset-form [name='${assetData.name}']`).removeClass('is-invalid');
                assetObj[assetData.name] = assetData.value;
            });  
            if(!assetFlag){
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please fill all fields'
                });
                return;
            }
        }
        if (currentStep < 6) {
            $('.multi-step-journal').hide();
            currentStep++;
            showStep(currentStep);
        }
    
        updateStepIndicator(currentStep);
    
        if (currentStep === 6) {
            $('.next-btn').hide();
            $('.save-btn').show();
        } else {
            $('.next-btn').show();
            $('.save-btn').hide();
        }
    
        if (currentStep === 1) {
            $('.prev-btn').hide();
        } else {
            $('.prev-btn').show();
        }
    });
    
    $('.prev-btn').on('click', function () {
        if (currentStep > 1) {
            $('.multi-step-journal').hide();
            currentStep--;
            showStep(currentStep);
        }
    
        updateStepIndicator(currentStep);
        $('.next-btn').show();
        $('.save-btn').hide();
    
        if (currentStep === 1) {
            $('.prev-btn').hide();
        } else {
            $('.prev-btn').show();
        }
    });
    
    function showStep(step) {
        $('.multi-step-journal').hide();
        $('.multi-step-journal').eq(step - 1).show();
    }
    
    function updateStepIndicator(step) {
        $('.step').removeClass('active');
        $('.indicator-line').removeClass('active');
        $('.step').each(function (index) {
            if (index < step) {
                $(this).addClass('active');
                $(this).next('.indicator-line').addClass('active');
            }
        });
    }
    
    showStep(currentStep);
    updateStepIndicator(currentStep);
    
    if (currentStep === 1) {
        $('.prev-btn').hide();
    }
    
});