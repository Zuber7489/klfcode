jQuery(function ($) {
    $(document).on('change', '#psyem_coupon_type', function () {
        var psyemSelBox = $(this);
        var psyemDrpVal = psyemSelBox.val();
        hideCouponAmountTypeFields();
        showCouponAmountTypeFields(psyemDrpVal);
    });

    setTimeout(() => {
        var psyemSelBox = jQuery('#psyem_coupon_type');
        if (psyemSelBox) {
            var psyemDrpVal = psyemSelBox.val();
            hideCouponAmountTypeFields();
            showCouponAmountTypeFields(psyemDrpVal);
        }
    }, 2000);
});

function hideCouponAmountTypeFields() {
    jQuery('#psyem_coupon_discount_amount').hide();
    jQuery('#psyem_coupon_discount_percentage').hide();
}

function showCouponAmountTypeFields(ftype) {
    if (ftype == 'Fixed') {
        jQuery('#psyem_coupon_discount_amount').show();
    }
    if (ftype == 'Percentage') {
        jQuery('#psyem_coupon_discount_percentage').show();
    }
}