var page_params = (psyem_order_ajax) ? (psyem_order_ajax) : null;
var psyOrderAjaxUrl = (page_params && page_params.order_ajaxurl) ? page_params.order_ajaxurl : '';
var psyOrderIntentAction = (page_params && page_params.order_intent_action) ? page_params.order_intent_action : '';
var psyOrderSaveAction = (page_params && page_params.order_save_action) ? page_params.order_save_action : '';
var psyOrderPriceAction = (page_params && page_params.order_price_action) ? page_params.order_price_action : '';
var psyOrderFreeAction = (page_params && page_params.order_free_booking_action) ? page_params.order_free_booking_action : '';
var psyOrderSendTicketAction = (page_params && page_params.order_send_ticket_action) ? page_params.order_send_ticket_action : '';
var psyOrderThankyouUrl = (page_params && page_params.order_thankou_url) ? page_params.order_thankou_url : '';
var serverError = (page_params && page_params.server_error) ? page_params.server_error : '';

var stripePublicKey = (page_params && page_params.stripe_public_key) ? page_params.stripe_public_key : '';
var createPaymentIntentTokenURL = psyOrderAjaxUrl;
var paymentSuccessURL = psyOrderAjaxUrl;
var csrf_token_name = '_nonce';
var csrf_token_value = (page_params && page_params.order_nonce) ? page_params.order_nonce : '';

var stripeCustomerId = '';
var stripePlanId = '';
var stripeProductId = '';
var paymentIntentId = '';
let setupKeys = '';

/* INIT STRIPE PAYMENT VARS */
let formSubmitButton = jQuery(".formSubmitButton"); // unknown
let psyemContinueFreeBtn = jQuery("#psyemContinueFreeBtn");
let psyemContinuePaymentBtn = jQuery("#psyemContinuePaymentBtn");
let StripeSubmitBtn = jQuery("#submit");

let psyemTotalTicketPrice = jQuery("#psyemTotalTicketPrice");
let psyemTotalDiscountPrice = jQuery("#psyemTotalDiscountPrice");
let psyemTotalCheckoutPrice = jQuery("#psyemTotalCheckoutPrice");

let ticketElm = document.querySelector("input[name='psyem_tickets']");
let nameElm = document.querySelector("input[name='psyem_name']");
let emailElm = document.querySelector("input[name='psyem_email']");
let coupunElm = document.querySelector("input[name='psyem_coupon']");
let companyElm = document.querySelector("input[name='psyem_company']");

let clientSecretInfoEle = document.querySelector(".client_secret_info");
let emailAddress = emailElm.value;

const psyemFreeSection = jQuery("#psyemFreeSection");
const psyemPaymentSection = jQuery("#psyemPaymentSection");
const stripeSectionCont = jQuery("#psyemPaymentFormCont");
const stripeFormCont = document.querySelector(".stripeFormCont");
const stripeCreditForm = document.querySelector(".stripeCreditForm");
const stripeThanyouCont = document.querySelector(".stripeThanyouCont");

const stripe = Stripe(stripePublicKey);

/* INIT STRIPE PAYMENT FORM */

