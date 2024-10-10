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
    let incomeObj = {};
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
            $('.save-expense').removeClass('visually-hidden');
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
            fiscalYearEnd.setMonth(fiscalYearEnd.getMonth() + 12);

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
    
        $(document).on('click', '.save-months', function (e) {
            e.preventDefault();
            let selectedMonths = [];
            let hasValue = false;
            console.log('selected account', selectedAccount);
            if (incomeObj.hasOwnProperty(selectedAccount)) {
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
                console.log(`Month: ${monthName}, Value: ${$(this).val()}`);
                if ($(this).val().trim() !== '') {
                    hasValue = true;
                }
                selectedMonths.push({
                    monthName,
                    value: $(this).val(),
                    account: selectedAccount,
                    startDate: startDate,
                    endDate: endDate
                });
            });
            
            incomeObj[selectedAccount] = {
                months: selectedMonths,
                startDate: startDate,
                endDate: endDate,
            };
            $('#saved-months').empty();
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
            console.log(incomeObj);
            $('.months-container').empty();
            $('#expense-category').val('');
            $('.end-date').val('');
            $('.start-date').val('');
            selectedAccount = '';
            hasValue = true;
        });
        
    });
    
    
        $(document).on('click', '.remove-saved-account', function(e){
        const accountId = $(this).attr('id');
    
        delete incomeObj[accountId];
        console.log('Current incomeObj:', incomeObj);
        
        $('#saved-months').empty();
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
    console.log(incomeObj);
    
    $('.next-btn').on('click', function () {
        console.log(incomeObj);
        
        // if (currentStep === 1) {
        //     if (Object.keys(incomeObj).length === 0) {
        //         Toast.fire({
        //             icon: 'warning',
        //             title: 'Missing Data',
        //             text: 'Please fill and save data for at least one month.'
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