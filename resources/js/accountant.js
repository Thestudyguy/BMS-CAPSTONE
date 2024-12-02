var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000
});

$(document).ready(function () {
    $('select[name="JournalStatus"]').on('change', function () {
        var modal = $(this).closest('.modal');
        var textarea = modal.find('.journal-draft-note');
        if ($(this).val() === 'Rejected' || $(this).val() === 'Canceled') {
            textarea.removeClass('visually-hidden');
        } else {
            textarea.addClass('visually-hidden').val('');
        }
    });

    $('.update-journal-status').on('click', function () {
        var journalId = $(this).attr('id');
        console.log(journalId);
        var modal = $(`#update-journal-status-${journalId}`);
        var selectedStatus = modal.find(`#serviceprogress-${journalId}`).val();
        var textarea = modal.find(`#note-${journalId}`);

        if ((selectedStatus === 'Canceled' || selectedStatus === 'Rejected') && textarea.val().trim() === '') {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please provide a note when the status is "Canceled" or "Rejected".'
            });
            textarea.addClass('is-invalid');
            return;
        }

        var form = $(`.update-journal-status-form-${journalId}`).serializeArray();
        $.ajax({
            type: 'POST',
            url: '/update-journal-status',
            data: form,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                localStorage.setItem('journal-status', 'updated');
                location.reload();
            },
            error: function (error) {
                console.error(error);
            }
        });
    });

    if (localStorage.getItem('journal-status') === 'updated') {
        Toast.fire({
            icon: 'success',
            title: 'Journal Status Updated',
            text: 'Journal status updated successfully'
        });
        localStorage.removeItem('journal-status');
    }
});