async function psyemInitializeIntent(fromsrc) {

    showPanelLoader(stripeSectionCont);

    var psyemCoupon = (coupunElm) ? coupunElm.value : '';
    var psyemName = (nameElm) ? nameElm.value : '';
    var psyemEmail = (emailElm) ? emailElm.value : '';
    var psyemCompany = (companyElm) ? companyElm.value : '';

    // Create a FormData object
    const formData = new FormData();
    formData.append('action', psyOrderIntentAction);
    formData.append('checkout_key', psyemCheckoutEncKey);
    formData.append('checkout_coupon', psyemCoupon);

    var bodyCont = jQuery('body');
    jQuery.each(bodyCont.find('.psyemCartItemInput'), function (index, item) {
        var psyemCartItemInputElm = jQuery(item);

        var psyemCartItemInputVal = psyemCartItemInputElm.val();
        psyemCartItemInputVal = (psyemCartItemInputVal > 0) ? psyemCartItemInputVal : 0;
        psyemCartItemInputVal = parseInt(psyemCartItemInputVal);

        var psyemCartItemInpuTciketID = psyemCartItemInputElm.attr('data-ticket');
        psyemCartItemInpuTciketID = (psyemCartItemInpuTciketID > 0) ? psyemCartItemInpuTciketID : 0;
        psyemCartItemInpuTciketID = parseInt(psyemCartItemInpuTciketID);

        if (psyemCartItemInputVal > 0 && psyemCartItemInpuTciketID > 0) {
            formData.append('checkout_tickets[' + psyemCartItemInpuTciketID + ']', psyemCartItemInputVal);
        }
    });


    formData.append('checkout_name', psyemName);
    formData.append('checkout_email', psyemEmail);
    formData.append('checkout_company', psyemCompany);
    formData.append(csrf_token_name, csrf_token_value);

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
                psyemSetLoading(false);
            }
        } else {
            psyemContinuePaymentBtn.prop('disabled', false);
            displayToaster(intentMessage, 'error');
        }
    } catch (error) {
        console.error('Fetch error:', error);
        displayToaster(serverError, 'error');
        psyemContinuePaymentBtn.prop('disabled', false);
    }

    hidePanelLoader(stripeSectionCont);

}

