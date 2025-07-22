
var page_params = (psyem_cart_ajax) ? (psyem_cart_ajax) : null;
var psyOrderAjaxUrl = (page_params && page_params.cart_ajaxurl) ? page_params.cart_ajaxurl : '';
var psyOrderPriceAction = (page_params && page_params.cart_price_action) ? page_params.cart_price_action : '';
var serverError = (page_params && page_params.server_error) ? page_params.server_error : '';
var csrf_token_name = '_nonce';
var csrf_token_value = (page_params && page_params.cart_nonce) ? page_params.cart_nonce : '';

jQuery(document).on('click', '.psyemCheckoutCartBtn', function () {
    var isCartValid = psyemValidateAddToCart();
    if (isCartValid == 'TRUE') {
        var psyemCartForm = jQuery('.psyemCartForm');
        psyemCalculateCartTicketPrice(psyemCartForm, true);
    }
});

jQuery(document).on('click', '.psyemPlusQtyBtn', function () {
    var ticketId = jQuery(this).attr('data-ticket');

    if (ticketId > 0) {
        var psyemTicketCont = jQuery('#ET' + ticketId);
        var plusIcon = psyemTicketCont.find('.dashicons-plus');

        var psyemCartItemInput = jQuery('input#psyemCartItemInput_' + ticketId);
        var psyemCartItemInputVal = psyemCartItemInput.val();
        psyemCartItemInputVal = parseInt((psyemCartItemInputVal > 0) ? psyemCartItemInputVal : 0);
        psyemCartItemInput.val(parseInt((psyemCartItemInputVal + 1)));
        var psyemCartItemInputVal = psyemCartItemInput.val();

        if (psyemCartItemInputVal > 0) {
            if (plusIcon && plusIcon) {
                plusIcon.removeClass('activePlus').addClass('activePlus');
            }
            var isCartValid = psyemValidateAddToCart();
            if (isCartValid == 'TRUE') {
                var psyemCartForm = jQuery('.psyemCartForm');
                psyemCalculateCartTicketPrice(psyemCartForm);
            }
        }
    }
});

jQuery(document).on('click', '.psyemMinusQtyBtn', function () {
    var ticketId = jQuery(this).attr('data-ticket');

    if (ticketId > 0) {
        var psyemTicketCont = jQuery('#ET' + ticketId);
        var plusIcon = psyemTicketCont.find('.dashicons-plus');

        var psyemCartItemInput = jQuery('input#psyemCartItemInput_' + ticketId);
        var psyemCartItemInputVal = psyemCartItemInput.val();
        psyemCartItemInputVal = parseInt((psyemCartItemInputVal));

        var updatedVal = 0;
        if ((psyemCartItemInputVal > 0)) {
            updatedVal = parseInt((psyemCartItemInputVal - 1));
        }

        psyemCartItemInput.val(updatedVal);
        var psyemCartItemInputVal = psyemCartItemInput.val();
        var isCartValid = psyemValidateAddToCart();
        if (isCartValid == 'TRUE') {
            var psyemCartForm = jQuery('.psyemCartForm');
            psyemCalculateCartTicketPrice(psyemCartForm);
            if (psyemCartItemInputVal == 0) {
                if (plusIcon && plusIcon) {
                    plusIcon.removeClass('activePlus');
                }
            }
        }
    }
});

jQuery(document).on('change', 'input.psyemCartItemInput', function () {
    var psyemCartItemInput = jQuery(this);
    var ticketId = psyemCartItemInput.attr('data-ticket');

    var isCartValid = psyemValidateAddToCart();
    if (isCartValid == 'TRUE') {
        var psyemCartForm = jQuery('.psyemCartForm');
        psyemCalculateCartTicketPrice(psyemCartForm);

        var psyemTicketCont = jQuery('#ET' + ticketId);
        var plusIcon = psyemTicketCont.find('.dashicons-plus');

        var psyemCartItemInputVal = psyemCartItemInput.val();

        if (psyemCartItemInputVal > 0) {
            if (plusIcon && plusIcon) {
                plusIcon.removeClass('activePlus').addClass('activePlus');
            }
        } else {
            if (plusIcon && plusIcon) {
                plusIcon.removeClass('activePlus');
            }
        }
    }
});

