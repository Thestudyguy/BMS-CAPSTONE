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
    let currentStep = 1;
    var income = window.journalIncome;   
    var expense = window.journalExpense;   
    var asset = window.journalAssets;   
    var liability = window.journalLiability;   
    var oe = window.journalOE;   
    var adjustment = window.journalOE;   
    var incomeObj = {}
    var expenseObj = {};
    var assetObj = {};
    var liabilityObj = {};
    var oeObj = {};
    var adjustmentObj = {};
    
    $.each(income, (index, income) =>{
        const incomeMonths = [];
        incomeMonths.push({
            incomeMonthName: income.month,
            value: income.amount
        });

        incomeObj[income.account] = {
            months: incomeMonths,
            startDate: income.start_date,
            endDate: income.end_date
        }
    });

    $.each(expense, (index, expense) => {
        const expenseMonths = [];
        expenseMonths.push({
            expenseMonthName: expense.month,
            value: expense.amount
        });
        expenseObj[expense.account] = {
            months: expenseMonths,
            startDate: expense.start_date,
            endDate: expense.end_date
        };
    });
    
    $.each(asset, (index, assets)=>{
        const accounts = [];
        accounts.push({
            assetAccount: assets.account,
            amount: assets.amount
        });
        assetObj[assets.asset_category] = {accounts};
    });
    
    
    $.each(liability, (index, lias)=>{
        liabilityObj = {
            account: lias.account,
            amount: lias.amount
        }
    });

    $.each(oe, (index, oes)=>{
        oeObj = {
            account: oes.account,
            amount: oes.amount
        }
    });

    let selectedAccount = '';
    $('#audit-income-category').on('change', function () {
        selectedAccount = $(this).val();
        $('.audit-income-form').removeClass('visually-hidden');
        $('.saved-audited-income-months').empty();
    });
    $('#audit-expense-category').on('change', function () {
        selectedAccount = $(this).val();
        $('.audit-expense-form').removeClass('visually-hidden');
        $('.saved-audited-expense-months').empty();
    });
