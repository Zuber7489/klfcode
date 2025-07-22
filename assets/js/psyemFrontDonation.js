var psyem_donation = (psyem_donation) ? (psyem_donation) : null;
var psyem_ajaxUrl = (psyem_donation && psyem_donation.donation_ajaxurl) ? psyem_donation.donation_ajaxurl : null;
var psyem_nonce = (psyem_donation && psyem_donation.donation_nonce) ? psyem_donation.donation_nonce : null;
var psyem_pAction = (psyem_donation && psyem_donation.donation_amount_action) ? psyem_donation.donation_amount_action : null;
var psyem_aAction = (psyem_donation && psyem_donation.donation_process_action) ? psyem_donation.donation_process_action : null;
var psyem_intent_action = (psyem_donation && psyem_donation.donation_intent_action) ? psyem_donation.donation_intent_action : null;
var psyem_payment_action = (psyem_donation && psyem_donation.donation_payment_action) ? psyem_donation.donation_payment_action : null;
var psyem_serverError = (psyem_donation && psyem_donation.server_error) ? psyem_donation.server_error : '';

let psyemDonationModal = jQuery('#psyemDonationModal');
let psyemDonationAmountsCont = jQuery('#psyemDonationAmountsCont');
let psyemDonationSection = jQuery('.psyemDonationSection');
let psyemCustomFieldGroup = jQuery('.custom-donation-fieldgroup');