jQuery(document).on('keyup', 'input.psyemCartItemInput', function () {
    var psyemCartItemInput = jQuery(this);
    var ticketId = psyemCartItemInput.attr('data-ticket');

    var isCartValid = psyemValidateAddToCart();
    if (isCartValid == 'TRUE') {
        var psyemCartForm = jQuery('.psyemCartForm');
        psyemCalculateCartTicketPrice(psyemCartForm);

        var psyemTicketCont = jQuery('#ET' + ticketId);
        var plusIcon = psyemTicketCont.find('.dashicons-plus');

        var psyemCartItemInputVal = psyemCartItemInput.val();
        if (psyemCartItemInputVal > 0) {
            if (plusIcon && plusIcon) {
                plusIcon.removeClass('activePlus').addClass('activePlus');
            }
        } else {
            if (plusIcon && plusIcon) {
                plusIcon.removeClass('activePlus');
            }
        }
    }
});

jQuery(document).on('click', '.gotoTickets', function () {
    var psyemCartForm = jQuery('.psyemCartForm');
    if (psyemCartForm && psyemCartForm) {
        scrollDivToCenter(psyemCartForm);
    }
});


function psyemValidateAddToCart() {

    var itemQuantity = 0;
    var psyemCartForm = jQuery('.psyemCartForm');
    if (psyemCartForm && psyemCartForm.length > 0) {
        jQuery.each(psyemCartForm.find('.psyemCartItemInput'), function (index, item) {
            var psyemCartItemInputElm = jQuery(item);
            var psyemCartItemInputVal = psyemCartItemInputElm.val();
            if (psyemCartItemInputVal > 0) {
                itemQuantity = parseInt((itemQuantity + psyemCartItemInputVal));
            }
        });
    }

    if (itemQuantity > 0 || itemQuantity == 0) {
        return 'TRUE';
    } else {
        displayToaster('At least one ticket with participant is required to checkout', 'error');
    }
    return 'FALSE';
}

function psyemCalculateCartTicketPrice(psyemCartForm, redirectTo = false) {

    if (psyemCartForm && psyemCartForm.length > 0) {
        var cartFormData = new FormData(psyemCartForm[0]);
        cartFormData.append('checkout_coupon', '');
        cartFormData.append('checkout_key', psyemCheckoutEncKey);
        cartFormData.append('checkout_source', 'Details');
        cartFormData.append('action', psyOrderPriceAction);
        cartFormData.append(csrf_token_name, csrf_token_value);

        var psyemCartBtn = jQuery('.psyemCheckoutCartBtn');
        var psyemCartTotal = jQuery('.psyemCartTotal');
  
          jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: psyOrderAjaxUrl,
            data: cartFormData,
            processData: false,
            contentType: false,
            accept: { json: 'application/json' },
            beforeSend: function () {
                showHideButtonLoader(psyemCartBtn, 'Show');
            },
            success: function (resp) {
                var status = (resp.status) ? resp.status : null;
                var message = (resp.message) ? resp.message : null;
                var validation = (resp.validation) ? resp.validation : null;

                var rdata = (resp.data) ? resp.data : null;

                var ticketTotalPrice = (rdata.total_price) ? rdata.total_price : 0;
                var ticketCheckoutPrice = (rdata.checkout_price) ? parseFloat(rdata.checkout_price) : 0;
                var redirect_url = (rdata.redirect_to) ? rdata.redirect_to : null;

                psyemCartTotal.text((ticketTotalPrice));

                if (status == 'success' && ticketCheckoutPrice > 0 && redirectTo) {
                    if (redirect_url && redirect_url.length > 0) {
                        locationHref(redirect_url);
                    }
                }

                if (status == 'error' && !validation) {
                    displayToaster(message, status);
                }
                if (status && status == 'error' && validation && validation.length > 0) {
                    displayToaster(validation[0]);
                }

                showHideButtonLoader(psyemCartBtn, 'Hide');
            },
            error: function (errorInfo) {
                showHideButtonLoader(psyemCartBtn, 'Hide');
                displayToaster(serverError, 'error');
            }
        });
    }
}