//income
    $('.audit-income-start-date, .audit-income-end-date').on('change', function () {
        const startDate = $('.audit-income-start-date').val();
        const endDate = $('.audit-income-end-date').val();
        if ($('#audit-income-category').val() === '') {
            Toast.fire({
                icon: 'warning',
                title: 'Missing account',
                text: 'Please select an account'
            });
            return;
        }
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const monthsContainer = $('.audit-income-months-container');
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

            while (current <= end) {
                const monthYear = current.toLocaleString('default', { month: 'long', year: 'numeric' });
                monthInputs += `<div class="col-sm-6 my-2">
                    <div class="input-group">
                    <input type="text" class="form-control audit-income-month-input" name="${monthYear}" id="" placeholder='${monthYear}' value="">
                    </div>
                    </div>`;
                current.setMonth(current.getMonth() + 1);
            }
            monthInputs += `
            <div class="col-sm-6 my-2 text-right">
                <button type='button' class="btn btn-primary save-income-audit-months form-control">Save</button>
            </div>`;
            monthInputs += `</div>`;
            monthsContainer.html(monthInputs);
        }
        $('.months-container').on('input', '.audit-income-month-input', function () {
            formatValueInput(this);
        });
        console.log(selectedAccount);
        
    });

    $(document).on('click', '.save-income-audit-months', function(e){
        e.preventDefault();
        let selectedMonths = [];
        let hasValue = true;
        if(incomeObj.hasOwnProperty(selectedAccount)){
            Toast.fire({
                icon: 'warning',
                title: 'Account Already Exists!',
                text: 'You have already added this account.'
            });
            $('.audit-income-end-date').val('');
            $('.audit-income-start-date').val('');
            $('.audit-income-months-container').empty();
            return;
        }       

        $('.audit-income-month-input').each(function(){
            var incomeMonthName = $(this).attr('name');
            var monthVal = $(this).val();
            if(monthVal !== ''){
                hasValue = false;
                $(`[name='${$(this).attr('name')}']`).addClass('is-invalid');
                selectedMonths.push({
                    incomeMonthName,
                    value: $(this).val(),
                    startDate: $('.audit-income-start-date').val(),
                    endDate: $('.audit-income-end-date').val()
                });
            }
        });

        if(hasValue){
            $('.audit-income-month-input').each(function () {
                $(`[name='${$(this).attr('name')}']`).addClass('is-invalid');
            });
            Toast.fire({
                icon: 'warning',
                title: 'Missing fields',
                text: 'Please fill in at least one month input.'
            });
            return;
        }
        incomeObj[selectedAccount] = {
            months: selectedMonths,
            startDate: $('.audit-income-start-date').val(),
            endDate: $('.auditincome-end-date').val()
        };
        console.log(incomeObj);
        $('#saved-audited-income-months').empty();
        $.each(incomeObj, (account, element) => {
            var tableHTML = `<table class="table table-hover client-journal-accounts">`;
            var accountParts = account.split('_');
            tableHTML += `
                <tr class="client-journal-accounts" data-widget="expandable-table" aria-expanded="false">
                    <td>
                        ${accountParts[1]} - ${element.startDate} - ${element.endDate}
                        <span class="text-sm fw-bold float-right remove-audit-income" id="${account}"><i class="fas fa-times"></i></span>
                    </td>
                </tr>
                <tr class="expandable-body bg-light client-journal-accounts">
                    <td>
                        <div class="p-0">
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
            $('#saved-audited-income-months').append(tableHTML);
        });
        $('.audit-income-months-container').empty();
        $('#audit-income-category').val('');
        $('.audit-income-end-date').val('');
        $('.audit-income-start-date').val('');
        selectedAccount = '';
        hasValue = true;
    });
//end of income


//expense
$('.audit-expense-start-date, .audit-expense-end-date').on('change', function () {
    const startDate = $('.audit-expense-start-date').val();
    const endDate = $('.audit-expense-end-date').val();
    if ($('#audit-expense-category').val() === '') {
        Toast.fire({
            icon: 'warning',
            title: 'Missing account',
            text: 'Please select an account'
        });
        return;
    }
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const monthsContainer = $('.audit-expense-months-container');
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

        while (current <= end) {
            const monthYear = current.toLocaleString('default', { month: 'long', year: 'numeric' });
            monthInputs += `<div class="col-sm-6 my-2">
                <div class="input-group">
                <input type="text" class="form-control audit-expense-month-input" name="${monthYear}" id="" placeholder='${monthYear}' value="">
                </div>
                </div>`;
            current.setMonth(current.getMonth() + 1);
        }
        monthInputs += `
        <div class="col-sm-6 my-2 text-right">
            <button type='button' class="btn btn-primary save-expense-audit-months form-control">Save</button>
        </div>`;
        monthInputs += `</div>`;
        monthsContainer.html(monthInputs);
    }
    $('.audit-expense-months-container').on('input', '.audit-expense-month-input', function () {
        formatValueInput(this);
    });
    console.log(selectedAccount);
    
});

    $(document).on('click', '.save-expense-audit-months', function(e){
        e.preventDefault();
        let selectedMonths = [];
        let hasValue = true;
        if(expenseObj.hasOwnProperty(selectedAccount)){
            Toast.fire({
                icon: 'warning',
                title: 'Account Already Exists!',
                text: 'You have already added this account.'
            });
            $('.audit-iexpensencome-end-date').val('');
            $('.audit-expense-start-date').val('');
            $('.audit-expense-months-container').empty();
            return;
        }       

        $('.audit-expense-month-input').each(function(){
            var expenseMonthName = $(this).attr('name');
            var monthVal = $(this).val();
            if(monthVal !== ''){
                hasValue = false;
                $(`[name='${$(this).attr('name')}']`).addClass('is-invalid');
                selectedMonths.push({
                    expenseMonthName,
                    value: $(this).val(),
                    startDate: $('.audit-expense-start-date').val(),
                    endDate: $('.audit-expense-end-date').val()
                });
            }
        });

        if(hasValue){
            $('.audit-expense-month-input').each(function () {
                $(`[name='${$(this).attr('name')}']`).addClass('is-invalid');
            });
            Toast.fire({
                icon: 'warning',
                title: 'Missing fields',
                text: 'Please fill in at least one month input.'
            });
            return;
        }
        expenseObj[selectedAccount] = {
            months: selectedMonths,
            startDate: $('.audit-expense-start-date').val(),
            endDate: $('.audit-expense-end-date').val()
        };
        console.log(expenseObj);
        $('#saved-audited-expense-months').empty();
        $.each(expenseObj, (account, element) => {
            var tableHTML = `<table class="table table-hover client-journal-accounts">`;
            var accountParts = account.split('_');
            tableHTML += `
                <tr class="client-journal-accounts" data-widget="expandable-table" aria-expanded="false">
                    <td>
                        ${accountParts[1]} - ${element.startDate} - ${element.endDate}
                        <span class="text-sm fw-bold float-right remove-audit-expense" id="${account}"><i class="fas fa-times"></i></span>
                    </td>
                </tr>
                <tr class="expandable-body bg-light client-journal-accounts">
                    <td>
                        <div class="p-0">
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
                        <td class='text-left'>${month.expenseMonthName}</td>
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
            $('#saved-audited-expense-months').append(tableHTML);
        });
        $('.audit-expense-months-container').empty();
        $('#audit-expense-category').val('');
        $('.audit-expense-end-date').val('');
        $('.audit-expense-start-date').val('');
        selectedAccount = '';
        hasValue = true;
    });
