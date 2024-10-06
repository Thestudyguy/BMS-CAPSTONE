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

    let selectedMonths = [];
    let selectedAccount = '';

    $('#expense-category').on('change', function () {
        selectedAccount = $('#expense-category').val();
        $('.expense-form').removeClass('visually-hidden');
        $('.months-container').empty();
        $('.save-expense').addClass('visually-hidden');
    });

    $('.start-date, .end-date').on('change', function () {
        const startDate = $('.start-date').val();
        const endDate = $('.end-date').val();

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
                <button class="btn btn-primary save-months form-control">Save</button>
            </div>`;
            monthInputs += `</div>`;

            monthsContainer.html(monthInputs);

            $('.months-container').on('input', '.month-input', function () {
                formatValueInput(this);
            });
            
            let incomeObj = {};
            $(document).on('click', '.save-months', function (e) {
                e.preventDefault();
                let selectedMonths = [];
                let hasValue = false;
                $('.month-input').each(function () {
                    var monthName = $(this).attr('name');
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

                if (!hasValue) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Missing Fields',
                        text: 'Please fill at least one field'
                    });
                } else {
                    incomeObj[selectedAccount] = {
                        months: selectedMonths,
                        startDate: startDate,
                        endDate: endDate,
                    };

                    $.each(incomeObj, (account, element) => {
                        var tableHTML = `<table class="table table-hover client-journal-accounts">`;
                        var accountParts = account.split('_');
                        tableHTML += `
                            <tr class="client-journal-accounts" data-widget="expandable-table" aria-expanded="false">
                                <td>
                                    ${accountParts[1]} - ${element.startDate} - ${element.endDate}
                                    <span class="text-sm fw-bold float-right remove-saved-account" id=${accountParts[0]}><i class="fas fa-times"></i></span>
                                </td>
                            </tr>
                            <tr class="expandable-body bg-light client-journal-accounts">
                                <td>
                                    <div class="p-0 text-center">
                                        <table class="table table-hover float-left">
                                        <thead>
                                         <tr>
                                        <td>Month</td>
                                        <td>amount</td>
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
                        $('#test-table-yawa').html(tableHTML);
                    });
                    
                    console.log(incomeObj);
                    $('.months-container').empty();
                    $('#expense-category').val('');
                    $('.end-date').val('');
                    $('.start-date').val('');
                }
            });
        }
    });

    // selectedMonths.push({ 
    //     monthName, 
    //     value: monthValue,
    //     account: selectedAccount,
    //     startDate: startDate,
    //     endDate: endDate
    // });

    // Multi-step form navigation

    $(document).on('click', '.remove-saved-account', function(e){
        const accountId = $(this).attr('id');
        console.log(accountId); // For debugging
        delete incomeObj[accountId];
    });
    $('.next-btn').on('click', function () {
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
});
