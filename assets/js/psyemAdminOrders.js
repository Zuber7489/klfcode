var psyOrderAjaxUrl = psyem_order_ajax.order_ajaxurl;
var psyOrderNonce = psyem_order_ajax.order_nonce;
var psyOrderCsvAction = psyem_order_ajax.order_csv_action;
var psyOrderSendAction = psyem_order_ajax.order_send_ticket_action;
var serverError = psyem_order_ajax.server_error;

jQuery(document).ready(function () {

    jQuery('#inputGroupFileCsvBtn').on('click', function () {
        uploadParticipantCsvData();
    });


    jQuery('.sendSelectedOrderTicket').on('click', function () {
        var $Btn = jQuery(this);
        var order = $Btn.data('order');
        var participantsRowsCont = jQuery('.participantsRowsCont');
        var participants = [];
        jQuery.each(participantsRowsCont.find('.selectParticipant'), function (index, item) {
            var selectedParticipantInput = jQuery(item);
            if (selectedParticipantInput.is(':checked') || selectedParticipantInput.prop('checked')) {
                participants.push(selectedParticipantInput.val());
            }
        });

        if (participants && participants.length > 0) {
            Swal.fire({
                title: "Are you sure?",
                text: "Want to send ticket to selected participants?",
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
                    sendOrderTickets($Btn, order, participants);
                }
            });
        } else {
            displayToaster('Please select participant', 'error');
        }
    });


    jQuery('.sendOrderTicket').on('click', function () {
        var $Btn = jQuery(this);
        var order = $Btn.data('order');
        var participant = $Btn.data('participant');
        var participants = [];
        if (participant > 0) { participants.push(participant); }

        if (participants && participants.length > 0) {
            Swal.fire({
                title: "Are you sure?",
                text: "Want to send ticket to selected participant?",
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
                    sendOrderTickets($Btn, order, participants);
                }
            });
        } else {
            displayToaster('Please select participant', 'error');
        }
    });


    jQuery(document).on('change', '#selectAllParticipants', function () {
        var selectAllParticipants = jQuery(this);
        var participantsRowsCont = jQuery('.participantsRowsCont');
        if (selectAllParticipants.is(':checked') || selectAllParticipants.prop('checked')) {
            jQuery.each(participantsRowsCont.find('.selectParticipant'), function (index, item) {
                var selectParticipantRow = jQuery(this);
                selectParticipantRow.prop('checked', true);
            });
        } else {
            jQuery.each(participantsRowsCont.find('.selectParticipant'), function (index, item) {
                var selectParticipantRow = jQuery(this);
                selectParticipantRow.prop('checked', false);
            });

        }
    });
});

function uploadParticipantCsvData() {
    var $Btn = jQuery('#inputGroupFileCsvBtn');

    var participantOrderIdInput = jQuery('input[name="participant_order_id"]');
    var participant_order_id = (participantOrderIdInput && participantOrderIdInput.length > 0) ? participantOrderIdInput.val() : 0;

    var participantCsvFileInput = jQuery('input[name="participant_csv_file"]');
    var participant_csv_file = null;

    if (participantCsvFileInput && participantCsvFileInput.length > 0) {
        participant_csv_file = participantCsvFileInput[0].files[0];
    }

    if (!participant_csv_file) {
        displayToaster('Please select a CSV file', 'error');
        return;
    }

    var csvFormData = new FormData();
    csvFormData.append('participant_order_id', participant_order_id);
    csvFormData.append('participant_csv_file', participant_csv_file);
    csvFormData.append('action', psyOrderCsvAction); // Set the AJAX action
    csvFormData.append('_nonce', psyOrderNonce); // Pass the nonce for security

    jQuery.ajax({
        type: "POST",
        dataType: "JSON",
        url: psyOrderAjaxUrl,
        data: csvFormData,
        processData: false,
        contentType: false,
        accept: { json: 'application/json' },
        beforeSend: function () {
            showHideButtonLoader($Btn, 'Show');
        },      
        success: function (resp) {
            showHideButtonLoader($Btn, 'Hide');
            var status = (resp.status) ? resp.status : null;
            var message = (resp.message) ? resp.message : null;
            var validation = (resp.validation) ? resp.validation : null;
            if (status == 'success') {
                displayToaster(message, status);
                setTimeout(() => {
                    locationReload();
                }, 2000);
            }
            if (status == 'error' && !validation) {
                displayToaster(message, status);
            }
            if (status && status == 'error' && validation && validation.length > 0) {
                displayToaster(validation[0]);
            }
        },
        error: function (errorInfo) {
            showHideButtonLoader($Btn, 'Hide');
            displayToaster(serverError, 'error');
        }
    });
}

function sendOrderTickets($Btn, order, participants) {

    if (order > 0) {
        var ticketFormData = new FormData();
        ticketFormData.append('order_id', order);
        ticketFormData.append('action', psyOrderSendAction);
        ticketFormData.append('_nonce', psyOrderNonce);
        if (participants && participants.length > 0) {
            jQuery.each(participants, function (index, value) {
                ticketFormData.append('participants[]', value);
            });
        }

        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: psyOrderAjaxUrl,
            data: ticketFormData,
            processData: false,
            contentType: false,
            accept: { json: 'application/json' },
            beforeSend: function () {
                showHideButtonLoader($Btn, 'Show');
            },
            success: function (resp) {
                var status = (resp.status) ? resp.status : null;
                var message = (resp.message) ? resp.message : null;
                var validation = (resp.validation) ? resp.validation : null;
                if (status == 'success') {
                    displayToaster(message, status);
                }
                if (status == 'error' && !validation) {
                    displayToaster(message, status);
                }
                if (status && status == 'error' && validation && validation.length > 0) {
                    displayToaster(validation[0]);
                }
                showHideButtonLoader($Btn, 'Hide');
            },
            error: function (errorInfo) {
                showHideButtonLoader($Btn, 'Hide');
                displayToaster(serverError, 'error');
            }
        });
    }
}