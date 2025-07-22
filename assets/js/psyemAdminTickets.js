jQuery(function ($) {
    $(document).on('change', '#psyem_ticket_type', function () {
        var psyemSelBox = $(this);
        var psyemDrpVal = psyemSelBox.val();
        hideTicketTypeFields();
        showTicketTypeFields(psyemDrpVal);
    });

    setTimeout(() => {
        var psyemSelBox = jQuery('#psyem_ticket_type');
        if (psyemSelBox) {
            var psyemDrpVal = psyemSelBox.val();
            hideTicketTypeFields();
            showTicketTypeFields(psyemDrpVal);
        }
    }, 2000);
});

function hideTicketTypeFields() {
    jQuery('#psyem_ticket_price_cont').hide();
    jQuery('#psyem_ticket_group_participant_cont').hide();
}

function showTicketTypeFields(ftype) {
    if (ftype == 'Individual') {
        jQuery('#psyem_ticket_price_cont').show();
    }
    if (ftype == 'Group') {
        jQuery('#psyem_ticket_price_cont').show();
        jQuery('#psyem_ticket_group_participant_cont').show();
    }
}