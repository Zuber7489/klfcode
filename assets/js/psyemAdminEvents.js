var psyem_event_ajax = (psyem_event_ajax) ? psyem_event_ajax : null;
var psyEventAjaxUrl = (psyem_event_ajax && psyem_event_ajax.event_ajaxurl) ? psyem_event_ajax.event_ajaxurl : '';
var psyEventNonce = (psyem_event_ajax && psyem_event_ajax.event_nonce) ? psyem_event_ajax.event_nonce : '';
var psyEventCopyAction = (psyem_event_ajax && psyem_event_ajax.event_copy_action) ? psyem_event_ajax.event_copy_action : '';
var serverError = (psyem_event_ajax && psyem_event_ajax.server_error) ? psyem_event_ajax.server_error : '';

jQuery(function ($) {
    let psyemEventMediaUploader;

    $('#psyemUploadEventMediaBtn').on('click', function (e) {
        e.preventDefault();

        if (psyemEventMediaUploader) {
            psyemEventMediaUploader.open();
            return;
        }

        psyemEventMediaUploader = wp.media({
            title: 'Select Files',
            button: {
                text: 'Add Files',
            },
            multiple: true
        });

        psyemEventMediaUploader.on('select', function () {
            const files = psyemEventMediaUploader.state().get('selection').toJSON();
            const uploadedFilesList = $('#psyemEventMediaFilesCont');
            const uploadedFilesInput = $('#psyem_event_media_urls');

            var uploadedFiles = [];
            try {
                uploadedFiles = JSON.parse(uploadedFilesInput.val() || '[]');
                if (!Array.isArray(uploadedFiles)) {
                    uploadedFiles = [];
                }
            } catch (e) {
                uploadedFiles = [];
            }

            files.forEach(function (file) {
                uploadedFiles.push(file.url);
                var fileHtml = `<div class="col-md-3 psyemEventMediaRow">
                                    <div class="card p-0">
                                        <div class="card-header border-0 bg-white p-1 text-end">
                                            <button type="button" class="btn btn-danger btn-sm psyemRemoveEventMediaBtn" data-furl="${file.url}">
                                                Remove
                                            </button>
                                        </div>
                                        <div class="card-body p-1">
                                            <img class="card-img" style="height: 20vh;" alt="${file.filename}" src="${file.url}">
                                        </div>
                                    </div>
                                </div>`;
                uploadedFilesList.append(fileHtml);
            });
            uploadedFilesInput.val(JSON.stringify(uploadedFiles));
        });

        psyemEventMediaUploader.open();
    });

    jQuery(document).on('click', '.psyemRemoveEventMediaBtn', function () {
        var pRBtn = jQuery(this);
        const listItem = pRBtn.parents('.psyemEventMediaRow');
        const fileUrl = pRBtn.data('furl');
        const uploadedFilesInput = jQuery('#psyem_event_media_urls');

        var uploadedFiles = [];
        try {
            uploadedFiles = JSON.parse(uploadedFilesInput.val() || '[]');
            if (!Array.isArray(uploadedFiles)) {
                uploadedFiles = [];
            }
        } catch (e) {
            uploadedFiles = [];
        }
        uploadedFiles = uploadedFiles.filter(file => file !== fileUrl);
        uploadedFilesInput.val(JSON.stringify(uploadedFiles));
        listItem.remove();
    });

    jQuery(document).on('click', '.psyemCopyEventData', function () {
        var $Btn = jQuery(this);
        var eventid = $Btn.attr('data-eventid');
        if (eventid > 0) {
            Swal.fire({
                title: "Are you sure?",
                text: "Want to create copy of clicked event?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-outline-secondary waves-effect'
                },
                buttonsStyling: true
            }).then((result) => {
                if (result.value) {
                    var copyFormData = new FormData();
                    copyFormData.append('event_id', eventid);
                    copyFormData.append('action', psyEventCopyAction);
                    copyFormData.append('_nonce', psyEventNonce);

                    jQuery.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: psyEventAjaxUrl,
                        data: copyFormData,
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
                            showHideButtonLoader($Btn, 'Hide');
                        },
                        error: function (errorInfo) {
                            showHideButtonLoader($Btn, 'Hide');
                            displayToaster(serverError, 'error');
                        }
                    });
                }
            });
        }
    });
});

jQuery(function ($) {
    $(document).on('change', '#psyem_event_registration_type', function () {
        var psyemSelBox = $(this);
        var psyemDrpVal = psyemSelBox.val();
        hideEventTicketTypesField();
        showEventTicketTypeField(psyemDrpVal);
    });

    setTimeout(() => {
        var psyemSelBox = jQuery('#psyem_event_registration_type');
        if (psyemSelBox) {
            var psyemDrpVal = psyemSelBox.val();
            hideEventTicketTypesField();
            showEventTicketTypeField(psyemDrpVal);
        }
    }, 2000);
});

function hideEventTicketTypesField() {
    jQuery('#psyem_event_tickets_cont').hide();
}

function showEventTicketTypeField(ftype) {
    if (ftype == 'Closed') {
        jQuery('#psyem_event_tickets_cont').show();
    }
    if (ftype == 'Paid') {
        jQuery('#psyem_event_tickets_cont').show();
    }
    if (ftype == 'Free') {
        jQuery('#psyem_event_tickets_cont').hide();
    }
}

