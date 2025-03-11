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
$(document).ready(function () {
    let currentStep = 1;
    var income = window.journalIncome;
    var expense = window.journalExpense;
    var asset = window.journalAssets;
    var liability = window.journalLiability;
    var oe = window.journalOE;
    var adjustment = window.journalOE;
    var incomeObj = income;
    var expenseObj = expense;
    var assetObj = {};
    var liabilityObj = {};
    var oeObj = {};
    var adjustmentObj = {};
    
//     $.each(income, (index, income) => {
//         if (!incomeObj[income.account]) {
//             incomeObj[income.account] = {
//                 months: [],
//                 startDate: income.start_date,
//                 endDate: income.end_date
//             };
//         }
//         incomeObj[income.account].months.push({
//             incomeMonthName: income.month,
//             value: income.amount
//         });
//     });

//     console.log('income', incomeObj);
    
//     $.each(expense, (index, expense) => {
//         const expenseMonths = [];
//         expenseMonths.push({
//             expenseMonthName: expense.month,
//             value: expense.amount
//         });
//         expenseObj[expense.account] = {
//             months: expenseMonths,
//             startDate: expense.start_date,
//             endDate: expense.end_date
//         };
//     });
// console.log('expense', expenseObj);

    $.each(asset, (index, assets) => {
        const accounts = [];
        accounts.push({
            assetAccount: assets.account,
            amount: assets.amount
        });
        assetObj[assets.asset_category] = { accounts };
    });


    $.each(liability, (index, lias) => {
        const accounts = [];
        var accountType = lias.AccountType
        accounts.push({
            liabilityAccount: lias.account,
            amount: lias.amount
        });
        liabilityObj[accountType] = { accounts };
    });


    $.each(oe, (index, oes) => {
        const accounts = [];
        var accountType = oes.AccountType
        accounts.push({
            oeAccount: oes.account,
            amount: oes.amount
        });
        oeObj[accountType] = { accounts };
        // oeObj = {
        //     account: oes.account,
        //     amount: oes.amount
        // }
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
        $(document).on('input', '.audit-income-month-input', function () {
            formatValueInput(this);
        });

    });

    $(document).on('click', '.save-income-audit-months', function (e) {
        e.preventDefault();
        let selectedMonths = [];
        let hasValue = true;
        if (incomeObj.hasOwnProperty(selectedAccount)) {
            Toast.fire({
                icon: 'warning',
                title: 'Account Already Exists!',
                text: 'You have already added this account.'
            });
            $('.audit-income-end-date').val('');
            $('.audit-income-start-date').val('');
            $('.audit-income-months-container').empty();
            console.log(true);
            
            return;
        }

        $('.audit-income-month-input').each(function () {
            var incomeMonthName = $(this).attr('name');
            console.log(incomeMonthName);
            
            var monthVal = $(this).val();
            if (monthVal !== '') {
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

        if (hasValue) {
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
            endDate: $('.audit-income-end-date').val()
        };
        $('#saved-audited-income-months').empty();
        $.each(incomeObj, (account, element) => {
            var tableHTML = `<table class="table table-hover client-journal-accounts">`;
            var accountParts = account.split('_');
            tableHTML += `
                <tr class="client-journal-accounts" data-widget="expandable-table" aria-expanded="false">
                    <td>
                        ${accountParts[1]}
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
        console.log(incomeObj);
        
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

    });

    $(document).on('click', '.save-expense-audit-months', function (e) {
        e.preventDefault();
        let selectedMonths = [];
        let hasValue = true;
        if (expenseObj.hasOwnProperty(selectedAccount)) {
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

        $('.audit-expense-month-input').each(function () {
            var expenseMonthName = $(this).attr('name');
            var monthVal = $(this).val();
            if (monthVal !== '') {
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

        if (hasValue) {
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
        $('#saved-audited-expense-months').empty();
        $.each(expenseObj, (account, element) => {
            var tableHTML = `<table class="table table-hover client-journal-accounts">`;
            var accountParts = account.split('_');
            tableHTML += `
                <tr class="client-journal-accounts" data-widget="expandable-table" aria-expanded="false">
                    <td>
                        ${accountParts[1]}
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
    $('.save-audit-asset-info').on('click', function (e) {
        e.preventDefault();

        var form = $('.journal-audit-asset-form').serializeArray();
        var accountType = '';
        var assetAccount = '';
        var assetAmount = '';
        let isAccountExisting = false;
        $.each(form, (index, input) => {

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

        $.each(assetObj, (index, accounts) => {
            $.each(accounts.accounts, (index, assets) => {
                if (assets.assetAccount === assetAccount.split('_')[0]) {
                    isAccountExisting = true;
                    return false;
                }
            });
            if (isAccountExisting) return false;
        });


        if (isAccountExisting) {
            Toast.fire({
                icon: 'warning',
                title: 'Existing Account',
                text: 'Account Already Exists!'
            });
            return false;
        }

        var trimmedCat = accountType.split('_');

        if (!assetObj[trimmedCat[0]]) {
            assetObj[trimmedCat[0]] = {
                accounts: []
            };
        }
        assetObj[trimmedCat[0]].accounts.push({
            assetAccount: assetAccount.split('_')[0],
            amount: assetAmount
        });
        $('.append-audit-asset-accounts').empty();
        let rows = ``;
        $.each(assetObj, (category, data) => {
            $.each(data.accounts, (subIndex, asset) => {
                var prepAmount = asset.amount;
                var sanitizedAmount = prepAmount.toString().replace(/,/g, "");
                var formattedAmount = parseFloat(sanitizedAmount).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                rows += `
            <tr id="${category}">
                <td class='text-sm'>${category}</td>
                <td class='text-sm'>${asset.assetAccount}</td>
                <td class='text-sm'>
                    ${formattedAmount}
                    <span class="badge fw-bold text-dark float-right remove-audited-asset" id="${asset.assetAccount}">
                        <i class="fas fa-times"></i>
                    </span>
                </td>
            </tr>`;
            });
        });

        $('.append-audit-asset-accounts').append(rows);
        $('#asset_account').val('');
        $('#asset_account_name  option:first').text('Select Account');
        $('#assetAmount').val('');
        isAccountExisting = false;
    });

    //assets

    //liabilities 
    $('.save-audit-liability-info').on('click', function (e) {
        e.preventDefault();

        var form = $('.journal-audit-liability-form').serializeArray();
        var accountType = '';
        var liabilityAccount = '';
        var liabilityAmount = '';
        let isAccountExisting = false;
        $.each(form, (index, input) => {

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

            if (input.name === 'audit-liability-account') {
                accountType = input.value;
            } else if (input.name === 'audit-liability-account-name') {
                liabilityAccount = input.value;
            } else if (input.name === 'liability-amount') {
                liabilityAmount = input.value;
            }
        });

        if (!accountType || !liabilityAccount || !liabilityAmount) {
            console.error('Required fields are missing.');
            return;
        }

        $.each(liabilityObj, (index, accounts) => {
            $.each(accounts.accounts, (index, lias) => {
                if (lias.liabilityAccount === liabilityAccount.split('_')[0]) {
                    isAccountExisting = true;
                    return false;
                }
            });
            if (isAccountExisting) return false;
        });


        if (isAccountExisting) {
            Toast.fire({
                icon: 'warning',
                title: 'Existing Account',
                text: 'Account Already Exists!'
            });
            return false;
        }

        var trimmedCat = accountType.split('_');

        if (!liabilityObj[trimmedCat[0]]) {
            liabilityObj[trimmedCat[0]] = {
                accounts: []
            };
        }
        liabilityObj[trimmedCat[0]].accounts.push({
            liabilityAccount: liabilityAccount.split('_')[0],
            amount: liabilityAmount
        });
        $('.append-audit-liability-accounts').empty();
        let rows = ``;
        $.each(liabilityObj, (category, data) => {
            $.each(data.accounts, (subIndex, lias) => {
                var prepAmount = lias.amount;
                var sanitizedAmount = prepAmount.toString().replace(/,/g, "");
                var formattedAmount = parseFloat(sanitizedAmount).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                rows += `
                <tr id="${category}">
                    <td class='text-sm'>${category}</td>
                    <td class='text-sm'>${lias.liabilityAccount}</td>
                    <td class='text-sm'>
                        ${formattedAmount}
                        <span class="badge fw-bold text-dark float-right remove-audit-liability" id="${lias.liabilityAccount}">
                            <i class="fas fa-times"></i>
                        </span>
                    </td>
                </tr>`;
            });
        });

        $('.append-audit-liability-accounts').append(rows);
        $('#liability_account').val('');
        $('#liability_account_name  option:first').text('Select Account');
        $('#liabilityAmount').val('');
        isAccountExisting = false;
    });
    //end of liabilities 

    //owners equity
    $('.save-audit-oe-info').on('click', function (e) {
        e.preventDefault();

        var form = $('.journal-audit-oe-form').serializeArray();
        var accountType = '';
        var oeAccount = '';
        var oeAmount = '';
        let isAccountExisting = false;
        $.each(form, (index, input) => {

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

            if (input.name === 'oe-account') {
                accountType = input.value;
            } else if (input.name === 'oe-account-name') {
                oeAccount = input.value;
            } else if (input.name === 'oe-amount') {
                oeAmount = input.value;
            }
        });

        if (!accountType || !oeAccount || !oeAccount) {
            console.error('Required fields are missing.');
            return;
        }

        $.each(oeObj, (index, accounts) => {
            $.each(accounts.accounts, (index, oes) => {
                if (oes.oeAccount === oeAccount.split('_')[0]) {
                    isAccountExisting = true;
                    return false;
                }
            });
            if (isAccountExisting) return false;
        });


        if (isAccountExisting) {
            Toast.fire({
                icon: 'warning',
                title: 'Existing Account',
                text: 'Account Already Exists!'
            });
            return false;
        }

        var trimmedCat = accountType.split('_');

        if (!oeObj[trimmedCat[0]]) {
            oeObj[trimmedCat[0]] = {
                accounts: []
            };
        }
        oeObj[trimmedCat[0]].accounts.push({
            oeAccount: oeAccount.split('_')[0],
            amount: oeAmount
        });
        $('.append-audit-oe-accounts').empty();
        let rows = ``;
        $.each(oeObj, (category, data) => {
        console.log(category);
                
            $.each(data.accounts, (subIndex, oes) => {
                var prepAmount = oes.amount;
                var sanitizedAmount = prepAmount.toString().replace(/,/g, "");
                var formattedAmount = parseFloat(sanitizedAmount).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                rows += `
            <tr id="${category}">
                <td class='text-sm'>${category}</td>
                <td class='text-sm'>${oes.oeAccount}</td>
                <td class='text-sm'>
                    ${formattedAmount}
                    <span class="badge fw-bold text-dark float-right remove-audit-oe some random stuff" id="${category}">
                        <i class="fas fa-times"></i>
                    </span>
                </td>
            </tr>`;
            });
        });

        $('.append-audit-oe-accounts').append(rows);
        $('#oe-account').val('');
        $('#oe-account-name  option:first').text('Select Account');
        $('#oeAmount').val('');
        isAccountExisting = false;
    });
    //end of owners equity

    //removal functions

    $(document).on('click', '.remove-audit-income', function () {
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
                        ${accountParts[1]}
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
            $('#saved-audited-income-months').append(tableHTML);
        });
        console.log(incomeObj);
    });

    $(document).on('click', '.remove-audit-expense', function (e) {
        delete expenseObj[$(this).attr('id')];
        $(this).closest('tr').remove();
        $('#saved-audited-expense-months').empty();
        $.each(expenseObj, (account, element) => {
            var tableHTML = `<table class="table table-hover client-journal-accounts">`;
            var accountParts = account.split('_');
            tableHTML += `
                <tr class="client-journal-accounts" data-widget="expandable-table" aria-expanded="false">
                    <td>
                        ${accountParts[1]}
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
        $(this).closest('tr').remove();
        var table = '<tbody class="append-audit-asset-accounts">';
        for (let category in assetObj) {
            if (assetObj[category].accounts) {
                assetObj[category].accounts = assetObj[category].accounts.filter(
                    item => item.assetAccount !== $(this).attr('id')
                );
            }
            if (assetObj[category].accounts.length === 0) {
                delete assetObj[category];
            }
        }

        // $.each(assetObj, (index, asset)=>{
        //     table += `<td>${index}</td>`
        //     $.each(asset.assetAccount, (index, account)=>{
        //     table += `<td>${account.account}</td>`
        //     table += `<td>${account.amount.toLocaleString()}</td>`
        //     });
        // });
        // table += '</tbody>';
        // $('.append-audit-asset-accounts').html(table);
    });

    $(document).on('click', '.remove-audit-liability', function (e) {
        e.preventDefault();
        $(this).closest('tr').remove();
        var table = '<tbody class="append-audit-liability-accounts">';
        for (let category in liabilityObj) {
            if (liabilityObj[category].accounts) {
                liabilityObj[category].accounts = liabilityObj[category].accounts.filter(
                    item => item.liabilityAccount !== $(this).attr('id')
                );
            }
            if (liabilityObj[category].accounts.length === 0) {
                delete liabilityObj[category];
            }
        }
        // $.each(liabilityObj, (index, lias)=>{
        //     table += `<td>${index}</td>`
        //     $.each(lias.liabilityAccount, (index, account)=>{
        //     table += `<td>${lias.account}</td>`
        //     table += `<td>${lias.amount.toLocaleString()}</td>`
        //     });
        // });
        // table += '</tbody>';
    });

    $(document).on('click', '.remove-audit-oe', function (e) {
        e.preventDefault();
        var id = $(this).attr('id');
        console.log('yawas');
        
        $(this).closest('tr').remove();
        
        var table = '<tbody class="append-audit-liability-accounts">';
        for (let category in oeObj) {
            if (oeObj[category].accounts) {
                oeObj[category].accounts = oeObj[category].accounts.filter(
                    item => item.oeAccount !== id
                );
            }
            if (oeObj[category].accounts.length === 0) {
                delete oeObj[category];
                console.log(oeObj);
                
            }
        }
        console.log(oeObj);
        
        // $.each(liabilityObj, (index, lias)=>{
        //     table += `<td>${index}</td>`
        //     $.each(lias.liabilityAccount, (index, account)=>{
        //     table += `<td>${lias.account}</td>`
        //     table += `<td>${lias.amount.toLocaleString()}</td>`
        //     });
        // });
        // table += '</tbody>';
    });

    //end of removal functions
    var incometotal = 0;
    var expensetotal = 0;
    var oetotal = 0;
    var netIncome = 0;
    var totalLC = 0;
    var totalDirectCost = 0;
    var totalGrossIncome = 0;// Total Gross Income from Engineering Services Cost / Total Gross Income 
    var totalOperatingExpense = 0;
    var totalNCA = 0;
    var totalCA = 0;
    var totalFA = 0;
    var totalAssets = 0;
    var totalLiability = 0;
    var totalOE = 0;
    console.log(expenseObj);
    
    $('.audit-next-btn').on('click', function () {
        if (currentStep === 1) {
            $('#append-audit-expenses-choy').empty();
            $('.revenue-audit-income-total').empty();
            if (Object.keys(incomeObj).length === 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Data',
                    text: 'Please fill and save data for at least one entry.'
                });
                return;
            }
            var appendIncome = '<div class="col-sm-12 ml-3 text-dark" id="append-audit-expenses-choy">';
            var preAuditTotal = 0;
            $.each(incomeObj, (index, data) => {
                appendIncome += `<div class="row mb-2">`;
            
                // Use ternary operator to handle prefixed or standalone account names
                const accountName = index.includes('_') ? index.split('_')[1] : index;
            
                appendIncome += `<div class="col-6 revenue-audit-accounts">${accountName}</div>`;
                console.log(index);
            
                $.each(data.months, (month, values) => {
                    var prepAmount = values.value.replace(/,/g, '');
                    var amountToFloat = parseFloat(prepAmount);
                    incometotal += amountToFloat;
                    preAuditTotal += amountToFloat;
                });
                appendIncome += `<div class="col-6 text-right revenue-audit-amount">${preAuditTotal.toLocaleString()}</div>`;
            
                appendIncome += `</div>`;
            });
            
            appendIncome += '</div>';
            $('#append-audit-expenses-choy').html(appendIncome);
            $('.revenue-audit-income-total').text(incometotal.toLocaleString());
        }


        if (currentStep === 2) {
            $('.append-audit-oe').empty();
            $('.append-audit-ldc').empty();
            var tolaDirectCost = 0;
            // var expLDCTable = '<div class="col-sm-12 ml-3 append-audit-ldc">';
            // var expOETable = '<div class="col-sm-12 ml-3 append-audit-oe">';
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
            totalOperatingExpense = 0;
            $.each(expenseObj, (index, expense) => {
                var account = index.split('_')[1];
                if (index.split('_')[2] === 'Less Direct Cost') {
                    var expLDCTable = '';
                    $.each(expense.months, (index, month) => {
                        var prepAmount = month.value.replace(/,/g, '');
                        var toFloat = parseFloat(prepAmount);
                        totalDirectCost += toFloat;
                        expLDCTable += `
                        <div class="row">
                            <div class="col-sm-6 text-left">${account}</div>
                            <div class="col-sm-6 text-right">${toFloat.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}</div>
                        </div>
                        `;
                    });
                    $('.append-audit-ldc').append(expLDCTable);
                }
                if (index.split('_')[2] === 'Operating Expenses') {
                    var expOETable = '';
                    $.each(expense.months, (index, month) => {
                        var prepAmount = month.value.replace(/,/g, '');
                        var toFloat = parseFloat(prepAmount);
                        totalOperatingExpense += toFloat;
                        expOETable += `
                        <div class="row">
                            <div class="col-sm-6 text-left">${account}</div>
                            <div class="col-sm-6 text-right">${toFloat.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}</div>
                        </div>
                        `;
                    });
                    $('.append-audit-oe').append(expOETable);
                }
            });

            totalGrossIncome = incometotal - totalDirectCost;
            netIncome = totalGrossIncome - totalOperatingExpense;
            $('.expenses-ldc-audit-total').text(totalDirectCost.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));

            $('.gries-audit-total').text(totalGrossIncome.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));

            $('.tgi-audit').text(totalGrossIncome.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));

            $('.oe-audit-total').text(totalOperatingExpense.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            $('.net-audit-amount').text(netIncome.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        }

        if (currentStep === 3) {
            $('.append-ca-audit').empty();
            $('.append-audit-nca').empty();
            $('.append-audit-fa').empty();
            const hasData = Object.keys(assetObj).some(key =>
                assetObj[key].accounts && assetObj[key].accounts.length > 0
            );
            if (!hasData) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Data',
                    text: 'Please fill and save data for at least one entry.'
                });
                return;
            }
            var caHTML = '';
            var ncaHTML = '';
            var faHTML = '';
            $.each(assetObj, (index, assets) => {

                if (index === 'Current Asset') {
                    $.each(assets.accounts, (index, ca) => {
                        var prepAmount = ca.amount.replace(/,/g, '');
                        var toFloat = parseFloat(prepAmount);
                        totalCA += toFloat;
                        caHTML += `
                                <div class="row">
                                    <span class="col-sm-6 text-sm text-left">${ca.assetAccount}</span>
                                    <span class="col-sm-6 text-sm text-right">${toFloat.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}</span>
                                </div>
                            `;
                    });
                }

                if (index === 'Non-Current Assets') {
                    $.each(assets.accounts, (index, ca) => {
                        var prepAmount = ca.amount.replace(/,/g, '');
                        var toFloat = parseFloat(prepAmount);
                        totalNCA += toFloat;
                        ncaHTML += `
                            <div class="row">
                                <span class="col-sm-6 text-sm text-left">${ca.assetAccount}</span>
                                <span class="col-sm-6 text-sm text-right">${toFloat.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}</span>
                            </div>
                        `;
                    });
                }
                if (index === 'Fixed Assets') {
                    $.each(assets.accounts, (index, ca) => {
                        var prepAmount = ca.amount.replace(/,/g, '');
                        var toFloat = parseFloat(prepAmount);
                        totalFA += toFloat;
                        faHTML += `
                        <div class="row">
                            <span class="col-sm-6 text-sm text-left">${ca.assetAccount}</span>
                            <span class="col-sm-6 text-sm text-right">${toFloat.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}</span>
                        </div>
                    `;
                    });
                }
                $('.append-ca-audit').html(caHTML);
                $('.append-audit-nca').html(ncaHTML);
                $('.append-audit-fa').html(faHTML);
                $('.tnca-audit-amount').text(totalNCA.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('.total-audit-ca').text(totalCA.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                totalAssets = totalCA + totalNCA + totalFA;
                $('.total-audit-assets').text(totalAssets.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            });

        }
        if (currentStep === 4) {
            totalLiability = 0;            
            $('.append-audit-cl').empty();
            const hasData = Object.keys(liabilityObj).some(key =>
                liabilityObj[key].accounts && liabilityObj[key].accounts.length > 0
            );
            if (!hasData) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Data',
                    text: 'Please fill and save data for at least one entry.'
                });
                return;
            }
            var liasTable = '';
            $.each(liabilityObj, (index, lias)=>{
                $.each(lias.accounts, (index, accounts)=>{
                    
                    var prepAmount = accounts.amount.replace(/,/g, '');
                    var toFloat = parseFloat(prepAmount);
                    totalLiability += toFloat;
                    liasTable += `
                        <div class="row">
                            <span class="col-sm-6 text-sm text-left">${accounts.liabilityAccount}</span>
                            <span class="col-sm-6 text-sm text-right">${toFloat.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}</span>
                        </div>
                    `;
                });
            });
            $('.append-audit-cl').html(liasTable);
        }
        if (currentStep === 5) {
            console.log('this line');
            
            totalOE = 0;
            $('.append-audit-oenw').empty();
            var oeTable = '';
            const hasData = Object.keys(oeObj).some(key =>
                oeObj[key].accounts && oeObj[key].accounts.length > 0
            );
            console.log(oeObj);
            
            if (!hasData) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Data',
                    text: 'Please fill and save data for at least one entry.'
                });
                return;
            }

            $.each(oeObj, (index, oes)=>{
                $.each(oes.accounts, (index, accounts)=>{
                    var prepAmount = accounts.amount.replace(/,/g, '');
                    var toFloat = parseFloat(prepAmount);
                    totalOE += toFloat;
                    oeTable += `<div class="row">
                            <span class="col-sm-6 text-sm text-left">${accounts.oeAccount}</span>
                            <span class="col-sm-6 text-sm text-right">${toFloat.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}</span>
                        </div>
                    `;

                });
            });
            $('.append-audit-oenw').html(oeTable);
        }
        if (currentStep === 6) {
            var form = $('.journal-audit-adjustments-form').serializeArray();
            var submitFlag = true;

            $.each(form, (index, input) => {
                if (input.value === '') {
                    submitFlag = false;
                    $(`[name='${input.name}']`).addClass('is-invalid');
                    return;
                }
                adjustmentObj[input.name] = input.value;
                $(`[name='${input.name}']`).removeClass('is-invalid');
            });

            if (!submitFlag) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please fill all fields'
                });
                return
            }
            var addCapital = 0;
            var drawings = 0;
            $.each(adjustmentObj, (equity, value)=>{
                if(equity === 'audit-owners_contribution'){
                    var prepAmount = value.replace(/,/g, '');
                    var toFloat = parseFloat(prepAmount);
                    addCapital += toFloat;
                }
                if(equity === 'audit-owners_withdrawal'){
                    var prepAmount = value.replace(/,/g, '');
                    var toFloat = parseFloat(prepAmount);
                    drawings += toFloat;
                }
            });            
            var appraisal = drawings + netIncome;
                $('.additional-audit-capital').text(addCapital.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('.audit-less-drawings').text(drawings.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            $('.fp-audit-nc').text(netIncome.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            $('.appraisal-audit-capital').text(appraisal.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            var capitalEnd = appraisal - parseFloat($('.audit-less-drawings').text().replace(/[^0-9.-]+/g, ''));
            $('.audit-capital-end').text(capitalEnd.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            var tlc = totalLiability + totalOE;
            $('.tlc-audit').text(tlc.toLocaleString('en-US', {
                maximumFractionDigits: 2,
                minimumFractionDigits: 2
            }));
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


    $('.save-audit-journal-btn').on('click', function(){
        $('.audit-client-journal-loader').removeClass('visually-hidden');
        var auditedJournal = {
            incomeObj,
            expenseObj,
            assetObj,
            liabilityObj,
            oeObj,
            adjustmentObj,
            references: $(this).attr('id')
        }
        $.ajax({
            type: "POST",
            url: 'client/journal/audit',
            data: auditedJournal,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response){
                $('.audit-client-journal-loader').addClass('visually-hidden');
                localStorage.setItem('journal', 'updated');
                window.location.href = 'clients';
            },
            error: function(errThrown, status, jqXHR){
                $('.audit-client-journal-loader').addClass('visually-hidden');
                ToastError.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                    text: `Translated: ${errThrown} \n if issue persist try reloading the page or contact developer`
                });
                return;
            }
        });
        // console.log($(this).attr('id'));
        // console.log(incomeObj);
        // console.log(expenseObj);
        // console.log(assetObj);
        // console.log(liabilityObj);
        // console.log(oeObj);
        // console.log(adjustmentObj);
        
    });
    var journal = localStorage.getItem('journal');
    if(journal === 'updated'){
        Toast.fire({
            icon: 'success',
            title: 'Journal Update',
            text: 'Journal Updated Successfully'
        });
        localStorage.removeItem('journal');
    }
});