/* SUBMIT STRIPE CC FORM */
if (document.querySelector(".payment-form")) {
    document.querySelector(".payment-form").addEventListener("submit", handleStripeFormSubmit);
    async function handleStripeFormSubmit(e) {
        e.preventDefault();
        psyemSetLoading(true);
        showHideButtonLoader(formSubmitButton, 'Show');

        await stripe.confirmPayment({
            elements,
            confirmParams: {
                // return_url: stripeReturnUrl,
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
                    psyemShowMessage(serverError);
                    displayToaster(serverError, 'error');
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
            psyemShowMessage("Payment process failed." + serverError);
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
            psyemShowMessage("An unexpected error occurred. Please try again.");
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

    var psyemName = (nameElm) ? nameElm.value : '';
    var psyemEmail = (emailElm) ? emailElm.value : '';
    var psyemCompany = (companyElm) ? companyElm.value : '';

    // Create a FormData object
    const formData = new FormData();
    formData.append("intent_id", payment_intent_id);
    formData.append("stripe_status", stripe_status);
    formData.append('action', psyOrderSaveAction);
    formData.append('checkout_key', psyemCheckoutEncKey);
    formData.append('checkout_name', psyemName);
    formData.append('checkout_email', psyemEmail);
    formData.append('checkout_company', psyemCompany);
    formData.append(csrf_token_name, csrf_token_value);

    if (payment_intent_id && payment_intent_id.length > 0) {
        jQuery.ajax({
            url: psyOrderAjaxUrl,
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
                var order_id = (data && data.order_id) ? data.order_id : 0;
                var participant_id = (data && data.participant_id) ? data.participant_id : 0;
                var order_enc = (data && data.order_enc) ? data.order_enc : '';

                if (status == 'success') {
                    if (stripe_status == 'succeeded' && order_id > 0 && participant_id > 0) {
                        psyemSendOrderTickets(order_id, participant_id);
                    }
                    stripeFormCont.style.display = "none";
                    setTimeout(() => {
                        if (order_enc && order_enc.length > 0) {
                            psyemSetLoading(true);
                            var redirectTo = psyOrderThankyouUrl + '?checkkey=' + order_enc;
                            locationHref(redirectTo);
                        }
                    }, 6000);
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

// events
jQuery(document).on('click', '#psyemContinuePaymentBtn', function () {
    var psyemContinuePaymentBtn = jQuery(this);


    var psyemName = (nameElm) ? nameElm.value : '';
    var psyemEmail = (emailElm) ? emailElm.value : '';

    if (psyemName && psyemName.length > 0) {
        var isUname = validateUsername(psyemName);
        if (!isUname) {
            displayToaster('Please enter valid name, Only add alphabet characters [a-zA-Z]', 'error');
            return false;
        }
    } else {
        displayToaster('Please enter your name', 'error');
        return false;
    }
    if (psyemEmail && psyemEmail.length > 0) {
        if (!psyemIsValidEmail(psyemEmail)) {
            displayToaster('Please enter a valid email address', 'error');
            return false;
        }
    } else {
        displayToaster('Please enter your valid email', 'error');
        return false;
    }

    psyemContinuePaymentBtn.prop('disabled', true);
    setTimeout(() => {
        psyemInitializeIntent('initial');
    }, 20);
});

jQuery(document).on('click', '#psyemContinueFreeBtn', function () {
    var $Btn = jQuery(this);

    var psyemTickets = (ticketElm) ? ticketElm.value : 0;
    var psyemName = (nameElm) ? nameElm.value : '';
    var psyemEmail = (emailElm) ? emailElm.value : '';

    if (psyemTickets > 0) { } else {
        displayToaster('Please choose particpants count', 'error');
        return false;
    }
    if (psyemName && psyemName.length > 0) {
        var isUname = validateUsername(psyemName);
        if (!isUname) {
            displayToaster('Please enter valid name, Add only alphabets [a-zA-Z]', 'error');
            return false;
        }
    } else {
        displayToaster('Please enter your name', 'error');
        return false;
    }
    if (psyemEmail && psyemEmail.length > 0) {
        if (!psyemIsValidEmail(psyemEmail)) {
            displayToaster('Please enter a valid email address', 'error');
            return false;
        }
    } else {
        displayToaster('Please enter your valid email', 'error');
        return false;
    }

    psyemProcessFreeEventBooking();
});

jQuery(document).on('change', 'input[name="psyem_tickets"]', function () {
    var psyemTicketsCountInp = jQuery(this);
    var psyemTicketsCount = psyemTicketsCountInp.val();
    var psyemCouponCode = (coupunElm) ? coupunElm.value : '';

    if (psyemTicketsCount) {
        if (!psyem_ValidateInputNumber(psyemTicketsCount)) {
            displayToaster('Number of participants must be greater than 0', 'error');
        } else {
            psyemCalculateTicketPrice(psyemTicketsCount, psyemCouponCode);
        }
    } else {
        displayToaster('At least one participant is required to book the ticket', 'error');
    }
});

jQuery(document).on('keyup', 'input[name="psyem_tickets"]', function () {
    var psyemTicketsCountInp = jQuery(this);
    var psyemTicketsCount = psyemTicketsCountInp.val();
    var psyemCouponCode = (coupunElm) ? coupunElm.value : '';

    if (psyemTicketsCount) {
        if (!psyem_ValidateInputNumber(psyemTicketsCount)) {
            displayToaster('Number of participants must be greater than 0', 'error');
        } else {
            psyemCalculateTicketPrice(psyemTicketsCount, psyemCouponCode);
        }
    } else {
        displayToaster('At least one participant is required to book the ticket', 'error');
    }
});

jQuery(document).on('click', '.psyemApplyCouponCode', function () {
    var psyemApplyCouponBtn = jQuery(this);
    var psyemTicketsCount = (ticketElm) ? ticketElm.value : 0;
    var psyemCouponCode = (coupunElm) ? coupunElm.value : '';

    if (ticketElm) {
        if (psyemCouponCode && psyemCouponCode.length > 0) {
            if (psyemTicketsCount > 0) {
                psyemCalculateTicketPrice(psyemTicketsCount, psyemCouponCode);
            } else {
                displayToaster('At least one participant is required to book the ticket', 'error');
            }
        }
    } else {
        if (psyemCouponCode && psyemCouponCode.length > 0) {
            psyemCalculateTicketPrice(0, psyemCouponCode);
        }
    }
});

function psyemCalculateTicketPrice(checkout_tickets, psyemCouponCode) {

    var csvFormData = new FormData();
    csvFormData.append('checkout_coupon', psyemCouponCode);
    csvFormData.append('checkout_key', psyemCheckoutEncKey);
    csvFormData.append('checkout_source', 'Checkout');
    csvFormData.append('action', psyOrderPriceAction);
    csvFormData.append(csrf_token_name, csrf_token_value);

    if (checkout_tickets > 0) {
        csvFormData.append('checkout_tickets[0]', checkout_tickets);
    } else {
        var bodyCont = jQuery('body');
        jQuery.each(bodyCont.find('.psyemCartItemInput'), function (index, item) {
            var psyemCartItemInputElm = jQuery(item);

            var psyemCartItemInputVal = psyemCartItemInputElm.val();
            psyemCartItemInputVal = (psyemCartItemInputVal > 0) ? psyemCartItemInputVal : 0;
            psyemCartItemInputVal = parseInt(psyemCartItemInputVal);

            var psyemCartItemInpuTciketID = psyemCartItemInputElm.attr('data-ticket');
            psyemCartItemInpuTciketID = (psyemCartItemInpuTciketID > 0) ? psyemCartItemInpuTciketID : 0;
            psyemCartItemInpuTciketID = parseInt(psyemCartItemInpuTciketID);

            if (psyemCartItemInpuTciketID > 0) {
                csvFormData.append('checkout_tickets[' + psyemCartItemInpuTciketID + ']', psyemCartItemInputVal);
            }
        });
    }

    jQuery.ajax({
        type: "POST",
        dataType: "JSON",
        url: psyOrderAjaxUrl,
        data: csvFormData,
        processData: false,
        contentType: false,
        accept: { json: 'application/json' },
        beforeSend: function () {
            showPanelLoader(psyemPaymentSection);
            stripeSectionCont.hide('fast');
            showHideButtonLoader(psyemContinuePaymentBtn, 'Show');
            psyemSetLoading(true);
        },
        success: function (resp) {
            var status = (resp.status) ? resp.status : null;
            var message = (resp.message) ? resp.message : null;
            var validation = (resp.validation) ? resp.validation : null;
            var rdata = (resp.data) ? resp.data : null;

            var ticketTotalPrice = (rdata.total_price) ? rdata.total_price : 0;
            var ticketDiscountPrice = (rdata.discount_price) ? rdata.discount_price : 0;
            var ticketCheckoutPrice = (rdata.checkout_price) ? rdata.checkout_price : 0;
            var ticketCurrenySign = (rdata.curreny_sign) ? rdata.curreny_sign : '$';
            var ticketCouponMessage = (rdata.coupon_message) ? rdata.coupon_message : '';

            if (status == 'success') {
                showHideButtonLoader(psyemContinuePaymentBtn, 'Hide');
                psyemSetLoading(false);

                psyemTotalTicketPrice.text((ticketCurrenySign + "" + ticketTotalPrice));
                psyemTotalDiscountPrice.text((ticketCurrenySign + "" + ticketDiscountPrice));
                psyemTotalCheckoutPrice.text((ticketCurrenySign + "" + ticketCheckoutPrice));

                psyemContinuePaymentBtn.prop('disabled', false);
                hidePanelLoader(psyemPaymentSection);
            }
            if (status == 'error' && !validation) {
                displayToaster(message, status);
            }
            if (status && status == 'error' && validation && validation.length > 0) {
                displayToaster(validation[0]);
            } else {
                if (ticketCouponMessage && ticketCouponMessage.length > 0) {
                    displayToaster(ticketCouponMessage);
                }
            }

            showHideButtonLoader(psyemContinuePaymentBtn, 'Hide');
            psyemSetLoading(false);
            setTimeout(() => {
                hidePanelLoader(psyemPaymentSection);
            }, 1000);
        },
        error: function (errorInfo) {
            showHideButtonLoader(psyemContinuePaymentBtn, 'Hide');
            psyemSetLoading(false);
            displayToaster(serverError, 'error');
            setTimeout(() => {
                hidePanelLoader(psyemPaymentSection);
            }, 1000);
        }
    });
}

function psyemIsValidEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email);
}

function psyemProcessFreeEventBooking() {

    var psyemTickets = (ticketElm) ? ticketElm.value : 0;
    var psyemName = (nameElm) ? nameElm.value : '';
    var psyemEmail = (emailElm) ? emailElm.value : '';
    var psyemCompany = (companyElm) ? companyElm.value : '';

    // Create a FormData object
    const formData = new FormData();
    formData.append('action', psyOrderFreeAction);
    formData.append('checkout_key', psyemCheckoutEncKey);
    formData.append('checkout_tickets', psyemTickets);
    formData.append('checkout_name', psyemName);
    formData.append('checkout_email', psyemEmail);
    formData.append('checkout_company', psyemCompany);
    formData.append(csrf_token_name, csrf_token_value);

    jQuery.ajax({
        type: "POST",
        dataType: "JSON",
        url: psyOrderAjaxUrl,
        data: formData,
        processData: false,
        contentType: false,
        accept: { json: 'application/json' },
        beforeSend: function () {
            showPanelLoader(psyemFreeSection);
            showHideButtonLoader(psyemContinueFreeBtn, 'Show');
        },
        success: function (result) {
            const { status, message, validation, data } = result;
            var order_id = (data && data.order_id) ? data.order_id : 0;
            var participant_id = (data && data.participant_id) ? data.participant_id : 0;
            var order_enc = (data && data.order_enc) ? data.order_enc : '';

            if (status == 'success') {
                if (order_id > 0 && participant_id > 0) {
                    psyemSendOrderTickets(order_id, participant_id);
                }
                setTimeout(() => {
                    if (order_enc && order_enc.length > 0) {
                        var redirectTo = psyOrderThankyouUrl + '?checkkey=' + order_enc;
                        locationHref(redirectTo);
                    }
                }, 6000);
            }
            if (status == 'error') {
                showHideButtonLoader(psyemContinueFreeBtn, 'Hide');
                setTimeout(() => {
                    hidePanelLoader(psyemFreeSection);
                }, 2000);
            }
            setTimeout(() => {
                hidePanelLoader(psyemFreeSection);
            }, 7000);
            displayToaster(message, status);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            displayToaster('Payment info has been failed to update', 'error');
            setTimeout(() => {
                hidePanelLoader(psyemPaymentSection);
            }, 2000);
        }
    });
}

function psyemSendOrderTickets(order, participant_id) {

    if (order > 0 && participant_id) {
        var ticketFormData = new FormData();
        ticketFormData.append('order_id', order);
        ticketFormData.append('participants[]', participant_id);
        ticketFormData.append('action', psyOrderSendTicketAction);
        ticketFormData.append(csrf_token_name, csrf_token_value);
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: psyOrderAjaxUrl,
            data: ticketFormData,
            processData: false,
            contentType: false,
            accept: { json: 'application/json' },
            beforeSend: function () { },
            success: function (resp) {
                var status = (resp.status) ? resp.status : null;
                var message = (resp.message) ? resp.message : null;
                var validation = (resp.validation) ? resp.validation : null;
                if (status == 'success') { }
                if (status == 'error' && !validation) { }
                if (status && status == 'error' && validation && validation.length > 0) { }
            },
            error: function (errorInfo) { }
        });
    }
}

window.onload = function () {
    var psyemEventsCheckoutCont = document.querySelector('.psyemEventsCheckoutCont');
    if (psyemEventsCheckoutCont) {
        psyemEventsCheckoutCont.style.display = 'block';
    }
};