//end of expense

//assets 
$('.save-audit-asset-info').on('click', function(e) {
    e.preventDefault();
    
    var form = $('.journal-audit-asset-form').serializeArray();
    var accountType = ''; 
    var assetAccount = '';
    var assetAmount = '';

    $.each(form, (index, input) => {
        console.log(input);
        
        if (input.value === '') {
            $(`[name='${input.name}']`).addClass('is-invalid');
            Toast.fire({
                icon: 'warning',
                title: 'Missing Fields',
                text: 'Please fill all fields'
            });
            return false;
        }

        $(`[name='${input.name}']`).removeClass('is-invalid');

        if (input.name === 'assetType') {
            accountType = input.value;
        } else if (input.name === 'assetAccount') {
            assetAccount = input.value;
        } else if (input.name === 'assetAmount') {
            assetAmount = input.value;
        }
    });

    if (!accountType || !assetAccount || !assetAmount) {
        console.error('Required fields are missing.');
        return;
    }
    var trimmedCat = accountType.split('_');
    console.log(trimmedCat[0]);
    
    if (!assetObj[trimmedCat[0]]) {
        assetObj[trimmedCat[0]] = {
            accounts: []
        };
    }
    assetObj[trimmedCat[0]].accounts.push({
        assetAccount: assetAccount.split('_')[0],
        amount: assetAmount
    });

    console.log("Asset Object:", assetObj);
});

//assets


    //removal functions
    
    $(document).on('click', '.remove-audit-income', function(){
        $(this).closest('tr').remove();
        var account = $(this).attr('id');
        delete incomeObj[account];
        $('#saved-audited-income-months').empty();
        $.each(incomeObj, (account, element) => {
            var tableHTML = `<table class="table table-hover client-journal-accounts">`;
            var accountParts = account.split('_');
            tableHTML += `
                <tr class="client-journal-accounts" data-widget="expandable-table" aria-expanded="false">
                    <td>
                        ${accountParts[1]} - ${element.startDate} - ${element.endDate}
                        <span class="text-sm fw-bold float-right remove-audit-income" id="${account}"><i class="fas fa-times"></i></span>
                    </td>
                </tr>
                <tr class="expandable-body bg-light client-journal-accounts">
                    <td>
                        <div class="p-0">
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
                        <td>${month.expenseMonthName}</td>
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
            $('#saved-audited-income-months').append(tableHTML);
        });
    });

    $(document).on('click', '.remove-audit-expense', function(e){
        delete expenseObj[$(this).attr('id')];
        $(this).closest('tr').remove();
        console.log(expenseObj);
        $('#saved-audited-expense-months').empty();
        $.each(expenseObj, (account, element) => {
            var tableHTML = `<table class="table table-hover client-journal-accounts">`;
            var accountParts = account.split('_');
            tableHTML += `
                <tr class="client-journal-accounts" data-widget="expandable-table" aria-expanded="false">
                    <td>
                        ${accountParts[1]} - ${element.startDate} - ${element.endDate}
                        <span class="text-sm fw-bold float-right remove-audit-expense" id="${account}"><i class="fas fa-times"></i></span>
                    </td>
                </tr>
                <tr class="expandable-body bg-light client-journal-accounts">
                    <td>
                        <div class="p-0">
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
                        <td class='text-left'>${month.expenseMonthName}</td>
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
            $('#saved-audited-expense-months').append(tableHTML);
        });
    });

    $(document).on('click', '.remove-audited-asset', function (e) {
        e.preventDefault();
        console.log(assetObj);
        console.log($(this).attr('id'));
        var table = '<tbody class="append-audit-asset-accounts">';
        for (let category in assetObj) {
            if (assetObj[category].assetData) {
                assetObj[category].assetData = assetObj[category].assetData.filter(
                    item => item.assetAccount !== $(this).attr('id')
                );
            }
        }
        $.each(assetObj, (index, asset)=>{
            table += `<td>${index}</td>`
            $.each(asset.assetAccount, (index, account)=>{
            table += `<td>${account.account}</td>`
            table += `<td>${account.amount.toLocaleString()}</td>`
            });
        });
        table += '</tbody>';

        console.log(assetObj);
    });
