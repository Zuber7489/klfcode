var psyem_offline_ajax = (psyem_offline_ajax) ? psyem_offline_ajax : null;
var psyOfflineAjaxUrl = (psyem_offline_ajax && psyem_offline_ajax.offline_ajaxurl) ? psyem_offline_ajax.offline_ajaxurl : '';
var psyOfflineAction = (psyem_offline_ajax && psyem_offline_ajax.offline_action) ? psyem_offline_ajax.offline_action : '';
var psyOfflineNonce = (psyem_offline_ajax && psyem_offline_ajax.offline_nonce) ? psyem_offline_ajax.offline_nonce : '';
var psyOfflineRedirect = (psyem_offline_ajax && psyem_offline_ajax.offline_redirect) ? psyem_offline_ajax.offline_redirect : '';
var serverError = (psyem_offline_ajax && psyem_offline_ajax.server_error) ? psyem_offline_ajax.server_error : '';

jQuery(document).ready(function () {
    jQuery('.saveOfflineRegistrationBtn').on('click', function () {
        var isValidOrForm = psyemValidateOfflineRegistrationForm();
        if (isValidOrForm == 'True') {
            Swal.fire({
                title: "Are you sure?",
                text: "Want to create offline registration?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-outline-secondary waves-effect'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.value) {
                    psyemCreateOfflineRegistrationOrder();
                }
            });
        }
    });
});


function psyemCreateOfflineRegistrationOrder() {
    var $Btn = jQuery('.saveOfflineRegistrationBtn');
    var $form = jQuery('#offlineRegistrationForm');
    var formData = getFormData($form);
    formData['action'] = psyOfflineAction;
    formData['_nonce'] = psyOfflineNonce;

    jQuery.ajax({
        type: "POST",
        dataType: "JSON",
        url: psyOfflineAjaxUrl,
        data: formData,
        beforeSend: function () {
            showHideButtonLoader($Btn, 'Show');
        },
        error: function (jqXHR, exception) {
            showHideButtonLoader($Btn, 'Hide');
            displayToaster(serverError, 'error');
        },
        success: function (resp) {
            var status = (resp.status) ? resp.status : null;
            var message = (resp.message) ? resp.message : null;
            var validation = (resp.validation) ? resp.validation : null;

            if (status == 'success') {
                displayToaster(message, status);
                setTimeout(() => {
                    locationHref(psyOfflineRedirect);
                }, 2000);
            }
            if (status == 'error' && !validation) {
                displayToaster(message, status);
            }
            if (status && status == 'error' && validation && validation.length > 0) {
                displayToaster(validation[0], 'error');
            }
            setTimeout(() => {
                showHideButtonLoader($Btn, 'Hide');
            }, 4000);
        }
    });
}

function psyemValidateOfflineRegistrationForm() {

    var $form = jQuery('#offlineRegistrationForm');
    var offline_event = $form.find('select[name="offline_event"]');
    var offline_event_val = offline_event.val();

    var offline_firstname = $form.find('input[name="offline_firstname"]');
    var offline_firstname_val = offline_firstname.val();

    var offline_lastname = $form.find('input[name="offline_lastname"]');
    var offline_lastname_val = offline_lastname.val();

    var offline_email = $form.find('input[name="offline_email"]');
    var offline_email_val = offline_email.val();

    var offline_tickets = $form.find('input[name="offline_tickets"]');
    var offline_tickets_val = offline_tickets.val();

    if (offline_event_val > 0) { } else {
        displayToaster('Please select event', 'error');
        return 'False';
    }

    if (offline_firstname_val && offline_firstname_val.length > 0) { } else {
        displayToaster('Please enter first name', 'error');
        return 'False';
    }

    if (offline_lastname_val && offline_lastname_val.length > 0) { } else {
        displayToaster('Please enter last name', 'error');
        return 'False';
    }

    if (offline_email_val && offline_email_val.length > 0) { } else {
        displayToaster('Please enter email addresss', 'error');
        return 'False';
    }

    if (offline_tickets_val) {
        if (!psyem_ValidateInputNumber(offline_tickets_val)) {
            displayToaster('Number of participants must be greater than 0', 'error');
            return 'False';
        }
    } else {
        displayToaster('Please enter number of participants', 'error');
        return 'False';
    }

    return 'True';
}



