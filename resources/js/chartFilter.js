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


    $('#yearInput').change(function () {
        var year = $(this).val(); // Input year
    
        if (!year || isNaN(year)) {
            console.error("Invalid year entered.");
            return;
        }
    
        $.ajax({
            method: 'POST',
            url: 'filter-by-year-income',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            data: { year: year },
            success: function (response) {
                console.log('Year Data:', response);
    
                // Optionally process year data (e.g., for yearly summary)
                let labels = Object.keys(response); // Months
                let data = Object.values(response); // Totals
    
                // Update chart
                lineChart.data.labels = labels;
                lineChart.data.datasets[0].data = data;
                lineChart.update();
            },
            error: function (err) {
                console.error('AJAX Error:', err.responseText);
            }
        });
    });
    
    $('#yearInputExpense').change(function () {
        var year = $(this).val(); // Input year
    
        if (!year || isNaN(year)) {
            console.error("Invalid year entered.");
            return;
        }
    
        $.ajax({
            method: 'POST',
            url: 'filter-by-year-expense',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            data: { year: year },
            success: function (response) {
                console.log('Year Data:', response);
    
                // Optionally process year data (e.g., for yearly summary)
                let labels = Object.keys(response); // Months
                let data = Object.values(response); // Totals
    
                // Update chart
                expenseChart.data.labels = labels;
                expenseChart.data.datasets[0].data = data;
                expenseChart.update();
            },
            error: function (err) {
                console.error('AJAX Error:', err.responseText);
            }
        });
    });

    $('#yearInputClient').change(function () {
        var year = $(this).val(); // Input year
    
        if (!year || isNaN(year)) {
            console.error("Invalid year entered.");
            return;
        }
    
        $.ajax({
            method: 'POST',
            url: 'filter-by-year-client',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            data: { year: year },
            success: function (response) {
                console.log('Year Data:', response);
    
                // Optionally process year data (e.g., for yearly summary)
                let labels = Object.keys(response); // Months
                let data = Object.values(response); // Totals
    
                // Update chart
                clientsChart.data.labels = labels;
                clientsChart.data.datasets[0].data = data;
                clientsChart.update();
            },
            error: function (err) {
                console.error('AJAX Error:', err.responseText);
            }
        });
    });

    $('#yearlySales').change(function () {
        var year = $(this).val();
    
        if (!year || isNaN(year)) {
            console.error("Invalid year entered.");
            return;
        }
    
        $.ajax({
            method: 'POST',
            url: 'yearly-sales',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            data: { year: year },
            success: function (response) {
                console.log('success');
                
                var yearData = response.details;
                if (yearData) {
                    let labels = [];
                    let data = [];
    
                    $.each(yearData, function (month, total) {
                        labels.push(month);
                        data.push(total.total);
                    });
    
                    // Update chart
                    salesChart.data.labels = labels;
                    salesChart.data.datasets[0].data = data;
                    salesChart.update();
                } else {
                    console.error("No data found for year: " + year);
                }
            },
            error: function (err, status, jqXHR) {
                ToastError.fire({
                    icon: 'error',
                    title: 'Fatal Error',
                    text: 'Oops! Something went wrong: ' + err.responseText
                });
            }
        });
    });
    
});
