$(document).ready(function () {
    // Initialize SweetAlert toast settings
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
    var preSubtotal = $('.test-total').text();
    console.log(preSubtotal);
    $('.overall-total').text(preSubtotal);
    // Click event for the plus icon
    $('.add-description').on('click', function () {
        var row = $(this).closest('tr');

        // Get data from the row's data attributes
        var service = row.data('service');
        var requirements = row.data('requirements');
        var category = row.data('category');
        var description = row.data('description');
        var taxType = row.data('taxtype');
        var formType = row.data('formtype');
        var price = row.data('price');

        // Check if the row is already added in the selected descriptions table
        var existingRow = $('#selected-descriptions-table tbody tr[data-service="' + service + '"][data-requirements="' + requirements + '"]');

        if (existingRow.length === 0) {
            var existsInBillingTable = $('.client-billing-services tbody tr').filter(function () {
                return $(this).find('td').eq(2).text().trim() === (category + " - " + description);
            }).length > 0;

            if (existsInBillingTable) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Conflict',
                    text: 'Account Description already in display'
                });
                return;
            }

            var newRow = `
                <tr data-service="${service}" data-requirements="${requirements}" data-price="${price}">
                    <td>${service}</td>
                    <td>${requirements}</td>
                    <td>${category}</td>
                    <td>${description}</td>
                    <td>${taxType}</td>
                    <td>${formType}</td>
                    <td>₱${parseFloat(price).toFixed(2)}</td>
                    <td><span class="badge bg-danger btn-sm remove-description"><i class='fas fa-trash'></i></span></td>
                </tr>`;

            $('#selected-descriptions-table tbody').append(newRow);
            $(this).hide(); // Hide the add button after adding

            calculateTotals();
        } else {
            Toast.fire({
                icon: 'warning',
                title: 'Conflict',
                text: 'Account Description already in display'
            });
        }
    });

    $('#selected-descriptions-table').on('click', '.remove-description', function () {
        $(this).closest('tr').remove();
        var service = $(this).closest('tr').data('service');
        var requirements = $(this).closest('tr').data('requirements');

        $('.add-description').each(function () {
            var row = $(this).closest('tr');
            if (row.data('service') === service && row.data('requirements') === requirements) {
                $(this).show();
            }
        });

        calculateTotals();
    });

    function calculateTotals() {
        let subtotal = 0;

        // Calculate subtotal for additional descriptions
        $('#selected-descriptions-table tbody tr').each(function () {
            const price = parseFloat($(this).data('price'));
            if (!isNaN(price)) {
                subtotal += price;
            }
        });

        // Update additional descriptions subtotal
        $('#additional-description-subtotal').text(subtotal.toFixed(2));

        // Get the total price from the main billing services
        let totalPrice = 0;
        $('.client-billing-services tbody tr').each(function () {
            const priceText = $(this).find('td:nth-child(4)').text().trim(); // Assuming the price is in the 4th column
            const price = parseFloat(priceText.replace(/₱/g, '').replace(/,/g, '')); // Remove currency symbol and commas
            if (!isNaN(price)) {
                totalPrice += price;
            }
        });

        // Calculate overall total
        const overallTotal = totalPrice + subtotal;
        $('.overall-total').text(overallTotal.toFixed(2));
    }

    $('.mail-client-bs').on('click', function(){
        var refID = $(this).attr('id');
        console.log(refID);
        
        $.ajax({
            type: 'POST',
            url: `mail-client-bs-${refID}`,
            // data: refID,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response){
                console.log(response);
                
            },
            error: function(error, status, jqXHR){
                console.log(error);
                
            },
        });
    });
});
