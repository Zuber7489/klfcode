var psyAjaxUrl = psyem_ajax.ajaxurl;
var psySettingsAction = psyem_ajax.settings_action;
var psyProjectSafeTypeAction = psyem_ajax.projectsafe_type_action;
var psySettingsNonce = psyem_ajax.settings_nonce;
var serverError = psyem_ajax.server_error;

jQuery(document).ready(function () {
    jQuery(document).on('click', '.savePsyemSettings', function () {
        saveSettingsData();
    });

    jQuery(document).on('click', '.projectsafeTypeAddBtn', function (ev) {
        var $btn = jQuery(this);
        addProjectSafeType($btn);
    });

    jQuery(document).on('click', '.projectsafeTypeRemoveBtn', function (ev) {
        var $btn = jQuery(this);
        removeProjectSafeType($btn);
    });
});

function showHideButtonLoader($Btn, stype) {
    if ($Btn && $Btn.length > 0) {
        var $btnLoader = $Btn.find('.buttonLoader');
        if ($btnLoader && $btnLoader.length > 0) {
            if (stype == 'Show') {
                $btnLoader.show('fast');
                $Btn.attr('disabled', true);
                $Btn.prop('disabled', true);
            }
            if (stype == 'Hide') {
                $btnLoader.hide('fast');
                $Btn.prop('disabled', false);
                $Btn.removeAttr("disabled");
            }
        }
    }
}

function EnableDisableButton($Btn, stype) {
    if ($Btn && $Btn.length > 0) {
        if (stype == true) {
            $Btn.prop('disabled', false);
            $Btn.removeAttr("disabled");
        }
        if (stype == false) {
            $Btn.attr('disabled', true);
            $Btn.prop('disabled', true);
        }
    }
}

function getFormData($form) {
    var formData = {};
    if ($form && $form.length > 0) {
        jQuery.each(
            $form.find('input[type="hidden"], input[type="text"], input[type="number"],input[type="checkbox"], select, textarea'),
            function (index, ele) {
                if (jQuery(ele).attr('name')) {
                    formData[jQuery(ele).attr('name')] = jQuery(ele).val();
                }
            }
        );
    }
    return formData;
}

function saveSettingsData() {
    var $Btn = jQuery('.savePsyemSettings');
    var $form = jQuery('#SettingsForm');
    var formData = getFormData($form);
    formData['action'] = psySettingsAction;
    formData['_nonce'] = psySettingsNonce;

    jQuery.ajax({
        type: "POST",
        dataType: "JSON",
        url: psyAjaxUrl,
        data: formData,
        beforeSend: function () {
            showHideButtonLoader($Btn, 'Show');
        },
        complete: function () {
            showHideButtonLoader($Btn, 'Hide');
        },
        error: function (jqXHR, exception) {
            showHideButtonLoader($Btn, 'Hide');
            displayToaster(serverError, 'error');
        },
        success: function (resp) {
            showHideButtonLoader($Btn, 'Hide');
            var status = (resp.status) ? resp.status : null;
            var message = (resp.message) ? resp.message : null;
            var data = (resp.data) ? resp.data : null;
            var validation = (resp.validation) ? resp.validation : null;

            if (status == 'success') {
                displayToaster(message, status);
            }
            if (status == 'error' && !validation) {
                displayToaster(message, status);
            }
            if (status && status == 'error' && validation && validation.length > 0) {
                displayToaster(message, status);
            }
        }
    });
}

function addProjectSafeType(elm) {
    if (elm && elm.length > 0) {
        var task = elm.data('task');
        var elmParent = elm.parents('.pstypeRowCont');
        var projectsafeTypesCont = jQuery('.projectsafeTypesCont');

        if (task && task.length > 0 && task == 'Create') {
            const typeFormData = new FormData();
            typeFormData.append('action', psyProjectSafeTypeAction);
            typeFormData.append('_nonce', psySettingsNonce);
            typeFormData.append('task', task);

            var projectsafe_title = '';
            var projectsafe_titleInput = jQuery('input[name="projectsafe_title"]');
            projectsafe_title = (projectsafe_titleInput && projectsafe_titleInput.val()) ? projectsafe_titleInput.val() : '';
            typeFormData.append('title', projectsafe_title);


            if ((projectsafe_title && projectsafe_title.length > 0)) {
                Swal.fire({
                    title: 'Are you sure want to create new record?',
                    text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        jQuery.ajax({
                            url: psyAjaxUrl,
                            type: 'POST',
                            data: typeFormData,
                            cache: false,
                            mimeType: "multipart/form-data",
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            accept: { json: 'application/json' },
                            beforeSend: function () {
                                showPanelLoader(elmParent);
                            },
                            error: function (jqXHR, exception) {
                                displayToaster(serverError, 'error');
                            },
                            success: function (resp) {
                                var status = (resp.status) ? resp.status : null;
                                var message = (resp.message) ? resp.message : null;
                                var rhtml = (resp.rhtml) ? resp.rhtml : null;
                                if (status == 'success') {
                                    if (rhtml && rhtml.length > 0 && projectsafeTypesCont && projectsafeTypesCont.length > 0) {
                                        projectsafeTypesCont.prepend(jQuery(rhtml));
                                        projectsafe_titleInput.val('').change();
                                    }
                                }
                                hidePanelLoader(elmParent);
                                displayToaster(message, status);
                            }
                        });
                    }
                });
            } else {
                displayToaster('Please fill the type title', 'error');
            }
        }
    }
}

function removeProjectSafeType(elm) {
    if (elm && elm.length > 0) {
        var task = elm.data('task');
        var row_slug = elm.data('slug');
        var elmParent = elm.parents('.pstypeRowCont');
        var projectsafeTypesCont = jQuery('.projectsafeTypesCont');

        if (task && task.length > 0 && task == 'Remove') {
            const typeFormData = new FormData();
            typeFormData.append('action', psyProjectSafeTypeAction);
            typeFormData.append('_nonce', psySettingsNonce);
            typeFormData.append('task', task);
            typeFormData.append('row_slug', row_slug);

            if ((row_slug && row_slug.length > 0)) {
                Swal.fire({
                    title: 'Are you sure want to permanently delete this record?',
                    text: "You won't be able to revert this",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        jQuery.ajax({
                            url: psyAjaxUrl,
                            type: 'POST',
                            data: typeFormData,
                            cache: false,
                            mimeType: "multipart/form-data",
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            accept: { json: 'application/json' },
                            beforeSend: function () {
                                showPanelLoader(elmParent);
                            },
                            error: function (jqXHR, exception) {
                                displayToaster(serverError, 'error');
                            },
                            success: function (resp) {
                                var status = (resp.status) ? resp.status : null;
                                var message = (resp.message) ? resp.message : null;
                                var rhtml = (resp.rhtml) ? resp.rhtml : null;
                                if (status == 'success') {
                                    if (task == 'Remove') {
                                        elmParent.remove();
                                    }
                                }
                                hidePanelLoader(elmParent);
                                displayToaster(message, status);
                            }
                        });
                    }
                });
            } else {
                displayToaster('Shortcode slug is missing', 'error');
            }
        }
    }
}