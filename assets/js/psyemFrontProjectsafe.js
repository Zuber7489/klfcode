var page_params = (psyem_projectsafe) ? (psyem_projectsafe) : null;
var psyemSaveAction = (page_params && page_params.projectsafe_form_action) ? page_params.projectsafe_form_action : '';
var psyemAjaxUrl = (page_params && page_params.projectsafe_ajaxurl) ? page_params.projectsafe_ajaxurl : '';
var psyemFormkey = (page_params && page_params.projectsafe_key) ? page_params.projectsafe_key : '';

var csrf_token_name = '_nonce';
var csrf_token_value = (page_params && page_params.projectsafe_nonce) ? page_params.projectsafe_nonce : '';
var serverError = (page_params && page_params.server_error) ? page_params.server_error : '';

let psyemPaticipantForm = jQuery('#psyemFirstStepForm');
let psyemPaticipantBtn = jQuery('#psyemFirstStepBtn');

let psyemContactForm = jQuery('#psyemSecondStepForm');
let psyemContactBtn = jQuery('#psyemSecondStepBtn');

var PsyemProjectSafePanel = function () {

    var psyemRunSetupData = function () {
        jQuery(document).on('click', '#psyemFirstStepBtn', function () {
            var isValid = psyemRunValidateParticipantData();
            if (isValid == 'TRUE') {
                psyemRunManageParticipantData();
            }
        });
        jQuery(document).on('click', '#psyemSecondStepBtn', function () {
            var isValid = psyemRunValidateContactData();
            if (isValid == 'TRUE') {
                psyemRunManageContactData();
            }
        });
        jQuery(document).on('change', 'select[name="field_region"]', function () {
            var field_region = jQuery(this);
            var fieldRegionVal = field_region.val();
            psyem_manageDistrictField(fieldRegionVal);
        });
        setTimeout(() => {
            psyem_manageDistrictField('');
        }, 5000);
    };

    var psyemRunValidateParticipantData = function () {

        var field_first_name = psyemPaticipantForm.find('input[name="field_first_name"]');
        if (!field_first_name.val()) {
            displayToaster('First name field is required', 'info');
            return 'FALSE';
        } else {
            var isValidName = validateUsername(field_first_name.val());
            if (!isValidName) {
                displayToaster('First name is invalid, Add only alphabets [a-zA-Z]', 'info');
                return 'FALSE';
            }
        }
        var field_last_name = psyemPaticipantForm.find('input[name="field_last_name"]');
        if (!field_last_name.val()) {
            displayToaster('Last name field is required', 'info');
            return 'FALSE';
        } else {
            var isValidName = validateUsername(field_last_name.val());
            if (!isValidName) {
                displayToaster('Last name is invalid, Add only alphabets [a-zA-Z]', 'info');
                return 'FALSE';
            }
        }
        var field_gender = psyemPaticipantForm.find('select[name="field_gender"]');
        if (!field_gender.val()) {
            displayToaster('Gender field is required', 'info');
            return 'FALSE';
        }
        var field_dob_day = psyemPaticipantForm.find('select[name="field_dob_day"]');
        if (!field_dob_day.val()) {
            displayToaster('DOB date field is required', 'info');
            return 'FALSE';
        }
        var field_dob_month = psyemPaticipantForm.find('select[name="field_dob_month"]');
        if (!field_dob_month.val()) {
            displayToaster('DOB month field is required', 'info');
            return 'FALSE';
        }
        var field_dob_year = psyemPaticipantForm.find('select[name="field_dob_year"]');
        if (!field_dob_year.val()) {
            displayToaster('DOB year field is required', 'info');
            return 'FALSE';
        }
        var field_sexual_experience = psyemPaticipantForm.find('select[name="field_sexual_experience"]');
        if (!field_sexual_experience.val()) {
            displayToaster('Sexual experience field is required', 'info');
            return 'FALSE';
        }
        var field_cervical_screening = psyemPaticipantForm.find('select[name="field_cervical_screening"]');
        if (!field_cervical_screening.val()) {
            displayToaster('Cervical screening field is required', 'info');
            return 'FALSE';
        }
        var field_undergoing_treatment = psyemPaticipantForm.find('select[name="field_undergoing_treatment"]');
        if (!field_undergoing_treatment.val()) {
            displayToaster('Undergoing treatment field is required', 'info');
            return 'FALSE';
        }
        var field_received_hpv = psyemPaticipantForm.find('select[name="field_received_hpv"]');
        if (!field_received_hpv.val()) {
            displayToaster('Received HPV vaccine field is required', 'info');
            return 'FALSE';
        }
        var field_pregnant = psyemPaticipantForm.find('select[name="field_pregnant"]');
        if (!field_pregnant.val()) {
            displayToaster('Are you pregnant field is required', 'info');
            return 'FALSE';
        }
        var field_hysterectomy = psyemPaticipantForm.find('select[name="field_hysterectomy"]');
        if (!field_hysterectomy.val()) {
            displayToaster('Did you have a hysterectomy field is required', 'info');
            return 'FALSE';
        }
        var field_agree_clinical = psyemPaticipantForm.find('input[name="field_agree_clinical"]');
        if (field_agree_clinical.is(':checked') || field_agree_clinical.prop('checked')) {
        } else {
            displayToaster('Clinical study consent field is required', 'info');
            return 'FALSE';
        }
        var field_info_sheet = psyemPaticipantForm.find('input[name="field_info_sheet"]');
        if (field_info_sheet.is(':checked') || field_info_sheet.prop('checked')) {
        } else {
            displayToaster('Information sheet consent field is required', 'info');
            return 'FALSE';
        }
        var field_participation = psyemPaticipantForm.find('input[name="field_participation"]');
        if (field_participation.is(':checked') || field_participation.prop('checked')) {
        } else {
            displayToaster('Voluntary participation consent field is required', 'info');
            return 'FALSE';
        }
        var field_agree_study = psyemPaticipantForm.find('input[name="field_agree_study"]');
        if (field_agree_study.is(':checked') || field_agree_study.prop('checked')) {
        } else {
            displayToaster('Cervical screening program consent field is required', 'info');
            return 'FALSE';
        }
        var field_agree_doctor = psyemPaticipantForm.find('input[name="field_agree_doctor"]');
        if (field_agree_doctor.is(':checked') || field_agree_doctor.prop('checked')) {
        } else {
            displayToaster('Professional opinion consent field is required', 'info');
            return 'FALSE';
        }
        var field_agree_tnc = psyemPaticipantForm.find('input[name="field_agree_tnc"]');
        if (field_agree_tnc.is(':checked') || field_agree_tnc.prop('checked')) {
        } else {
            displayToaster('Terms and conditions consent field is required', 'info');
            return 'FALSE';
        }

        var pdob = field_dob_year.val() + '-' + field_dob_month.val() + '-' + field_dob_day.val();
        var particpant_age = calculateAgeInYears(pdob);
        var params = {
            'field_undergoing_treatment': field_undergoing_treatment.val(),
            'field_cervical_screening': field_cervical_screening.val(),
            'field_sexual_experience': field_sexual_experience.val(),
            'field_hysterectomy': field_hysterectomy.val(),
            'field_received_hpv': field_received_hpv.val(),
            'field_pregnant': field_pregnant.val(),
            'field_gender': field_gender.val(),
            'age_year': particpant_age
        };

        var isEligible = psyem_isEligibleForRegistration(params);

        if (isEligible == 'No') {
            Swal.fire({
                text: "Sorry, You cannot meet the project requirement",
                icon: 'warning',
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonText: "Ok",
                buttonsStyling: true
            }).then((result) => {
                if (result.value) {
                }
            });
            // show pop up
            return 'FALSE';
        }
        return 'TRUE';

    };

    var psyemRunManageParticipantData = function () {

        const pformData = new FormData(psyemPaticipantForm[0]);
        pformData.append('action', psyemSaveAction);
        pformData.append('field_form_key', psyemFormkey);
        pformData.append('field_form_type', 'Participant');
        pformData.append(csrf_token_name, csrf_token_value);

        jQuery.ajax({
            url: psyemAjaxUrl,
            type: 'POST',
            data: pformData,
            cache: false,
            mimeType: "multipart/form-data",
            dataType: 'json',
            processData: false,
            contentType: false,
            accept: { json: 'application/json' },
            beforeSend: function () {
                showHideButtonLoader(psyemPaticipantBtn, 'Show');
            },
            success: function (result) {
                const { status, message, validation, data } = result;
                var record_id = (data && data.record_id) ? data.record_id : 0;
                var movenext = (data && data.movenext) ? data.movenext : '';

                if (status == 'success') {
                    displayToaster(message, status);
                    if (movenext == 'Yes') {
                        psyemSwitchToStep(2);
                        scrollWindowToTop(psyemContactForm);
                    }
                }

                if (status == 'error' && !validation) {
                    displayToaster(message, status);
                }

                if (status && status == 'error' && validation && validation.length > 0) {
                    displayToaster(validation[0], 'info');
                }
                showHideButtonLoader(psyemPaticipantBtn, 'Hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                displayToaster(serverError, 'info');
                showHideButtonLoader(psyemPaticipantBtn, 'Hide');
            }
        });
    };

    var psyemRunValidateContactData = function () {
        var field_contact_sms = psyemContactForm.find('input[name="field_contact_sms"]');
        var field_contact_email = psyemContactForm.find('input[name="field_contact_email"]');
        if ((field_contact_sms.is(':checked') || field_contact_sms.prop('checked')) || (field_contact_email.is(':checked') || field_contact_email.prop('checked'))) { } else {
            displayToaster('Contact way field is required', 'info');
            return 'FALSE';
        }
        var field_phone = psyemContactForm.find('input[name="field_phone"]');
        if (!field_phone.val()) {
            displayToaster('Phone field is required', 'info');
            return 'FALSE';
        } else {
            var isValidPhone = validatePhoneNumber(field_phone.val());
            if (!isValidPhone) {
                displayToaster('Phone field input is invalid, must be a 10 digits valid phone number', 'info');
                return 'FALSE';
            }
        }
        var field_email = psyemContactForm.find('input[name="field_email"]');
        if (!field_email.val()) {
            displayToaster('Email field is required', 'info');
            return 'FALSE';
        } else {
            var isValidEmail = validateEmailAddress(field_email.val());
            if (!isValidEmail) {
                displayToaster('Email is not valid, Please enter valid email address', 'info');
                return 'FALSE';
            }
        }
        var field_region = psyemContactForm.find('select[name="field_region"]');
        if (!field_region.val()) {
            displayToaster('Region field is required', 'info');
            return 'FALSE';
        }
        var field_district = psyemContactForm.find('select[name="field_district"]');
        if (!field_district.val()) {
            displayToaster('District field is required', 'info');
            return 'FALSE';
        }
        var field_address = psyemContactForm.find('input[name="field_address"]');
        if (!field_address.val()) {
            displayToaster('Address field is required', 'info');
            return 'FALSE';
        }
        var field_source = psyemContactForm.find('select[name="field_source"]');
        if (!field_source.val()) {
            displayToaster('Contact source field is required', 'info');
            return 'FALSE';
        }
        return 'TRUE';
    };

    var psyemRunManageContactData = function () {
        const pformData = new FormData(psyemContactForm[0]);
        pformData.append('action', psyemSaveAction);
        pformData.append('field_form_key', psyemFormkey);
        pformData.append('field_form_type', 'Contact');
        pformData.append(csrf_token_name, csrf_token_value);

        jQuery.ajax({
            url: psyemAjaxUrl,
            type: 'POST',
            data: pformData,
            cache: false,
            mimeType: "multipart/form-data",
            dataType: 'json',
            processData: false,
            contentType: false,
            accept: { json: 'application/json' },
            beforeSend: function () {
                showHideButtonLoader(psyemContactBtn, 'Show');
            },
            success: function (result) {
                const { status, message, validation, data } = result;
                var record_id = (data && data.record_id) ? data.record_id : 0;
                var movenext = (data && data.movenext) ? data.movenext : '';

                if (status == 'success') {
                    displayToaster(message, status);
                    if (movenext == 'No' && record_id > 0) {
                        psyem_manageThankyouSection(record_id);
                        setTimeout(() => {
                            locationReload();
                        }, 15000);
                    }
                }
                if (status == 'error' && !validation) {
                    displayToaster(message, status);
                    showHideButtonLoader(psyemContactBtn, 'Hide');
                }
                if (status && status == 'error' && validation && validation.length > 0) {
                    displayToaster(validation[0], 'info');
                    showHideButtonLoader(psyemContactBtn, 'Hide');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                displayToaster(serverError, 'info');
                showHideButtonLoader(psyemContactBtn, 'Hide');
            }
        });
    };

    return {
        init: function () {
            psyemRunSetupData();
        }
    };

}();

jQuery(function () {
    PsyemProjectSafePanel.init();
});

(function ($) {
    var placeholderShown = function (e, self) {
        var self = $(self || this),
            shown = 'placeholder-shown',
            hasValue = !!self.val();
        self.toggleClass(shown, !hasValue);
    };

    var d = $(document);
    var ns = 'input[type=text],input[type=url],input[type=tel],input[type=number],input[type=file],input[type=email],textarea';

    $(document).ready(function () {
        $(ns).each(function (key, elem) {
            placeholderShown(null, elem);
            d.on('keyup', ns, placeholderShown);
        });
    });

})(jQuery);

(function ($) {
    $(document).ready(function () {
        $('.teal-form').find('select').selectize({
            create: false,
            load: function (query, callback) {
                this.clearOptions();
                this.renderCache = {};
            }
        });

        $('#psyemFirstStepForm').find('select').each(function () {
            psyemSelectActions($(this));
        });

        $('#psyemSecondStepForm').find('select').each(function () {
            psyemSelectActions($(this));
        });

        // Back button click
        $('#teal-form__step-2-back').on('click', function (e) {
            e.preventDefault();
            psyemSwitchToStep(1);
        });
    });

})(jQuery);

(function ($) {
    $(document).ready(function () {
        $('.form-item input').each(function () {
            if ($(this).val() != '') {
                $(this).parent().find('label').addClass('active');
            }
        });

        $('.form-item input').on('keyup', function () {
            if ($(this).val() == "") {
                $(this).parent().find('label').removeClass('active');
            } else {
                $(this).parent().find('label').addClass('active');
            }
        });

        $('.form-item input').on('focus', function () {
            if ($(this).val() == "") {
                $(this).parent().find('label').removeClass('active');
            } else {
                $(this).parent().find('label').addClass('active');
            }
        });
    });
})(jQuery);

function psyemSelectActions(select) {
    select[0].selectize.on('focus', function () {
        select.next('.selectize-control').addClass('focussed');
    });
    select[0].selectize.on('blur', function () {
        select.next('.selectize-control').removeClass('focussed');
    });
    select[0].selectize.on('change', function () {
        select.next('.selectize-control').addClass('changed');
    });
    select[0].selectize.on('click', function () {
        select.next('.selectize-control').removeClass('focussed');
        select.next('.selectize-control').addClass('focussed');
    });
}

function psyemSwitchToStep(step) {
    var content = jQuery('.teal-form__content[data-step="' + step + '"]');
    var li = jQuery('.teal-form__steps li[data-step="' + step + '"]');

    jQuery('.teal-form__content').not(content).removeClass('active');
    jQuery('.teal-form__steps li').not(li).removeClass('active');
    content.addClass('active');
    if (step > 1) {
        jQuery('.teal-form__steps').addClass('prev');
    } else {
        jQuery('.teal-form__steps').removeClass('prev');
    }
}

function psyem_isEligibleForRegistration(params = null) {

    var field_sexual_experience = params.field_sexual_experience; // yes
    var field_cervical_screening = params.field_cervical_screening; // no
    var field_undergoing_treatment = params.field_undergoing_treatment; // no
    var field_received_hpv = params.field_received_hpv; // no
    var field_pregnant = params.field_pregnant; // no
    var field_hysterectomy = params.field_hysterectomy; // no
    var field_gender = params.field_gender; // no
    var age_year = params.age_year; // > 25

    if (
        field_sexual_experience == 'yes' &&
        field_cervical_screening == 'no' &&
        field_undergoing_treatment == 'no' &&
        field_received_hpv == 'no' &&
        field_pregnant == 'no' &&
        field_hysterectomy == 'no' &&
        field_gender == 'female' &&
        age_year > 25
    ) {
        return 'Yes';
    }
    return 'No';

}

function psyem_manageDistrictField(regionOptionVal) {

    var selectizeDropdownContent = jQuery('.selectize-dropdown-content');
    if (selectizeDropdownContent && selectizeDropdownContent.length > 0) {
        selectizeDropdownContent.find('.hongkongisland').prop('disabled', true).hide();
        selectizeDropdownContent.find('.kowloon').prop('disabled', true).hide();
        selectizeDropdownContent.find('.newterritories').prop('disabled', true).hide();

        if (regionOptionVal && regionOptionVal.length > 0) {
            var regionValSlug = regionOptionVal.toLowerCase().replace(/\s+/g, '');

            if (regionValSlug && regionValSlug.length > 0) {
                if (regionValSlug == 'hongkongisland') {
                    selectizeDropdownContent.find('.hongkongisland').prop('disabled', false).show();
                }
                if (regionValSlug == 'kowloon') {
                    selectizeDropdownContent.find('.kowloon').prop('disabled', false).show();
                }
                if (regionValSlug == 'newterritories') {
                    selectizeDropdownContent.find('.newterritories').prop('disabled', false).show();
                }
            }
        }
    }
}

function psyem_manageThankyouSection(ref_numb) {
    var hideThankyouCont = jQuery('.hideThankyouCont');
    if (hideThankyouCont && hideThankyouCont.length > 0) {
        hideThankyouCont.hide();
    }
    var showThankyouCont = jQuery('.showThankyouCont');
    if (showThankyouCont && showThankyouCont.length > 0) {
        showThankyouCont.show();
    }
    var psyemPsReferenceNo = jQuery('.psyemPsReferenceNo');
    psyemPsReferenceNo.text('');
    if (ref_numb) {
        psyemPsReferenceNo.text(ref_numb);
    }
}


window.onload = function () {
    var psyemProjectSafeCont = document.querySelector('.psyemProjectSafeCont');
    if (psyemProjectSafeCont) {
        psyemProjectSafeCont.style.display = 'block';
    }
};
