import './dom';
import './add-client-services';
import './journalDom';
import './coa-dom';
import './user';
import './settings';
import './client';
import './billing';
import './pdf';
import './journal-pin-entry';
import './accountant';
import './edit-journal';
import './service-req';
import './chartFilter';
//global functions
$(document).ready(function(){
    
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
    
    window.previewImage = function(event, previewId) {
        const fileInput = event.target;
        const file = fileInput.files[0]; 
    
        if (file) {
            const reader = new FileReader();
    
            reader.onload = function (e) {
                const previewElement = document.getElementById(previewId);
                if (previewElement) {
                    previewElement.src = e.target.result;
                } else {
                    console.error(`Preview element with ID ${previewId} not found.`);
                }
            };
    
            reader.readAsDataURL(file);
        } else {
            console.warn('No file selected.');
        }
    };
    
        $('.settings-info-box').hover(
            function() {
                $(this).find('.fa-trash').removeClass('visually-hidden'); // Show on hover
            },
            function() {
                $(this).find('.fa-trash').addClass('visually-hidden'); // Hide when not hovering
            }
        );
    
});