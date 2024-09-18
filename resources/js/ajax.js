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
            ...updatedService,
            id: refID
        },
        headers: header,
        success: function(response) {
            CallSuccess(response);
        },
        error: function(xhr, status, errors) {
            CallError(xhr, status, errors);
        }
    });
    console.log(updatedService);
    console.log(refID);
}

/** create new requirement/sub service
 *
 * @param {string} url
 * @param {Object} subService
 * @param {string} header
 * @param {function} success
 * @param {function} error
 */
export function NewSubServicec(url, subService, header, CallSuccess, CallError) {
    $.ajax({
        url: url,
        type: 'POST',
        data: subService,
        headers: header,
        success: function(response) {
            CallSuccess(response);
        },
        error: function(xhr, status, errors) {
            CallError(xhr, status, errors);
        }
    });
}

/** create new requirement/sub service
 *
 * @param {string} url
 * @param {Object} clientRep
 * @param {File} profile
 * @param {string} header
 * @param {function} success
 * @param {function} error
 */
export function NewClientRecord(url, client, profile, header, CallSuccess, CallError) {
    var formData = new FormData();
    for (var key in client) {
        if (client.hasOwnProperty(key)) {
            formData.append(key, client[key]);
        }
    }
    if (profile) {
        formData.append('profile', profile);
    }
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        headers: header,
        processData: false,           
        contentType: false, 
        success: function(response) {
            CallSuccess(response);
        },
        error: function(xhr, status, errors) {
            CallError(xhr, status, errors);
        }
    });
}