import './dom';
import './add-client-services';
import './journalDom';
import './coa-dom';
import './user';
import './settings';
import './client';
import './billing';
//global functions
$(document).ready(function() {
    window.formatValueInput = function(input) {
        var value = input.value.replace(/[^0-9\.]/g, '');
        var parts = value.split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        if (parts.length > 1) {
            input.value = parts[0] + '.' + parts[1];
        } else {
            input.value = parts[0];
        }
    };
});