//end of removal functions
    $('.audit-next-btn').on('click', function () {
        if (currentStep === 1) {
            if(Object.keys(incomeObj).length === 0){
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Data',
                    text: 'Please fill and save data for at least one entry.'
                });
                return;
            }
        }


        if (currentStep === 2) {
            if (Object.keys(expenseObj).length === 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Data',
                    text: 'Please fill and save data for at least one entry.'
                });
                return;
            }
        
            var hasLessDirectCost = false;
            var hasOperatingExpenses = false;
        
            $.each(expenseObj, (index, incomeData) => {
                var expenseAccount = index.split('_');
                var expenseType = expenseAccount[2];
        
                if (expenseType === 'Less Direct Cost') {
                    hasLessDirectCost = true;
                } else if (expenseType === 'Operating Expenses') {
                    hasOperatingExpenses = true;
                }
            });
        
            if (!hasLessDirectCost || !hasOperatingExpenses) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Data',
                    text: 'Both "Less Direct Cost" and "Operating Expenses" entries are required.'
                });
                return;
            }

        }

        if (currentStep === 3) {
           
        }
        if (currentStep === 4) {
        
        }
        if (currentStep === 5) {
        
        }
        if (currentStep === 6) {
        
        }
        
        
        if (currentStep < 7) {
            $('.multi-step-journal-audit').hide();
            currentStep++;
            showStep(currentStep);
        }

        updateStepIndicator(currentStep);

        if (currentStep === 7) {
            $('.audit-next-btn').hide();
            $('.audit-save-btn').show();
        } else {
            $('.audit-next-btn').show();
            $('.audit-save-btn').hide();
        }

        if (currentStep === 1) {
            $('.audit-prev-btn').hide();
        } else {
            $('.audit-prev-btn').show();
        }
    });

    $('.audit-prev-btn').on('click', function () {
        if (currentStep > 1) {
            $('.multi-step-journal-audit').hide();
            currentStep--;
            showStep(currentStep);
        }

        updateStepIndicator(currentStep);
        $('.audit-next-btn').show();
        $('.audit-save-btn').hide();

        if (currentStep === 1) {
            $('.audit-prev-btn').hide();
        } else {
            $('.audit-prev-btn').show();
        }
    });

    function showStep(step) {
        $('.multi-step-journal-audit').hide();
        $('.multi-step-journal-audit').eq(step - 1).show();
    }

    function updateStepIndicator(step) {
        $('.audit-step').removeClass('active');
        $('.audit-indicator-line').removeClass('active');
        $('.audit-step').each(function (index) {
            if (index < step) {
                $(this).addClass('active');
                $(this).next('.audit-indicator-line').addClass('active');
            }
        });
    }

    showStep(currentStep);
    updateStepIndicator(currentStep);

    if (currentStep === 1) {
        $('.audit-prev-btn').hide();
    }

    
});