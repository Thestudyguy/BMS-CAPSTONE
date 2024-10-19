$(document).ready(function () {
    var servicesToBilling = window.servicesData;
    var adsToBilling = window.ads;
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
    var preSubtotal = $('.test-total').text();
    console.log(preSubtotal);
    $('.overall-total').text(preSubtotal);
    $('.add-description').on('click', function () {
        var row = $(this).closest('tr');

        var service = row.data('service');
        var requirements = row.data('requirements');
        var category = row.data('category');
        var description = row.data('description');
        var taxType = row.data('taxtype');
        var formType = row.data('formtype');
        var price = row.data('price');

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
            $(this).hide(); 

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

        $('#selected-descriptions-table tbody tr').each(function () {
            const price = parseFloat($(this).data('price'));
            if (!isNaN(price)) {
                subtotal += price;
            }
        });

        $('#additional-description-subtotal').text(subtotal.toFixed(2));

        let totalPrice = 0;
        $('.client-billing-services tbody tr').each(function () {
            const priceText = $(this).find('td:nth-child(4)').text().trim();
            const price = parseFloat(priceText.replace(/₱/g, '').replace(/,/g, ''));
            if (!isNaN(price)) {
                totalPrice += price;
            }
        });

        const overallTotal = totalPrice + subtotal;
        $('.overall-total').text(overallTotal.toFixed(2));
    }

    $('.mail-client-bs').on('click', function(){
        var refID = $(this).attr('id');
        console.log(refID);
        
        $.ajax({
            type: 'POST',
            url: `mail-client-bs-${refID}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function(response){
                console.log(response);
                
            },
            error: function(error, status, jqXHR){
                console.log(error);
                
            },
        });
    });

    $('.remove-service-from-billing').on('click', function(){
        console.log($(this).attr('id'));
        $(this).closest('tr').remove();
    });
});
