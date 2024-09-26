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
    $('#createNewSelect').on('change', function() {
        const selectedValue = $(this).val();
        if (selectedValue === 'Journal') {
            const modalTarget = $(this).find('option:selected').data('bs-target');
            const modal = new bootstrap.Modal($(modalTarget));
            modal.show();
        }
    });

    $('.start-date, .end-date').on('change', function() {
        const startDate = $('.start-date').val();
        const endDate = $('.end-date').val();
    
        if (startDate && endDate) {
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
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <span class="input-group-text">${monthYear}</span>
                        </div>
                        <div class="col-sm-6">
                            <input type="number" name='month[]' class="form-control" placeholder="Amount">
                        </div>
                    </div>
                    <button class='sddd btn btn-primary'>submit</button>
                `);
                current.setMonth(current.getMonth() + 1);
            }
        }
    });
});
