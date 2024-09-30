import './dom';
import './add-client-services';
import './journalDom';
import './coa-dom';

//global functions
$(document).ready(function(){
    window.formatValueInput = function (input) {
        var removeChar = input.value.replace(/[^0-9\.]/g, '');
        var removeDot = removeChar.replace(/\./g, '');
        input.value = removeDot
        var formatedNumber = input.value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        input.value = formatedNumber
    } 


    
});