var psyemDonationPanel = function () {

    var psyemRunSetupPanelData = function () {
        jQuery(document).on('click', '.psyemMonthlyDonationElm', function (ev) {
            var $btn = jQuery(this);
            psyemRunGetDonationAmountsHtml('Monthly', $btn);
        });

        jQuery(document).on('click', '.psyemOnetimeDonationElm', function (ev) {
            var $btn = jQuery(this);
            psyemRunGetDonationAmountsHtml('Onetime', $btn);
        });

        jQuery('#psyemDonationModal').on('shown.bs.modal', function (ev) {
            psyemDonationModal.removeClass('in').addClass('in');
            psyemDonationModal.removeClass('show').addClass('show');
        });

        jQuery('#psyemDonationModal').on('hidden.bs.modal', function (ev) {
            psyemDonationModal.removeClass('in');
            psyemDonationModal.removeClass('show');
        });

        jQuery(document).on('focus', 'input[name="customdonationamt"]', function (ev) {
            jQuery('body').find('.custom-donation-fieldgroup').removeClass('active').addClass('active');
            jQuery('body').find('.custom-donation-fieldgroup').find('.donation_currency_label').show('fast');
        });

        jQuery(document).on('focusout', 'input[name="customdonationamt"]', function (ev) {
            var cInput = jQuery(this);
            var cInputVal = cInput.val();
            if (cInputVal > 0) {
                jQuery('body').find('.custom-donation-fieldgroup').removeClass('active').addClass('active');
                jQuery('body').find('.custom-donation-fieldgroup').find('.donation_currency_label').show('fast');
            } else {
                jQuery('body').find('.custom-donation-fieldgroup').removeClass('active');
                jQuery('body').find('.custom-donation-fieldgroup').find('.donation_currency_label').hide('fast');
            }
        });

        jQuery(document).on('keydown', 'input[name="customdonationamt"]', function (ev) {
            var cInput = jQuery(this);
            var cInputVal = cInput.val();
            if (cInputVal > 0) {
                jQuery('body').find('.custom-donation-fieldgroup').removeClass('active').addClass('active');
                jQuery('body').find('.custom-donation-fieldgroup').find('.donation_currency_label').show('fast');
            } else {
                jQuery('body').find('.custom-donation-fieldgroup').removeClass('active');
                jQuery('body').find('.custom-donation-fieldgroup').find('.donation_currency_label').hide('fast');
            }
        });

        jQuery('body').on('click', '.donation-amount', function (ev) {
            var cInput = jQuery(this);
            jQuery('body').find('.donation-amount-select').find('.donation-amount').removeClass('selected');
            if (cInput.hasClass('placeholder-shown')) {
            } else {
                jQuery('body').find('.donation-amount-select').find('.donation-amount').val('').trigger('keydown');
            }
            cInput.removeClass('selected').addClass('selected');
        });

        jQuery('body').on('click', '.submit-donation', function (ev) {
            var sBtn = jQuery(this);
            var amountFor = 'Check';
            var amountEnc = '123';
            var amount = 10;

            var psyemCartForm = jQuery('body').find('.donation-amount-select');
            jQuery.each(psyemCartForm.find('.donation-amount'), function (index, item) {
                var psyemCartItemInputElm = jQuery(item);
                if (psyemCartItemInputElm.hasClass('selected')) {
                    amountEnc = psyemCartItemInputElm.attr('data-amountenc');
                    amountFor = psyemCartItemInputElm.attr('data-amountfor');
                    if (amountEnc == 'Custom') {
                        amount = psyemCartItemInputElm.val();
                    }
                    return false;
                }
            });


            if (amountEnc && amountEnc.length > 0 && amountFor && amountFor.length > 0) {
                if (amountEnc == 'Custom') {
                    if (amount > 0) {
                        psyemRunProcessSelectedDonationAmount(amountEnc, amountFor, amount);
                    } else {
                        displayToaster('Please enter some amount to process', 'error');
                        return false;
                    }
                } else {
                    psyemRunProcessSelectedDonationAmount(amountEnc, amountFor, amount);
                }
            }
        });
    };

    var psyemRunGetDonationAmountsHtml = function (prices_type, elm) {

        const formData = new FormData();
        var mtitle = elm.attr('data-donation-title');
        mtitle = (mtitle && mtitle != 'undefined') ? mtitle : '';

        formData.append("amount_type", prices_type);
        formData.append("amount_for", prices_type);
        formData.append('action', psyem_pAction);
        formData.append('_nonce', psyem_nonce);
        formData.append('modal_title', mtitle);
        
        jQuery.ajax({
            url: psyem_ajaxUrl,
            type: 'POST',
            data: formData,
            cache: false,
            mimeType: "multipart/form-data",
            dataType: 'json',
            processData: false,
            contentType: false,
            accept: { json: 'application/json' },
            beforeSend: function () {
                showPanelLoader(psyemDonationSection);
            },
            success: function (result) {
                const { status, message, phtml } = result;
                if (status == 'success') {
                    if (phtml && phtml.length > 0) {
                        psyemDonationAmountsCont.html(phtml);
                        showCommonModal(psyemDonationModal);
                        // hideCommonModal(psyemDonationModal);
                    }
                }
                if (status == 'error') {
                    displayToaster(message, status);
                }

                setTimeout(() => {
                    hidePanelLoader(psyemDonationSection);
                }, 2000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                displayToaster(psyem_serverError, 'error');
                setTimeout(() => {
                    hidePanelLoader(psyemDonationSection);
                }, 2000);
            }
        });
    };


    var psyemRunProcessSelectedDonationAmount = function (amountEnc, amountFor, amount) {
        const formData = new FormData();
        formData.append("amount_enc", amountEnc);
        formData.append("amount_for", amountFor);
        formData.append("amount", amount);
        formData.append('action', psyem_aAction);
        formData.append('_nonce', psyem_nonce);

        jQuery.ajax({
            url: psyem_ajaxUrl,
            type: 'POST',
            data: formData,
            cache: false,
            mimeType: "multipart/form-data",
            dataType: 'json',
            processData: false,
            contentType: false,
            accept: { json: 'application/json' },
            beforeSend: function () {
                showPanelLoader(psyemDonationSection);
            },
            success: function (result) {
                const { status, message, validation, redirectto } = result;
                if (status == 'success') {
                    locationHref(redirectto);
                }

                if (status == 'error') {
                    displayToaster(message, status);
                }
                if (status && status == 'error' && validation && validation.length > 0) {
                    displayToaster(validation[0]);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                displayToaster(psyem_serverError, 'error');
            }
        });
    };

    return {
        init: function () {
            psyemRunSetupPanelData();
        }
    };
}();

jQuery(function () {
    psyemDonationPanel.init();
});


var stripePublicKey = (psyem_donation && psyem_donation.stripe_public_key) ? psyem_donation.stripe_public_key : '';
var createPaymentIntentTokenURL = psyem_ajaxUrl;
var paymentSuccessURL = psyem_ajaxUrl;
let psyemContinuePaymentBtn = jQuery('#psyemContinuePaymentBtn');
let psyemConfirmPaymentBtn = jQuery('#psyemConfirmPaymentBtn');
let psyemStripeCont = jQuery('#psyemStripeCont');
let psyemDonationCheckoutForm = jQuery('#psyemDonationCheckoutForm');
var errorTimeo = null;

const psyemPaymentSection = jQuery("#psyemPaymentSection");
const stripeSectionCont = jQuery("#psyemPaymentFormCont");
const stripeFormCont = document.querySelector(".stripeFormCont");
const stripeCreditForm = document.querySelector(".stripeCreditForm");
const stripeThanyouCont = document.querySelector(".stripeThanyouCont");
let clientSecretInfoEle = document.querySelector(".client_secret_info");
let StripeSubmitBtn = jQuery("#submit");
let formSubmitButton = jQuery(".formSubmitButton"); // unknown

const stripe = Stripe(stripePublicKey);
/* INIT STRIPE PAYMENT FORM */
async function psyemInitializeIntent(fromsrc) {

    showPanelLoader(stripeSectionCont);
    showHideButtonLoader(psyemContinuePaymentBtn, 'Show');

    // Create a FormData object
    const formData = new FormData(psyemDonationCheckoutForm[0]);
    formData.append('action', psyem_intent_action);
    formData.append('_nonce', psyem_nonce);

    try {
        const response = await fetch(createPaymentIntentTokenURL, {
            method: "POST",
            body: formData, // Send the FormData object
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Error:', response.status, errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const intentInfo = await response.json();
        var intentStatus = (intentInfo && intentInfo.status) ? intentInfo.status : '';
        var intentMessage = (intentInfo && intentInfo.message) ? intentInfo.message : '';

        if (intentStatus == 'success') {
            const intentData = (intentInfo && intentInfo.data) ? intentInfo.data : '';
            const clientSecret = (intentData && intentData.clientSecret) ? intentData.clientSecret : '';
            const paymentIntentId = (intentData && intentData.PaymentIntentId) ? intentData.PaymentIntentId : '';
            if (clientSecret && clientSecret.length > 0) {
                clientSecretInfoEle.value = clientSecret;
                const elementOptions = {
                    clientSecret: clientSecret
                };

                elements = stripe.elements(elementOptions);
                const paymentElementOptions = {
                    layout: {
                        type: 'tabs',
                        defaultCollapsed: true,
                    },
                    captureMethod: 'manual',   // This line is important.
                };
                const paymentElement = elements.create("payment", paymentElementOptions);
                paymentElement.mount("#payment-element");
                stripeSectionCont.show('fast');
                psyemContinuePaymentBtn.hide('fast');
                psyemSetLoading(false);
                showHideButtonLoader(psyemContinuePaymentBtn, 'Hide');
            }
        } else {
            psyemContinuePaymentBtn.prop('disabled', false);
            displayToaster(intentMessage, 'error');
        }
    } catch (error) {
        console.error('Fetch error:', error);
        displayToaster(psyem_serverError, 'error');
        psyemContinuePaymentBtn.prop('disabled', false);
    }

    psyemSetLoading(false);
    hidePanelLoader(stripeSectionCont);
    showHideButtonLoader(psyemContinuePaymentBtn, 'Hide');

}

/* SUBMIT STRIPE CC FORM */
if (document.querySelector(".payment-form")) {
    document.querySelector(".payment-form").addEventListener("submit", handleStripeFormSubmit);
    async function handleStripeFormSubmit(e) {
        e.preventDefault();
        psyemSetLoading(true);
        showHideButtonLoader(formSubmitButton, 'Show');

        const psyemDCheckoutForm = new FormData(psyemDonationCheckoutForm[0]);
        var emailAddress = psyemDCheckoutForm.get('email');
        await stripe.confirmPayment({
            elements,
            confirmParams: {
                receipt_email: emailAddress,
            },
            redirect: "if_required"
        }).then(function (stripeResponse) {
            if (typeof stripeResponse === 'object' && stripeResponse !== null) {
                var objectKeys = Object.keys(stripeResponse);
                var responseKey = (objectKeys && objectKeys.length > 0) ? objectKeys[0] : '';
                // Handle response casess
                if (responseKey == 'paymentIntent') {
                    psyemHandleStripeSuccessResponse(stripeResponse);
                } else if (responseKey == 'error') {
                    psyemHandleStripeErrorResponse(stripeResponse);
                } else {
                    psyemShowMessage(psyem_serverError);
                    displayToaster(psyem_serverError, 'error');
                }
            }
        });

        psyemSetLoading(false);
        showHideButtonLoader(formSubmitButton, 'Hide');
    }
}

/* SHOW /HIDE STRIPE PAY FORM BUTTON LOADER */
function psyemSetLoading(isLoading) {

    var spinnerL = document.querySelector("#spinner");
    var buttonTxt = document.querySelector("#button-text");

    if (document.querySelector("#submit") && spinnerL) {
        if (isLoading) {
            // Disable the button and show a spinner
            document.querySelector("#submit").disabled = true;
            if (spinnerL) {
                spinnerL.classList.remove("d-none");
            }
            if (buttonTxt) {
                buttonTxt.classList.add("d-none");
            }
            if (StripeSubmitBtn && StripeSubmitBtn.length > 0) {
                showHideButtonLoader(StripeSubmitBtn, 'Show');
            }
        } else {
            document.querySelector("#submit").disabled = false;
            if (spinnerL) {
                spinnerL.classList.add("d-none");
            }
            if (buttonTxt) {
                buttonTxt.classList.remove("d-none");
            }
            if (StripeSubmitBtn && StripeSubmitBtn.length > 0) {
                showHideButtonLoader(StripeSubmitBtn, 'Hide');
            }
        }
    }
}

/* STRIPE SUCCESS */
async function psyemHandleStripeSuccessResponse(stripeResponse) {
    var sResponse = (stripeResponse.paymentIntent) ? stripeResponse.paymentIntent : null;
    const paymentIntentid = (sResponse && sResponse.id && sResponse.id.length > 0) ? sResponse.id : '';
    const clientSecret = (sResponse && sResponse.client_secret && sResponse.client_secret.length > 0) ? sResponse.client_secret : '';
    const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);
    showPanelLoader(psyemPaymentSection);
    switch (paymentIntent.status) {
        case "succeeded":
            psyemUpdatePaymentIntentResponseInDb(paymentIntentid, 'succeeded');
            showHideButtonLoader(psyemContinuePaymentBtn, 'Show');
            psyemSetLoading(true);
            break;
        case "processing":
            psyemUpdatePaymentIntentResponseInDb(paymentIntentid, 'processing');
            showHideButtonLoader(psyemContinuePaymentBtn, 'Show');
            psyemSetLoading(true);
            break;
        case "requires_payment_method":
            psyemSetLoading(false);
            showHideButtonLoader(formSubmitButton, 'Hide');
            psyemInitializeIntent('requires_payment_method');
            psyemShowMessage("Your payment process was not successfull, and requires_payment_method, please try again.");
            break;
        case "requires_action":
            psyemSetLoading(false);
            showHideButtonLoader(formSubmitButton, 'Hide');
            psyemInitializeIntent('requires_action');
            psyemShowMessage("Your payment process was not successful, and requires_action, please try again.");
            break;
        default:
            psyemSetLoading(false);
            showHideButtonLoader(formSubmitButton, 'Hide');
            psyemInitializeIntent('default');
            psyemShowMessage("Payment process failed." + psyem_serverError);
            break;
    }
    setTimeout(() => {
        hidePanelLoader(psyemPaymentSection);
    }, 6000);
}

/* STRIPE ERROR */
function psyemHandleStripeErrorResponse(stripeResponse) {
    var eResponse = (stripeResponse.error) ? stripeResponse.error : null;
    if (eResponse.type && eResponse.type.length > 0) {
        if (eResponse.type === "card_error" || eResponse.type === "validation_error" || eResponse.type === 'invalid_request_error') {
            psyemShowMessage(eResponse.message);
        } else {
            psyemShowMessage(psyem_serverError);
        }
    }
    psyemSetLoading(false);
    showHideButtonLoader(formSubmitButton, 'Hide');
}

function psyemShowMessage(messageText) {
    const messageContainer = document.querySelector("#payment-message");

    messageContainer.classList.remove("d-none");
    messageContainer.textContent = messageText;

    setTimeout(function () {
        messageContainer.classList.add("d-none");
        messageText.textContent = "";
    }, 8000);
}

function psyemUpdatePaymentIntentResponseInDb(payment_intent_id, stripe_status) {

    // Create a FormData object
    const formData = new FormData();
    formData.append("intent_id", payment_intent_id);
    formData.append("stripe_status", stripe_status);
    formData.append('action', psyem_payment_action);
    formData.append('_nonce', psyem_nonce);

    if (payment_intent_id && payment_intent_id.length > 0) {
        jQuery.ajax({
            url: psyem_ajaxUrl,
            type: 'POST',
            data: formData,
            cache: false,
            mimeType: "multipart/form-data",
            dataType: 'json',
            processData: false,
            contentType: false,
            accept: { json: 'application/json' },
            beforeSend: function () {
                showPanelLoader(psyemPaymentSection);
                psyemSetLoading(true);
            },
            success: function (result) {
                const { status, message, validation, data } = result;
                var donation_id = (data && data.donation_id) ? data.donation_id : 0;
                var donation_enc = (data && data.donation_enc) ? data.donation_enc : '';

                if (status == 'success') {
                    if (stripe_status == 'succeeded' && donation_id > 0) {
                        psyemSendDonationEmail(donation_id);
                    }
                    stripeFormCont.style.display = "none";
                    if (donation_enc && donation_enc.length > 0) {
                        psyemSetLoading(true);
                        psyem_manageDonationThankyouSection(donation_enc);
                    }
                }
                if (status == 'error') {
                    psyemSetLoading(false);
                }

                setTimeout(() => {
                    hidePanelLoader(psyemPaymentSection);
                }, 6000);
                displayToaster(message, status);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                psyemSetLoading(false);
                displayToaster('Payment info has been failed to update', 'error');
                setTimeout(() => {
                    hidePanelLoader(psyemPaymentSection);
                }, 2000);
            }
        });
    }
}

var psyemDonationCheckoutPanel = function () {

    var psyemRunSetupCheckoutPanelData = function () {

        jQuery(document).on('click', '.newsletterChk', function () {
            var chkInp = jQuery(this);
            if (chkInp.hasClass('agree')) {
                chkInp.removeClass('agree');
                jQuery('.newsletter_agree').val('NotAgreed');
            } else {
                chkInp.removeClass('agree').addClass('agree');
                jQuery('.newsletter_agree').val('Agreed');
            }
        });

        jQuery(document).on('click', '.tandcCheck', function () {
            var chkInp = jQuery(this);
            if (chkInp.hasClass('agree')) {
                chkInp.removeClass('agree');
                jQuery('.tandc_agree').val('NotAgreed');
            } else {
                chkInp.removeClass('agree').addClass('agree');
                jQuery('.tandc_agree').val('Agreed');
            }
        });

        jQuery(document).on('click', '#psyemContinuePaymentBtn', function () {
            var isValid = psyemRunValidateCheckoutFormData();

            if (isValid == 'TRUE') {
                psyemInitializeIntent('initial');
            }
        });
    };

    var psyemRunValidateCheckoutFormData = function () {

        var errorCount = 0;
        jQuery('.field-error').text('');

        const psyemDCheckoutForm = new FormData(psyemDonationCheckoutForm[0]);
        // Additional details
        var first_name = psyemDCheckoutForm.get('first_name');
        if (!first_name) {
            jQuery('.error_first_name').text('First name field is required');
            errorCount = parseInt((errorCount + 1));
        } else {
            var isValidName = validateUsername(first_name);
            if (!isValidName) {
                jQuery('.error_first_name').text('First name is invalid, Add only alphabets [a-zA-Z]');
                errorCount = parseInt((errorCount + 1));
            }
        }

        var last_name = psyemDCheckoutForm.get('last_name');
        if (!last_name) {
            jQuery('.error_last_name').text('Last name field is required');
            errorCount = parseInt((errorCount + 1));
        } else {
            var isValidName = validateUsername(last_name);
            if (!isValidName) {
                jQuery('.error_last_name').text('Last name is invalid, Add only alphabets [a-zA-Z]');
                errorCount = parseInt((errorCount + 1));
            }
        }

        var email = psyemDCheckoutForm.get('email');
        if (!email) {
            jQuery('.error_email').text('Email field is required');
            errorCount = parseInt((errorCount + 1));
        } else {
            var isValidEmail = validateEmailAddress(email);
            if (!isValidEmail) {
                jQuery('.error_email').text('Email is not valid, Please enter valid email address.');
                errorCount = parseInt((errorCount + 1));
            }
        }

        var phone = psyemDCheckoutForm.get('phone');
        if (!phone) {
            jQuery('.error_phone').text('Phone field is required');
            errorCount = parseInt((errorCount + 1));
        } else {
            var isValidPhone = validatePhoneNumber(phone);
            if (!isValidPhone) {
                //jQuery('.error_phone').text('Phone field input is invalid, must be a 10 digits valid phone number.');
                // errorCount = parseInt((errorCount + 1));
            }
        }

        var company = psyemDCheckoutForm.get('company');
        if (!company) {
            jQuery('.error_company').text('Company field is required');
            errorCount = parseInt((errorCount + 1));
        } else {
            var isValidName = validateUsername(company);
            if (!isValidName) {
                jQuery('.error_company').text('Company is invalid, Add only alphabets [a-zA-Z]');
                errorCount = parseInt((errorCount + 1));
            }
        }

        // billing details
        var billing_country = psyemDCheckoutForm.get('billing_country');
        if (!billing_country) {
            jQuery('.error_billing_country').text('Country field is required');
            errorCount = parseInt((errorCount + 1));
        } else {
            var isValidName = validateUsername(billing_country);
            if (!isValidName) {
                jQuery('.error_billing_country').text('Country is invalid, Add only alphabets [a-zA-Z]');
                errorCount = parseInt((errorCount + 1));
            }
        }

        var billing_address = psyemDCheckoutForm.get('billing_address');
        if (!billing_address) {
            jQuery('.error_billing_address').text('Address field is required');
            errorCount = parseInt((errorCount + 1));
        }

        var billing_city = psyemDCheckoutForm.get('billing_city');
        if (!billing_city) {
            jQuery('.error_billing_city').text('City field is required');
            errorCount = parseInt((errorCount + 1));
        } else {
            var isValidName = validateUsername(billing_city);
            if (!isValidName) {
                jQuery('.error_billing_city').text('City is invalid, Add only alphabets [a-zA-Z]');
                errorCount = parseInt((errorCount + 1));
            }
        }

        var billing_district = psyemDCheckoutForm.get('billing_district');
        if (!billing_district) {
            jQuery('.error_billing_district').text('District field is required');
            errorCount = parseInt((errorCount + 1));
        } else {
            var isValidName = validateUsername(billing_district);
            if (!isValidName) {
                jQuery('.error_billing_district').text('District is invalid, Add only alphabets [a-zA-Z]');
                errorCount = parseInt((errorCount + 1));
            }
        }

        if (errorTimeo) {
            clearTimeout(errorTimeo);
        }

        errorTimeo = setTimeout(() => {
            jQuery('.field-error').text('');
        }, 8000);

        if (errorCount > 0) {
            return 'FALSE';
        }
        return 'TRUE';
    };

    return {
        init: function () {
            psyemRunSetupCheckoutPanelData();
        }
    };
}();

jQuery(function () {
    psyemDonationCheckoutPanel.init();
});


function psyem_manageDonationThankyouSection(ref_numb) {
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

function psyemSendDonationEmail(donation_id) {

}

window.onload = function () {
    var psyemDonationCont = document.querySelector('.psyemDonationCont');
    if (psyemDonationCont) {
        psyemDonationCont.style.display = 'block';
    }
    var psyemDonationCheckoutc = document.querySelector('.psyemDonationCheckout');
    if (psyemDonationCheckoutc) {
        psyemDonationCheckoutc.style.display = 'block';
    }
};

