/** new service
 *
 * @param {string} url
 * @param {Object} serviceData
 * @param {function} success
 * @param {function} error
 */
export function NewService(url, serviceData, CallSuccess, CallError) {
    $.ajax({
        url: url,
        type: 'POST',
        data: serviceData,
        // headers: header,
        success: function(response) {
            CallSuccess(response);
        },
        error: function(xhr, status, errors) {
            CallError(xhr,status,errors);
        }
    });
}


/** remove/delete service
 *
 * @param {string} url
 * @param {string} header
 * @param {function} success
 * @param {function} error
 */
export function RemoveService(url, header, CallSuccess, CallError) {
    $.ajax({
        url: url,
        type: 'POST',
        headers: header,
        success: function(response) {
            CallSuccess(response);
        },
        error: function(xhr, status, errors) {
            CallError(xhr,status,errors);
        }
    });
}

/** editt/update service
 *
 * @param {string} url
 * @param {string} refID
 * @param {Object} updatedService
 * @param {string} header
 * @param {function} success
 * @param {function} error
 */
export function EditService(url, updatedService, refID, header, CallSuccess, CallError) {
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            data : updatedService,
            id : refID
        },
        headers: header,
        success: function(response) {
            CallSuccess(response);
        },
        error: function(xhr, status, errors) {
            CallError(xhr,status,errors);
        }
    });
    console.log(updatedService);
    console.log(refID);
    
}