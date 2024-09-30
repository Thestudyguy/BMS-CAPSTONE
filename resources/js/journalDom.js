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

$(document).ready(function() {

    var expenses = {};

    $('#expense-category').on('change', function(){
        $('.expense-form').removeClass('visually-hidden');
        $('.months-container').empty();
        $('.save-expense').addClass('visually-hidden');
    });
    

    $('.start-date, .end-date').on('change', function() {
        const startDate = $('.start-date').val();
        const endDate = $('.end-date').val();
    
        if (startDate && endDate) {
        $('.save-expense').removeClass('visually-hidden');
        const start = new Date(startDate);
            const end = new Date(endDate);
            const monthsContainer = $('.months-container');
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
    
            let current = new Date(start);
            while (current <= end) {
                const monthYear = current.toLocaleString('default', { month: 'long', year: 'numeric' });
                monthsContainer.append(`
                    <div class="col-sm-12 my-2">
                    <div class="input-group">
                    <input type="text" class="form-control month-input" name="${monthYear}" id="" placeholder='${monthYear}'>
                    </div>
                    </div>
                `);
                current.setMonth(current.getMonth() + 1);
            }
            $('.months-container').on('input', '.month-input', function() {
                formatValueInput(this);
                console.log($(this).val());
                
            });
        }
    });
});
