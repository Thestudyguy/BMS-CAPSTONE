$(document).ready(function() {
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

    $('.next-btn').on('click', function() {
        

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

    $('.prev-btn').on('click', function() {
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
        $('.step').each(function(index) {
            if (index < step) {
                $(this).addClass('active');
                $(this).next('.indicator-line').addClass('active');
            }
        });
    }

    function validateStep(step) {
        let valid = true;
        if (step === 1) {
            if ($('#expense-category').val() === "") {
                valid = false;
            }
        }
        return valid;
    }

    showStep(currentStep);
    updateStepIndicator(currentStep);
});


// $('#expense-category').on('change', function(){
//     $('.expense-form').removeClass('visually-hidden');
//     $('.months-container').empty();
//     $('.save-expense').addClass('visually-hidden');
// });


// $('.start-date, .end-date').on('change', function() {
//     const startDate = $('.start-date').val();
//     const endDate = $('.end-date').val();

//     if (startDate && endDate) {
//     $('.save-expense').removeClass('visually-hidden');
//     const start = new Date(startDate);
//         const end = new Date(endDate);
//         const monthsContainer = $('.months-container');
//         const monthDifference = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start.getMonth());
//         if (monthDifference > 12) {
//             ToastError.fire({
//                 icon: 'error',
//                 title: 'The selected period exceeds 12 months.'
//             });
//             return;
//         }

//         const fiscalYearEnd = new Date(start);
//         fiscalYearEnd.setMonth(fiscalYearEnd.getMonth() + 12);

//         if (end > fiscalYearEnd) {
//             ToastError.fire({
//                 icon: 'error',
//                 title: 'The selected period must not exceed the fiscal year based on the start date.'
//             });
//             return;
//         }

//         monthsContainer.empty();

//         let current = new Date(start);
//         while (current <= end) {
//             const monthYear = current.toLocaleString('default', { month: 'long', year: 'numeric' });
//             monthsContainer.append(
//                 <div class="col-sm-12 my-2">
//                 <div class="input-group">
//                 <input type="text" class="form-control month-input" name="${monthYear}" id="" placeholder='${monthYear}'>
//                 </div>
//                 </div>
//             );
//             current.setMonth(current.getMonth() + 1);
//         }
//         $('.months-container').on('input', '.month-input', function() {
//             formatValueInput(this);
//             console.log($(this).val());
            
//         });
//     }
// });