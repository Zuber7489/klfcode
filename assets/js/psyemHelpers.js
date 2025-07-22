var jQuery = jQuery.noConflict();
function select2Single() {
    try {
        var select2Single = jQuery('.select2Single');
        if (select2Single && select2Single.length > 0) {
            let jQselect2FunctionName = "select2";
            if (typeof jQuery.fn[jQselect2FunctionName] === "function") {
                select2Single.select2({ allowClear: true, placeholder: "Select Option" });
            }
        }
    } catch (error) {
        console.error('select2Single:  select2 is not loaded or an error occurred:', error);
    }
}

function prd(data, prefix = 'TEST') {
    console.log(prefix, data);
}

jQuery(function () {
    jQuery(document).on("keypress keyup blur change", '.strict_numeric', function (event) {
        var t_val = jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || jQuery(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    jQuery(document).on("keypress keyup blur change", '.strict_integer', function (event) {
        jQuery(this).val(jQuery(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    jQuery(document).on("keypress keyup blur change", '.strict_decimal', function (event) {
        var el = jQuery(this);
        var ex = /^\d*(\.\d{0,2})?$/;
        if (ex.test(el.val()) == false) {
            var elval = (parseInt(el.val() * 100) / 100);
            if (isNaN(elval)) {
                el.val(0);
            } else {
                el.val(elval);
            }
        }
    });

    jQuery(document).on("keypress keyup blur change", '.strict_month_year', function (event) {
        var el = jQuery(this);
        var ex = /^(0[1-9]|1[0-2])\/?(\d{4})?$/; // Regex for MM/YYYY format
        if (ex.test(el.val()) == false) {
            // If the input doesn't match MM/YYYY format, correct it
            var value = el.val().replace(/\D/g, ''); // Remove non-numeric characters
            if (value.length > 2) {
                // Add '/' after the 2nd character (month)
                value = value.substr(0, 2) + '/' + value.substr(2);
            }
            el.val(value.substr(0, 7)); // Limit to MM/YYYY format (7 characters)
        }
    });


    jQuery(document).on("keypress keyup blur change", '.strict_space', function (event) {
        // restrict double spaces in input
        var el = jQuery(this);
        var val = el.val();
        val = val.replace(/\s{2,}/g, ' ');
        el.val(val);
    });

    jQuery(document).on("keypress keyup blur change", '.strict_phone', function (event) {
        // Replace any non-digit characters
        jQuery(this).val(jQuery(this).val().replace(/[^\d]/g, ""));

        // Check the length of the input value
        if (jQuery(this).val().length > 10) {
            // If length is greater than 10, trim it to the first 10 digits
            jQuery(this).val(jQuery(this).val().substring(0, 10));
        }

        // Prevent non-numeric input
        if ((event.which < 48 || event.which > 57) && event.which !== 0) {
            event.preventDefault();
        }
    });

});


function validatePhoneNumber(phoneNumber) {
    // Remove any whitespace from the input
    phoneNumber = phoneNumber.trim();

    // Check if the phone number is exactly 10 digits and contains only numbers
    const phoneRegex = /^\d{10}$/;

    if (phoneRegex.test(phoneNumber)) {
        return true; // Valid phone number
    }
    return false; // Invalid phone number   
}

function scrollWindowToTop(elem) {
    if (elem && elem.length > 0) {
        jQuery('html, body').animate({
            scrollTop: elem.offset().top - 200
        }, 600); // 600 milliseconds for the animation
    }
}

function scrollDivToTop(elem) {
    if (elem && elem.length > 0) {
        elem.animate({
            scrollTop: 0 // Scroll to the top of the div
        }, 600);
    }
}

function scrollDivToBottom(elem) {
    if (elem && elem.length > 0) {
        elem.animate({
            scrollTop: elem[0].scrollHeight // Scroll to the bottom of the div
        }, 600);
    }
}

function scrollDivToCenter(elem) {
    if (elem && elem.length > 0) {
        // Get the height of the window
        var windowHeight = jQuery(window).height();

        // Get the position of the element relative to the document
        var elementOffset = elem.offset().top;

        // Calculate the scroll position to center the element in the window
        var scrollPosition = elementOffset - (windowHeight / 2) + (elem.outerHeight() / 2);

        // Animate the scroll to the calculated position
        jQuery('html, body').animate({
            scrollTop: scrollPosition
        }, 600);
    }
}

function calculateAgeInYears(birthDate) {
    // const birthDate = "1990-05-15";

    const birth = new Date(birthDate);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const monthDifference = today.getMonth() - birth.getMonth();
    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birth.getDate())) {
        age--;
    }

    return age;
}

function psyem_ValidateInputNumber(input) {
    const trimmedInput = input.trim();
    const regex = /^[1-9]\d*$/;
    if (regex.test(trimmedInput)) {
        return true;
    }
    return false;
}

jQuery(function () {
    let select2SingleFunctionName = "select2Single";
    if (typeof window[select2SingleFunctionName] === "function") {
        select2Single();
    }
});

function validateEmailAddress(inputText) {
    var mailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (inputText.match(mailFormat)) {
        return true;
    } else {
        return false;
    }
}

function prd(data, prefix = 'TEST') {
    console.log(prefix, data);
}

function locationHref(url) {
    if (url && url.length > 0) {
        window.location.href = url;
    }
}

function locationReload() {
    window.location.reload();
}

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

function showHideCommonMesg(stype, mesg) {
    var commonErrorMesg = jQuery('.commonErrorMesg');
    if (commonErrorMesg && commonErrorMesg.length > 0) {
        var commonSuccessMesg = jQuery('.commonSuccessMesg');
        commonSuccessMesg.html('');
        if (stype == 'Show') {
            commonErrorMesg.html(mesg).show('fast').promise().done(function () {
                setTimeout(function () { commonErrorMesg.html(''); }, 12000);
            });
        }
        if (stype == 'Hide') {
            commonErrorMesg.html('').hide('fast');
        }
    }
}

function showHideSuccessMesg(stype, mesg) {
    var commonSuccessMesg = jQuery('.commonSuccessMesg');
    if (commonSuccessMesg && commonSuccessMesg.length > 0) {
        var commonErrorMesg = jQuery('.commonErrorMesg');
        commonErrorMesg.html('');
        if (stype == 'Show') {
            commonSuccessMesg.html(mesg).show('fast').promise().done(function () {
                setTimeout(function () { commonSuccessMesg.html(''); }, 12000);
            });
        }
        if (stype == 'Hide') {
            commonSuccessMesg.html('').hide('fast');
        }
    }
}

function displayToaster(mesg, mtype) {
    // from bundle.js
    var tOptions = {
        position: 'top-right',
        timeOut: "2000",
        newestOnTop: true,
        progressBar: true,
    };

    if (mtype == 'success' || mtype == true) {
        toastr.success(mesg, `Success`, tOptions);
    }
    if (mtype == 'error' || mtype == false) {
        toastr.error(mesg, `Error`, tOptions);
    }
    if (mtype != 'error' && mtype != 'success') {
        toastr.info(mesg, `Info`, tOptions);
    }
}


function showPanelLoader(element, type = true) {
    var type = (type) ? 'load1' : 'load2';
    var $this = element,
        csspinnerClass = 'csspinner',
        panel = element,
        spinner = type;
    if (panel && panel.length > 0) {
        panel.addClass(csspinnerClass + ' ' + spinner);
    }
}

function hidePanelLoader(element, type = true) {
    var type = (type) ? 'load1' : 'load2';
    var $this = element,
        csspinnerClass = 'csspinner',
        panel = element,
        spinner = type;
    if (panel && panel.length > 0) {
        panel.removeClass(csspinnerClass);
    }
}

function getFormData($form) {
    var formData = {};
    if ($form && $form.length > 0) {
        jQuery.each(
            $form.find('input[type="hidden"], input[type="text"], input[type="email"], input[type="url"], input[type="number"], input[type="checkbox"], select, textarea'),
            function (index, ele) {
                if (jQuery(ele).attr('name')) {
                    formData[jQuery(ele).attr('name')] = jQuery(ele).val();
                }
            }
        );
    }
    return formData;
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

function validateUsername(username) {
    const pattern = /^[a-zA-Z\s]{2,60}$/;
    return pattern.test(username);
}

function showCommonModal($modalCont, allowBackDrop = true) {
    if ($modalCont && $modalCont.length > 0) {

        var mOptions = {};
        if (allowBackDrop) {
            var mOptions = { 'backdrop': 'static', keyboard: false };
        }
        $modalCont.modal(mOptions);
        $modalCont.modal('show');
    }
}

function hideCommonModal($modalCont) {
    if ($modalCont && $modalCont.length > 0) {
        $modalCont.modal('hide');
    }
}

function formatPriceWithComma(price = 0) {
    if (Number.isInteger(price)) {
        return price.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    } else if (!isNaN(price) && typeof price === 'number') {
        return price.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
    return price;
}