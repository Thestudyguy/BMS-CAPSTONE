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
    let liabilityObj = {};
    let oeObj = {};
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
        $.ajax({
            type: 'POST',
            url: `get-account-types-${at}`,
            // data: at,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                let atElement = '';
                $.each(response, (index, ats) => {
                    $.each(ats, (index, atsData) => {
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
            url: `get-account-types-${at}`,
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
            url: `get-account-types-${at}`,
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
    e.preventDefault(); // Prevent the default form submission

    var hasErrors = false; // Flag for validation
    var assetForm = $('.journal-asset-form').serializeArray(); // Get form data
    var accountType = ''; // Variable to hold the selected asset type
    var assetAccount = ''; // Variable to hold the selected asset account
    var assetAmount = ''; // Variable to hold the asset amount

    // Clear previous validation classes
    $('.journal-asset-form input, .journal-asset-form select').removeClass('is-invalid');

    // Validate inputs and extract values
    $.each(assetForm, function(index, data) {
        if (data.value === '') {
            $(`.journal-asset-form [name='${data.name}']`).addClass('is-invalid');
            hasErrors = true; // Set error flag if any input is invalid
        }
        if (data.name === 'assetType') {
            accountType = data.value; // Capture the asset type
        } else if (data.name === 'assetAccount') {
            assetAccount = data.value; // Capture the asset account
        } else if (data.name === 'assetAmount') {
            assetAmount = data.value; // Capture the asset amount
        }
    });

    // If there are validation errors, show a warning and exit
    if (hasErrors) {
        alert('Please fill all fields'); // Replace with your Toast implementation
        return; // Stop further processing
    }

    // Create the asset structure
    if (!assetObj[accountType]) {
        // Initialize if the asset type doesn't exist
        assetObj[accountType] = {
            accounts: []
        };
    }

    // Add the account details
    assetObj[accountType].accounts.push({
        assetAccount: assetAccount,
        amount: assetAmount
    });

    // Debugging: Log the asset object
    console.log("Asset Object:", assetObj);

    // Prepare HTML for displaying the assets
    var assetDisplay = '';
    $.each(assetObj, function(key, value) {
        $.each(value.accounts, function(i, account) {
            assetDisplay += `
                <tr>
                    <td style="font-size: 0.8em;">${key}</td>
                    <td style="font-size: 0.8em;">${account.assetAccount}</td>
                    <td style="font-size: 0.8em;">${account.amount}</td>
                </tr>
            `;
        });
    });

    // Inject the generated HTML into the DOM
    $('.append-asset-accounts').html(assetDisplay);
    
    // Clear the form fields after saving
    $('.journal-asset-form')[0].reset(); // Reset form
    $('#asset_account_name').html('<option value="" selected hidden>Select an asset type first</option>'); // Reset asset account dropdown

    // Debugging: Log the final HTML
    console.log("Final Asset Display HTML:", assetDisplay);
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
    let liabilityFlag = true;
    let oeFlag = true;
    var incometotal = 0;
    var expensetotal = 0;
    var oetotal = 0;
    $('.next-btn').on('click', function () {

        // if (currentStep === 1) {
        //     if (Object.keys(incomeObj).length === 0) {
        //         Toast.fire({
        //             icon: 'warning',
        //             title: 'Missing Data',
        //             text: 'Please fill and save data for at least one entry.'
        //         });
        //         return;
        //     }

        //     $('#append-expenses-choy').empty();
        //     incometotal = 0;
        //     var expensesHtml = '';

        //     $.each(incomeObj, function (accountName, accountData) {
        //         var account = accountName.split('_');
        //         var accountTotal = 0;

        //         $.each(accountData.months, function (index, month) {
        //             var preparedValue = month.value.replace(/[^0-9]/g, '');
        //             var valToFloat = parseFloat(preparedValue);
        //             accountTotal += valToFloat;
        //         });
        //         var formattedAccountTotal = accountTotal.toLocaleString();
        //         incometotal += accountTotal;
        //         expensesHtml += `
        //             <div class="row">
        //                 <div class="col-sm-6 text-left">${account[1]}</div>
        //                 <div class="col-sm-6 text-right">${formattedAccountTotal}</div>
        //             </div>`;
        //     });

        //     var formattedTotal = incometotal.toLocaleString();
        //     $('#append-expenses-choy').append(expensesHtml);

        //     var totalExpensesHtml = `
        //         <div class="row mt-3">
        //             <div class="col-sm-6 text-left"><strong>Total:</strong></div>
        //             <div class="col-sm-6 text-right"><strong>${formattedTotal}</strong></div>
        //         </div>`;

        //     $('.append-expense-total').html(totalExpensesHtml);
        // }


        // if (currentStep === 2) {
        //     $('.append-ldc').empty();
        //     $('.expenses-total').text('');
        //     $('.append-oe').empty('');
        //     $('.oe-total').text('');
        //     var expenseHTML = '';
        //     var operatingExpenseHTML = '';
        //     expensetotal = 0;
        //     oetotal = 0;
        
        //     if (Object.keys(expensesObj).length === 0) {
        //         Toast.fire({
        //             icon: 'warning',
        //             title: 'Missing Data',
        //             text: 'Please fill and save data for at least one entry.'
        //         });
        //         return;
        //     }
        
        //     var hasLessDirectCost = false;
        //     var hasOperatingExpenses = false;
        
        //     $.each(expensesObj, (index, incomeData) => {
        //         var expenseAccount = index.split('_');
        //         var expenseType = expenseAccount[2];
        
        //         if (expenseType === 'Less Direct Cost') {
        //             hasLessDirectCost = true;
        //         } else if (expenseType === 'Operating Expenses') {
        //             hasOperatingExpenses = true;
        //         }
        //     });
        
        //     if (!hasLessDirectCost || !hasOperatingExpenses) {
        //         Toast.fire({
        //             icon: 'warning',
        //             title: 'Missing Data',
        //             text: 'Both "Less Direct Cost" and "Operating Expenses" entries are required.'
        //         });
        //         return;
        //     }
        
        //     $.each(expensesObj, (index, incomeData) => {
        //         var expenseAccount = index.split('_');
        //         var expenseType = expenseAccount[2];
        //         var expenseAccTotal = 0;
        //         var operatingTotal = 0;
        //         if (expenseType === 'Less Direct Cost') {
        //             $.each(incomeData.months, (index, expenseData) => {
        //                 var preparedValue = expenseData.value.replace(/[^0-9]/g, '');
        //                 var valToFloat = parseFloat(preparedValue);
        //                 expenseAccTotal += valToFloat;
        //             });
        
        //             var formatExpAccTotal = expenseAccTotal.toLocaleString();
        //             expensetotal += expenseAccTotal;
        
        //             expenseHTML += `
        //             <div class="row">
        //                 <div class="col-sm-6 text-left">${expenseAccount[1]}</div>
        //                 <div class="col-sm-6 text-right">${formatExpAccTotal}</div>
        //             </div>
        //             `;
        //         }else if(expenseType === 'Operating Expenses'){
        //             $.each(incomeData.months, (index, expenseData) => {
        //                 var preparedValue = expenseData.value.replace(/[^0-9]/g, '');
        //                 var valToFloat = parseFloat(preparedValue);
        //                 operatingTotal += valToFloat;
        //             });
        
        //             var formatOperatingTotal = operatingTotal.toLocaleString();
        //             oetotal += operatingTotal;
        
        //             operatingExpenseHTML += `
        //             <div class="row">
        //                 <div class="col-sm-6 text-left">${expenseAccount[1]}</div>
        //                 <div class="col-sm-6 text-right">${formatOperatingTotal}</div>
        //             </div>
        //             `;
        //         }
        //     });
        
        //     var totalGTI = incometotal - expensetotal;
        //     var totalGI = totalGTI - oetotal;
        //     $('.gries-total').text(totalGTI.toLocaleString());
        //     $('.tgi').text(totalGTI.toLocaleString());
        //     $('.expenses-total').text(expensetotal.toLocaleString());
        //     $('.append-ldc').append(expenseHTML);
        //     $('.append-oe').append(operatingExpenseHTML);
        //     $('.oe-total').text(oetotal.toLocaleString());
        //     $('.net-amount').text(totalGI.toLocaleString());

        // }
        
        


        // if (currentStep === 3) {
        //     assetFlag = true;
        //     let assetForm = $('.journal-asset-form').serializeArray();
        //     $.each(assetForm, (index, assetData) => {
        //         if (assetData.value.trim() === '') {
        //             assetFlag = false;
        //             $(`.journal-asset-form [name='${assetData.name}']`).addClass('is-invalid');
        //         } else {
        //             $(`.journal-asset-form [name='${assetData.name}']`).removeClass('is-invalid');
        //             assetObj[assetData.name] = assetData.value;
        //         }
        //     });

        //     if (!assetFlag) {
        //         Toast.fire({
        //             icon: 'warning',
        //             title: 'Missing Fields',
        //             text: 'Please fill all fields'
        //         });
        //         return;
        //     }
        // }
        // if (currentStep === 4) {
        //     liabilityFlag = true;
        //     let liabilityForm = $('.journal-liability-form').serializeArray();

        //     $.each(liabilityForm, (index, liabilityData) => {
        //         if (liabilityData.value.trim() === '') {
        //             liabilityFlag = false;
        //             $(`.journal-liability-form [name='${liabilityData.name}']`).addClass('is-invalid');
        //         } else {
        //             $(`.journal-liability-form [name='${liabilityData.name}']`).removeClass('is-invalid');
        //             liabilityObj[liabilityData.name] = liabilityData.value;
        //         }
        //     });
        //     if (!liabilityFlag) {
        //         Toast.fire({
        //             icon: 'warning',
        //             title: 'Missing Fields',
        //             text: 'Please fill all fields'
        //         });
        //         return;
        //     }
        // }
        // if (currentStep === 5) {
        //     oeFlag = true;
        //     let oeForm = $('.journal-oe-form').serializeArray();

        //     $.each(oeForm, (index, oeform) => {
        //         if (oeform.value.trim() === '') {
        //             oeFlag = false;
        //             $(`.journal-oe-form [name='${oeform.name}']`).addClass('is-invalid');
        //         } else {
        //             $(`.journal-oe-form [name='${oeform.name}']`).removeClass('is-invalid');
        //             oeObj[oeform.name] = oeform.value;
        //         }
        //     });
        //     if (!oeFlag) {
        //         Toast.fire({
        //             icon: 'warning',
        //             title: 'Missing Fields',
        //             text: 'Please fill all fields'
        //         });
        //         return;
        //     }
        // }
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