import { ClientJournalEntry } from "./ajax";

$(document).ready(function () {
    let currentStep = 1;
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
    let liabilityObj = {};
    let oeObj = {};
    let adjustmentObj = {};
    $('#expense-category').on('change', function () {
        selectedAccount = $('#expense-category').val();
        $('.expense-form').removeClass('visually-hidden');
        $('.months-container').empty();
        $('.save-expense').addClass('visually-hidden');
    });

    $('.start-date, .end-date').on('change', function () {
        const startDate = $('.start-date').val();
        const endDate = $('.end-date').val();
        if ($('#expense-category').val() === '') {
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

        if (hasValue) {
            $('.month-input').each(function () {
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
        $('.months-container').empty();
        $('#expense-category').val('');
        $('.end-date').val('');
        $('.start-date').val('');
        selectedAccount = '';
        hasValue = true;
    });


    $(document).on('click', '.remove-saved-account', function (e) {
        const accountId = $(this).attr('id');

        delete expensesObj[accountId];

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
    $('#income-category').on('change', function () {
        $('.income-form').removeClass('visually-hidden');
        selectedIncomeAccount = $('#income-category').val();
        $('.income-months-container').empty();
        // $('.save-expense').addClass('visually-hidden');
    });

    $('.income-start-date, .income-end-date').on('change', function () {
        const startDate = $('.income-start-date').val();
        const endDate = $('.income-end-date').val();

        if ($('#income-category').val() === '') {
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

    $(document).on('click', '.save-expense-months', function (e) {

        e.preventDefault();
        let selectedMonths = [];
        let hasValue = true;
        if (incomeObj.hasOwnProperty(selectedIncomeAccount)) {
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

        $('.income-month-input').each(function () {
            var incomeMonthName = $(this).attr('name');
            var incomeMonthVal = $(this).val();
            if (incomeMonthVal !== '') {
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
        if (hasValue) {
            $('.income-month-input').each(function () {
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
        console.log(incomeObj);
        
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
        $('.income-months-container').empty();
        $('#income-category').val('');
        $('.income-end-date').val('');
        $('.income-start-date').val('');
        selectedAccount = '';
        hasValue = true;
    });

    $(document).on('click', '.remove-saved-account', function (e) {
        const accountId = $(this).attr('id');

        delete incomeObj[accountId];

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
    $(document).on('change', '#asset_account', function () {
        let at = $(this).val();
        console.log(at);
        
        $.ajax({
            type: 'POST',
            url: `get-accounts-${at}`,
            // data: at,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                console.log(response);
                
                let atElement = '';
                $.each(response, (index, ats) => {
                    $.each(ats, (index, atsData) => {
                        console.log(atsData);
                        atElement += `
                            <option value="${atsData.AccountName}_${atsData.id}">${atsData.AccountName}</option>
                        `;
                    });
                });

                // atElement += '</select>'; not recommended
                $('#asset_account_name').html(atElement);
            },
            error: function (error, stat, jqXHR) {
                console.error(error);
            }
        });
    });
    //liability
    $(document).on('change', '#liability_account', function () {
        let at = $(this).val();
        $.ajax({
            type: 'POST',
            url: `get-accounts-${at}`,
            // data: at,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                let atElement = '';
                $.each(response, (index, lts) => {
                    $.each(lts, (index, ltsData) => {
                        atElement += `
                            <option value="${ltsData.AccountName}_${ltsData.id}">${ltsData.AccountName}</option>
                        `;
                    });
                });

                // atElement += '</select>'; not recommended
                $('#liability_account_name').html(atElement);
            },
            error: function (error, stat, jqXHR) {
                console.error(error);
            }
        });
    });
    //oe
    $(document).on('change', '#oe_account', function () {
        let at = $(this).val();
        $.ajax({
            type: 'POST',
            url: `get-accounts-${at}`,
            // data: at,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                let atElement = '';
                $.each(response, (index, lts) => {
                    $.each(lts, (index, ltsData) => {
                        atElement += `
                            <option value="${ltsData.AccountName}_${ltsData.id}">${ltsData.AccountName}</option>
                        `;
                    });
                });

                // atElement += '</select>'; not recommended
                $('#oe_account_name').html(atElement);
            },
            error: function (error, stat, jqXHR) {
                console.error(error);
            }
        });
    });
    

$('.save-asset-info').on('click', function(e) {
    e.preventDefault();

    var hasErrors = false; 
    var assetForm = $('.journal-asset-form').serializeArray(); 
    var accountType = ''; 
    var assetAccount = '';
    var assetAmount = '';

    $('.journal-asset-form input, .journal-asset-form select').removeClass('is-invalid');

    $.each(assetForm, function(index, data) {
        if (data.value === '') {
            $(`.journal-asset-form [name='${data.name}']`).addClass('is-invalid');
            hasErrors = true;
        }
        if (data.name === 'assetType') {
            accountType = data.value;
        } else if (data.name === 'assetAccount') {
            assetAccount = data.value;
        } else if (data.name === 'assetAmount') {
            assetAmount = data.value;
        }
    });

    if (hasErrors) {
        Toast.fire({
            icon: 'warning',
            title: 'Missing Fields',
            text: 'Please fill all fields'
        });
        return;
    }

    if (!assetObj[accountType]) {
        assetObj[accountType] = {
            accounts: []
        };
    }

    assetObj[accountType].accounts.push({
        assetAccount: assetAccount,
        amount: assetAmount
    });

    console.log("Asset Object:", assetObj);

    var assetDisplay = '';
    $.each(assetObj, function(key, value) {
        $.each(value.accounts, function(i, account) {
            assetDisplay += `
                <tr>
                    <td style="font-size: 0.8em;">${key.split('_')[0]}</td>
                    <td style="font-size: 0.8em;">${account.assetAccount.split('_')[0]}</td>
                    <td style="font-size: 0.8em;">${account.amount}</td>
                    <td style="font-size: 0.8em;">
                        <span class="badge fw-normal text-danger remove-asset" data-account="${account.assetAccount}" data-type="${key}">
                            <div class="fas fa-times"></div>
                        </span>
                    </td>
                </tr>
            `;
        });
    });

    $('.append-asset-accounts').html(assetDisplay);
    
    $('.journal-asset-form')[0].reset();
    $('#asset_account_name').html('<option value="" selected hidden>Select an asset type first</option>');

});

    $('.save-liability-info').on('click', function(e){
        e.preventDefault();
        var hasErrors = false; 
    var assetForm = $('.journal-liability-form').serializeArray(); 
    var accountType = ''; 
    var liabilityAccount = '';
    var liabilityAmount = '';

    $('.journal-liability-form input, .journal-liability-form select').removeClass('is-invalid');

    $.each(assetForm, function(index, data) {
        if (data.value === '') {
            $(`.journal-liability-form [name='${data.name}']`).addClass('is-invalid');
            hasErrors = true;
        }
        if (data.name === 'liability-account') {
            accountType = data.value;
        } else if (data.name === 'liability-account-name') {
            liabilityAccount = data.value;
        } else if (data.name === 'liability-amount') {
            liabilityAmount = data.value;
        }
    });

    if (hasErrors) {
        Toast.fire({
            icon: 'warning',
            title: 'Missing Fields',
            text: 'Please fill all fields'
        });
        return;
    }

    if (!liabilityObj[accountType]) {
        liabilityObj[accountType] = {
            accounts: []
        };
    }

    liabilityObj[accountType].accounts.push({
        liabilityAccount: liabilityAccount,
        amount: liabilityAmount
    });

    console.log("Asset Object:", assetObj);

    var liabilityDisplay = '';
    $.each(liabilityObj, function(key, value) {
        $.each(value.accounts, function(i, account) {
            liabilityDisplay += `
                <tr>
                    <td style="font-size: 0.8em;">${key.split('_')[0]}</td>
                    <td style="font-size: 0.8em;">${account.liabilityAccount.split('_')[0]}</td>
                    <td style="font-size: 0.8em;">${account.amount}</td>
                    <td style="font-size: 0.8em;">
                        <span class="badge fw-normal text-danger remove-asset" data-account="${account.liabilityAccount}" data-type="${key}">
                            <div class="fas fa-times"></div>
                        </span>
                    </td>
                </tr>
            `;
        });
    });

    $('.append-liability-accounts').html(liabilityDisplay);
    
    $('.journal-liability-form')[0].reset();
    $('#liability_account_name').html('<option value="" selected hidden>Select an asset type first</option>');
    });

    $('.save-oe-info').on('click', function(e){
        e.preventDefault();
        var hasErrors = false; 
    var assetForm = $('.journal-oe-form').serializeArray(); 
    var accountType = ''; 
    var oeAccount = '';
    var oeAmount = '';

    $('.journal-oe-form input, .journal-oe-form select').removeClass('is-invalid');

    $.each(assetForm, function(index, data) {
        if (data.value === '') {
            $(`.journal-oe-form [name='${data.name}']`).addClass('is-invalid');
            hasErrors = true;
        }
        if (data.name === 'oe-account') {
            accountType = data.value;
        } else if (data.name === 'oe-account-name') {
            oeAccount = data.value;
        } else if (data.name === 'oe-amount') {
            oeAmount = data.value;
        }
    });

    if (hasErrors) {
        Toast.fire({
            icon: 'warning',
            title: 'Missing Fields',
            text: 'Please fill all fields'
        });
        return;
    }

    if (!oeObj[accountType]) {
        oeObj[accountType] = {
            accounts: []
        };
    }

    oeObj[accountType].accounts.push({
        oeAccount: oeAccount,
        amount: oeAmount
    });

    console.log("Asset Object:", assetObj);

    var oeDisplay = '';
    $.each(oeObj, function(key, value) {
        $.each(value.accounts, function(i, account) {
            oeDisplay += `
                <tr>
                    <td style="font-size: 0.8em;">${key.split('_')[0]}</td>
                    <td style="font-size: 0.8em;">${account.oeAccount.split('_')[0]}</td>
                    <td style="font-size: 0.8em;">${account.amount}</td>
                    <td style="font-size: 0.8em;">
                        <span class="badge fw-normal text-danger remove-asset" data-account="${account.oeAccount}" data-type="${key}">
                            <div class="fas fa-times"></div>
                        </span>
                    </td>
                </tr>
            `;
        });
    });

    $('.append-oe-accounts').html(oeDisplay);
    
    $('.journal-oe-form')[0].reset();
    $('#oe_account_name').html('<option value="" selected hidden>Select an account type first</option>');
    });

    let assetFlag = true;
    let liabilityFlag = true;
    let oeFlag = true;
    var incometotal = 0;
    var expensetotal = 0;
    var oetotal = 0;
    var netIncome = 0;
    var totalLC = 0;
    $('.next-btn').on('click', function () {
        if (currentStep === 1) {
            if (Object.keys(incomeObj).length === 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Data',
                    text: 'Please fill and save data for at least one entry.'
                });
                return;
            }

            $('#append-expenses-choy').empty();
            incometotal = 0;
            var expensesHtml = '';

            $.each(incomeObj, function (accountName, accountData) {
                var account = accountName.split('_');
                var accountTotal = 0;

                $.each(accountData.months, function (index, month) {
                    var preparedValue = month.value.replace(/[^0-9]/g, '');
                    var valToFloat = parseFloat(preparedValue);
                    accountTotal += valToFloat;
                });
                var formattedAccountTotal = accountTotal.toLocaleString();
                incometotal += accountTotal;
                expensesHtml += `
                    <div class="row">
                        <div class="col-sm-6 text-left">${account[1]}</div>
                        <div class="col-sm-6 text-right">${formattedAccountTotal}</div>
                    </div>`;
            });

            var formattedTotal = incometotal.toLocaleString();
            $('#append-expenses-choy').append(expensesHtml);

            var totalExpensesHtml = `
                <div class="row mt-3">
                    <div class="col-sm-6 text-left"><strong>Total:</strong></div>
                    <div class="col-sm-6 text-right"><strong>${formattedTotal}</strong></div>
                </div>`;

            $('.append-expense-total').html(totalExpensesHtml);
        }


        if (currentStep === 2) {
            $('.append-ldc').empty();
            $('.expenses-total').text('');
            $('.append-oe').empty('');
            $('.oe-total').text('');
            var expenseHTML = '';
            var operatingExpenseHTML = '';
            expensetotal = 0;
            oetotal = 0;
        
            if (Object.keys(expensesObj).length === 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Data',
                    text: 'Please fill and save data for at least one entry.'
                });
                return;
            }
        
            var hasLessDirectCost = false;
            var hasOperatingExpenses = false;
        
            $.each(expensesObj, (index, incomeData) => {
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
        
            $.each(expensesObj, (index, incomeData) => {
                var expenseAccount = index.split('_');
                var expenseType = expenseAccount[2];
                var expenseAccTotal = 0;
                var operatingTotal = 0;
                if (expenseType === 'Less Direct Cost') {
                    $.each(incomeData.months, (index, expenseData) => {
                        var preparedValue = expenseData.value.replace(/[^0-9]/g, '');
                        var valToFloat = parseFloat(preparedValue);
                        expenseAccTotal += valToFloat;
                    });
        
                    var formatExpAccTotal = expenseAccTotal.toLocaleString();
                    expensetotal += expenseAccTotal;
        
                    expenseHTML += `
                    <div class="row">
                        <div class="col-sm-6 text-left">${expenseAccount[1]}</div>
                        <div class="col-sm-6 text-right">${formatExpAccTotal}</div>
                    </div>
                    `;
                }else if(expenseType === 'Operating Expenses'){
                    $.each(incomeData.months, (index, expenseData) => {
                        var preparedValue = expenseData.value.replace(/[^0-9]/g, '');
                        var valToFloat = parseFloat(preparedValue);
                        operatingTotal += valToFloat;
                    });
        
                    var formatOperatingTotal = operatingTotal.toLocaleString();
                    oetotal += operatingTotal;
        
                    operatingExpenseHTML += `
                    <div class="row">
                        <div class="col-sm-6 text-left">${expenseAccount[1]}</div>
                        <div class="col-sm-6 text-right">${formatOperatingTotal}</div>
                    </div>
                    `;
                }
            });
        
            var totalGTI = incometotal - expensetotal;
            var totalGI = totalGTI - oetotal;
            netIncome = totalGI;
            $('.gries-total').text(totalGTI.toLocaleString());
            $('.tgi').text(totalGTI.toLocaleString());
            $('.expenses-total').text(expensetotal.toLocaleString());
            $('.append-ldc').append(expenseHTML);
            $('.append-oe').append(operatingExpenseHTML);
            $('.oe-total').text(oetotal.toLocaleString());
            $('.net-amount').text(totalGI.toLocaleString());

        }

        if (currentStep === 3) {
            var assetDisp = '';
            var totalAssets = 0;
            var totalNCA = 0;
            var totalCA = 0;
            var totalFA = 0;
            $('.append-ca').html('');
            $('.append-nca').html('');
            $('.append-fa').html('');
            if (Object.keys(assetObj).length === 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Empty Field',
                    text: 'Please fill and save data for at least one entry.'
                });
                return;
            }

            $.each(assetObj, (index, data)=>{
                if(index.split('_')[0] === 'Current Asset'){
                    $.each(data.accounts, (index, accounts)=>{
                        var caAmount = accounts.amount.replace(/[^0-9]/g, '');
                        var preparedCAAmount = parseFloat(caAmount);
                        totalCA += preparedCAAmount;
                        $('.total-ca').text(totalCA.toLocaleString());            
                        $('.append-ca').append(
                            `<div class="d-flex justify-content-between">
                            <span class='fw-normal float-left'>${accounts.assetAccount.split('_')[0]}</span>
                            <span class='fw-normal float-right'>${accounts.amount.toLocaleString()}</span>
                            </div>`
                        );
                    });
                }
                if(index.split('_')[0] === 'Non-Current Assets'){
                    $.each(data.accounts, (index, accounts)=>{
                        var tncaAmount = accounts.amount.replace(/[^0-9]/g, '');
                        var preparedTNCAAmount = parseFloat(tncaAmount);
                        totalNCA += preparedTNCAAmount;
                        $('.tnca-amount').text(totalNCA.toLocaleString());
                        $('.append-nca').append(
                           `<div class="d-flex justify-content-between">
                            <span class='fw-normal float-left'>${accounts.assetAccount.split('_')[0]}</span>
                            <span class='fw-normal float-right'>${accounts.amount.toLocaleString()}</span>
                            </div>`
                        );
                    });
                }
                if(index.split('_')[0] === 'Fixed Assets'){
                    $.each(data.accounts, (index, accounts)=>{
                        var faAmount = accounts.amount.replace(/[^0-9]/g, '');
                        var preparedFAAmount = parseFloat(faAmount);
                        totalFA += preparedFAAmount;
                        $('.append-fa').append(
                            `<div class="d-flex justify-content-between">
                            <span class='fw-normal float-left'>${accounts.assetAccount.split('_')[0]}</span>
                            <span class='fw-normal float-right'>${accounts.amount.toLocaleString()}</span>
                            </div>`
                        );
                    });
                }
            });
            totalAssets = totalCA + totalNCA + totalFA;
            $('.total-assets').text(totalAssets.toLocaleString());
            
        }
        if (currentStep === 4) {
            var liDisp = ``;
            var liAmount = 0;
            if(Object.keys(liabilityObj).length === 0){
                Toast.fire({
                    icon: 'warning',
                    title: 'Empty Field',
                    text: 'Please fill and save data for at least one entry.'
                });
                return;
            }

            $.each(liabilityObj, (index, liability)=>{
                $.each(liability.accounts, (index, accounts)=>{
                    var sanitizeLAM = accounts.amount.replace(/[^0-9.-]/g, '');
                    var lamToFloat = parseFloat(sanitizeLAM);
                    liAmount += lamToFloat;
                    
                    console.log(liAmount);
                    
                    liDisp += `
                    <div class="d-flex justify-content-between">
                        <span class="fw-normal">${accounts.liabilityAccount.split('_')[0]}</span>
                        <span class="fw-normal">${accounts.amount}</span>
                    </div>
                    `;
                    
                });
            });
            totalLC += liAmount;
            $('.append-cl').html(liDisp);
        }
        if (currentStep === 5) {
            var oeDisp = ``;
            var oeAmount = 0;
           if(Object.keys(oeObj).length === 0){
                Toast.fire({
                    icon: 'warning',
                    title: 'Empty Field',
                    text: 'Please fill and save data for at least one entry.'
                });
                return;
           }

           $.each(oeObj, (index, data)=>{
            $.each(data.accounts, (index, oe)=>{
                var sanitizeOe = oe.amount.replace(/[^0-9.-]/g, '');
                var oeamToFloat = parseFloat(sanitizeOe);
                oeAmount += oeamToFloat;
                
                console.log(oeAmount);
                
                oeDisp += `
                <div class="d-flex justify-content-between">
                <span class="fw-normal">${oe.oeAccount.split('_')[0]}</span>
                <span class="fw-normal">${oe.amount}</span>
            </div>
                `;
            });
            
           });
           totalLC += oeAmount;
           $('.append-oenw').html(oeDisp);
        }
        if (currentStep === 6) {
            // totalLC = liAmount + oeAmount;
            // console.log(liAmount);
            // console.log(oeAmount);
            
            // console.log(totalLC);
            
            $('.tlc').text(totalLC.toLocaleString());
            var additionalCapital = 0;
            var proceedFlag = true; 
            var adjustmentForms = $('.journal-adjustments-form').serializeArray();
            $.each(adjustmentForms, (index, input) => {
                var $inputField = $(`.journal-adjustments-form [name='${input.name}']`);
                
                if (input.value.trim() === '') {
                    proceedFlag = false;
                    $inputField.addClass('is-invalid');
                } else {
                    $inputField.removeClass('is-invalid');
                    adjustmentObj[input.name] = input.value;
                }
            });
        
            if (!proceedFlag) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please fill all fields'
                });
                return;
            }
        
            $.each(adjustmentObj, (index, data) => {
                var adjAmount = data.replace(/[^0-9]/g, '');
                var preparedAdjustments = parseFloat(adjAmount);
        
                if (index === 'owners_withdrawal') {
                    $('.less-drawings').text(preparedAdjustments.toLocaleString());
                }
        
                if (index === 'owners_capital') {
                    additionalCapital += preparedAdjustments;
                }
                if (index !== 'owners_withdrawal' && index !== 'owners_capital') {
                    additionalCapital += preparedAdjustments;
                }
            });
            $('.additional-capital').text(additionalCapital.toLocaleString());
            $('.fp-nc').text(netIncome.toLocaleString());
        
            var appraisal = additionalCapital + netIncome;
            $('.appraisal-capital').text(appraisal.toLocaleString());
        
            var capitalEnd = appraisal - parseFloat($('.less-drawings').text().replace(/[^0-9.-]+/g, ""));
            $('.capital-end').text(capitalEnd.toLocaleString());
        }
        
        
        if (currentStep < 7) {
            $('.multi-step-journal').hide();
            currentStep++;
            showStep(currentStep);
        }

        updateStepIndicator(currentStep);

        if (currentStep === 7) {
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

    $('.save-journal-btn').click(function(){
        var journalData = {
            incomeObj,
            expensesObj,
            assetObj,
            liabilityObj,
            oeObj,
            adjustmentObj,
            'client_id': $(this).attr('id').split('_')[1]

        }
        ClientJournalEntry(
            'new-client-journal-entry',
            journalData,
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            CallSuccess,
            CallFailed
        )
        
        function CallSuccess(response){
            console.log(response);
            localStorage.setItem('journal', 'created');
            location.reload();
        }

        function CallFailed(error, status, jqXHR){
            console.log(error);
        }
    });


    var journalStat = localStorage.getItem('journal');
    if(journalStat === 'created'){
        Toast.fire({
            icon: 'success',
            title: 'Journal Entry',
            text: 'New Journal Entry Saved'
        });
        localStorage.removeItem('journal');
    }
});