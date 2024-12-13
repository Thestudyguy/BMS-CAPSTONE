$(document).ready(function(){
    var defaultLabels = window.defaultLabels;  // All months as labels
var defaultExpense = window.defaultExpense; // Your default expenses data
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

    $('.expense-quarter').change(function () {
        var quarter = $(this).val();
        if (!quarter || !['Q1', 'Q2', 'Q3', 'Q4'].includes(quarter)) {
            console.error("Invalid quarter selected.");
            return;
        }

        $.ajax({
            method: 'POST',
            url: `quarterly-expense-${quarter}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                var quarterData = response[quarter];
                if (quarterData) {
                    let labels = [];
                    let data = [];

                    $.each(quarterData.details, function(month, total) {
                        labels.push(month);
                        data.push(total.total);
                    });

                    expenseChart.data.labels = labels;
                    expenseChart.data.datasets[0].data = data;
                    expenseChart.update();
                } else {
                    console.error("No data found for quarter: " + quarter);
                }
            },
            error: function (err, status, jqXHR) {
                ToastError.fire({
                    icon: 'error',
                    title: 'Fatal Error',
                    text: 'Oops! Something went wrong: ' + err
                });
            }
        });
    });

    $('.income-quarter').change(function () {
        var quarter = $(this).val();

        if (!quarter || !['Q1', 'Q2', 'Q3', 'Q4'].includes(quarter)) {
            console.error("Invalid quarter selected.");
            return;
        }

        $.ajax({
            method: 'POST',
            url: `quarterly-income-${quarter}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                var quarterData = response[quarter];
                if (quarterData) {
                    let labels = [];
                    let data = [];

                    $.each(quarterData.details, function(month, total) {
                        labels.push(month);
                        data.push(total.total);
                    });

                    lineChart.data.labels = labels;
                    lineChart.data.datasets[0].data = data;
                    lineChart.update();
                } else {
                    console.error("No data found for quarter: " + quarter);
                }
            },
            error: function (err, status, jqXHR) {
                ToastError.fire({
                    icon: 'error',
                    title: 'Fatal Error',
                    text: 'Oops! Something went wrong: ' + err
                });
            }
        });
    });
    $('.sales-quarter').change(function () {
        var quarter = $(this).val();

        if (!quarter || !['Q1', 'Q2', 'Q3', 'Q4'].includes(quarter)) {
            console.error("Invalid quarter selected.");
            return;
        }

        $.ajax({
            method: 'POST',
            url: `quarterly-sales-${quarter}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                var quarterData = response[quarter];
                if (quarterData) {
                    let labels = [];
                    let data = [];

                    $.each(quarterData.details, function(month, total) {
                        labels.push(month);
                        data.push(total.total);
                    });

                    salesChart.data.labels = labels;
                    salesChart.data.datasets[0].data = data;
                    salesChart.update();
                } else {
                    console.error("No data found for quarter: " + quarter);
                }
            },
            error: function (err, status, jqXHR) {
                ToastError.fire({
                    icon: 'error',
                    title: 'Fatal Error',
                    text: 'Oops! Something went wrong: ' + err
                });
            }
        });
    });




    $('.client-quarter').change(function () {
        var quarter = $(this).val();
    
        if (!quarter || !['Q1', 'Q2', 'Q3', 'Q4'].includes(quarter)) {
            console.error("Invalid quarter selected.");
            return;
        }
    
        $.ajax({
            method: 'POST',
            url: `/quarterly-client-${quarter}`,  // Ensure the URL matches your route
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                var quarterData = response[quarter];
    
                if (quarterData) {
                    // Prepare the labels and data arrays for the chart
                    let labels = quarterData.months;  // Use the months of the selected quarter
                    let data = [];
    
                    // Fill the data array with the total clients for each month in the selected quarter
                    for (let month of labels) {
                        data.push(quarterData.details[month] ? quarterData.details[month].total : 0);
                    }
    
                    // Update the chart with the new data
                    clientsChart.data.labels = labels;
                    clientsChart.data.datasets[0].data = data;
                    clientsChart.update();  // Refresh the chart with new data
                } else {
                    console.error("No data found for quarter: " + quarter);
                }
            },
            error: function (err) {
                ToastError.fire({
                    icon: 'error',
                    title: 'Fatal Error',
                    text: 'Oops! Something went wrong: ' + err
                });
            }
        });
    });
    
    
});
