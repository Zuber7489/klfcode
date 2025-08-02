<?php ob_start(); ?>
<?php
/**
 * Template Name: Psyem Events checkout shortcode
 */
?>
<?php
$REQData            = (isset($_GET) && !empty($_GET)) ? $_GET : [];
$EventIdEnc         = (isset($REQData['checkkey']) && !empty($REQData['checkkey'])) ? $REQData['checkkey'] :  '';
$EventId            = (!empty($EventIdEnc)) ? psyem_safe_b64decode_id($EventIdEnc) : 0;
$psyemEventInfo     = psyem_GetSinglePostWithMetaPrefix('psyem-events', $EventId, 'psyem_event_');
$psyemEventMeta     = @$psyemEventInfo['meta_data'];
$isBookingAllowed   = psyem_IsEventBookingAllowed(0, $psyemEventInfo);

$eventRegType       = (isset($psyemEventMeta['psyem_event_registration_type'])) ? $psyemEventMeta['psyem_event_registration_type'] :  '';
$excerpt            = @$psyemEventInfo['excerpt'];
$fetauredImage      = @$psyemEventInfo['image'];

$event_startdate    = @$psyemEventMeta['psyem_event_startdate'];
$event_starttime    = @$psyemEventMeta['psyem_event_starttime'];
$start_date         = psyem_GetFormattedDatetime('d F Y', $event_startdate);
$start_time         = psyem_GetFormattedDatetime('h:i A', $event_startdate . '' . $event_starttime);

$event_enddate      = @$psyemEventMeta['psyem_event_enddate'];
$event_endtime      = @$psyemEventMeta['psyem_event_endtime'];
$end_date           = psyem_GetFormattedDatetime('d F Y', $event_enddate);
$end_time           = psyem_GetFormattedDatetime('h:i A', $event_startdate . '' . $event_endtime);

$cartInfoData       = (isset($_SESSION[$EventIdEnc])) ? $_SESSION[$EventIdEnc] :  [];
$curreny_type       = (isset($cartInfoData['curreny_type']) && !empty($cartInfoData['curreny_type'])) ? $cartInfoData['curreny_type'] : 'USD';
$curreny_sign       = (isset($cartInfoData['curreny_sign']) && !empty($cartInfoData['curreny_sign'])) ? $cartInfoData['curreny_sign'] : '$';
$checkout_price     = (isset($cartInfoData['checkout_price']) && !empty($cartInfoData['checkout_price'])) ? $cartInfoData['checkout_price'] : 0.00;
$discount_price     = (isset($cartInfoData['discount_price']) && !empty($cartInfoData['discount_price'])) ? $cartInfoData['discount_price'] : 0.00;
$total_price        = (isset($cartInfoData['total_price']) && !empty($cartInfoData['total_price'])) ? $cartInfoData['total_price'] : 0.00;
$total_discount     = (isset($cartInfoData['total_discount']) && !empty($cartInfoData['total_discount'])) ? $cartInfoData['total_discount'] : 0.00;
$coupon_data        = (isset($cartInfoData['coupon_data']) && !empty($cartInfoData['coupon_data'])) ? $cartInfoData['coupon_data'] : [];
$cart_data          = (isset($cartInfoData['cart_data']) && !empty($cartInfoData['cart_data'])) ? $cartInfoData['cart_data'] : [];

$psyem_options                  = psyem_GetOptionsWithPrefix();
$psyem_event_listing_page_id    = @$psyem_options['psyem_event_listing_page_id'];
?>
<div class="psyemEventsCheckoutCont" style="display: none;">
    <section class="topBradcampImage" style="background:url(<?= $fetauredImage ?>); background-position: center; background-repeat: no-repeat; background-size: cover;">
        <div class="container">
            <div class="row justify-content-between mrAll-0">
                <div class="col-md-12 pAll-0">
                    <p class="bradCamp">
                        <a href="<?= psyem_GetPageLinkByID($psyem_event_listing_page_id) ?>">
                            <?= __('Our Events', 'psyeventsmanager') ?> >
                        </a>
                        <a href="<?= @$psyemEventInfo['link'] ?>">
                            <?= @$psyemEventInfo['title'] ?> >
                        </a>
                        <?= __('Checkout', 'psyeventsmanager') ?>
                    </p>
                </div>
            </div>

            <div class="row justify-content-between align-items-end mrAll-0">
                <div class="col-md-3 pAll-0">
                    <img src="<?= $fetauredImage ?>" class="imgWdth" alt="<?= @$psyemEventInfo['title'] ?>" />
                </div>
                <div class="col-md-9">
                    <h2 class="gTxt">
                        <?= @$psyemEventInfo['title'] ?>
                    </h2>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <?php if (!empty($psyemEventInfo) && is_array($psyemEventInfo)) { ?>
                <?php if (!empty($excerpt)) { ?>
                    <div class="post-excerpt col-md-12 mb-5 p-3" id="EV<?= $EventId ?>">
                        <?= (!empty($excerpt)) ? $excerpt : ''; ?>
                    </div>
                <?php }  ?>

                <div class="post-checkout col-md-12 mb-5">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="card-title">
                                                        <?= __('Event Info', 'psyeventsmanager') ?>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="fw-bold d-block mb-2">
                                                            <?= __('Start Date Time', 'psyeventsmanager') ?>
                                                        </label>
                                                        <p><?php echo $start_date . ' - ' . $start_time; ?></p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="fw-bold d-block mb-2">
                                                            <?= __('End Date Time', 'psyeventsmanager') ?>
                                                        </label>
                                                        <p><?php echo $end_date . ' - ' . $end_time; ?></p>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="fw-bold d-block mb-2">
                                                            <?= __('Registration Type', 'psyeventsmanager') ?>
                                                        </label>
                                                        <p><?= $eventRegType ?></p>
                                                    </div>

                                                    <?php if ($eventRegType == 'Free'): ?>
                                                        <div class="mb-3">
                                                            <label class="fw-bold d-block mb-2">
                                                                <?= __('Registration Ticket Price', 'psyeventsmanager') ?>
                                                            </label>
                                                            <p> $0.00</p>
                                                        </div>
                                                    <?php elseif ($eventRegType == 'Invitation'): ?>
                                                        <div class="mb-3">
                                                            <label class="fw-bold d-block mb-2">
                                                                <?= __('Registration Ticket Price', 'psyeventsmanager') ?>
                                                            </label>
                                                            <p> $0.00</p>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="mb-3">
                                                        <label class="fw-bold d-block mb-2">
                                                            <?= __('Address', 'psyeventsmanager') ?>
                                                        </label>
                                                        <p><?= $psyemEventMeta['psyem_event_address'] ?></p>
                                                    </div>

                                                    <?php if (!empty($psyemEventMeta['psyem_event_disclaimer'])): ?>
                                                        <div class="mb-3">
                                                            <label class="fw-bold d-block mb-2">
                                                                <?= __('Disclaimer', 'psyeventsmanager') ?>
                                                            </label>
                                                            <p><?= @$psyemEventMeta['psyem_event_disclaimer'] ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if ($eventRegType == 'Paid'): ?>
                                            <div class="col-md-8" id="psyemPaymentSection">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title">
                                                            <?= __('Order Summary', 'psyeventsmanager') ?>
                                                        </h5>
                                                    </div>

                                                    <div class="card-body" id="psyemBasicInfoCont">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <?php if (!empty($cart_data) && is_array($cart_data)): ?>
                                                                    <table class="table table-responsive table-hovered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th> <?= __('Item', 'psyeventsmanager') ?></th>
                                                                                <th> <?= __('Quantity', 'psyeventsmanager') ?></th>
                                                                                <th> <?= __('Total', 'psyeventsmanager') ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            foreach ($cart_data as $cartTicketId => $cartTicketInfo) :
                                                                            ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <input type="hidden"
                                                                                            class="psyemCartItemInput"
                                                                                            data-ticket="<?= $cartTicketId ?>"
                                                                                            value="<?= @$cartTicketInfo['choosen_count'] ?>">

                                                                                        <h5 class="card-title mb-0">
                                                                                            <?= @$cartTicketInfo['title'] ?>
                                                                                        </h5>
                                                                                        <strong><?= @$cartTicketInfo['type'] ?></strong>
                                                                                        <p><?php echo (!empty($cartTicketInfo['excerpt'])) ? $cartTicketInfo['excerpt'] : ''; ?></p>
                                                                                    </td>
                                                                                    <td><?= @$cartTicketInfo['choosen_count'] ?></td>
                                                                                    <td>
                                                                                        <p><?= @$curreny_sign ?><?= @$cartTicketInfo['cart_price'] ?></p>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php endforeach; ?>
                                                                        </tbody>
                                                                    </table>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="row">
                                                                    <div class="col-md-12 mb-3">
                                                                        <label for="psyem_name" class="fw-bold d-block mb-2">
                                                                            <?= __('Name', 'psyeventsmanager') ?>
                                                                        </label>
                                                                        <input type="name" class="form-control strict_space" id="psyem_name" name="psyem_name" value="" placeholder="<?= __('Enter your name', 'psyeventsmanager') ?>" />
                                                                    </div>
                                                                    <div class="col-md-12 mb-3">
                                                                        <label for="psyem_company" class="fw-bold d-block mb-2">
                                                                            <?= __('Company', 'psyeventsmanager') ?>
                                                                        </label>
                                                                        <input type="text" class="form-control strict_space" id="psyem_company" name="psyem_company" value="" placeholder="<?= __('Enter company name', 'psyeventsmanager') ?>" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="row">
                                                                    <div class="col-md-12 mb-3">
                                                                        <label for="psyem_email" class="fw-bold d-block mb-2">
                                                                            <?= __('Email', 'psyeventsmanager') ?>
                                                                        </label>
                                                                        <input type="Email" class="form-control strict_space" id="psyem_email" name="psyem_email" value="" placeholder="<?= __('Enter your valid email', 'psyeventsmanager') ?>" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="psyem_coupon" class="fw-bold d-block mb-2">
                                                                            <?= __('Coupon Code', 'psyeventsmanager') ?>
                                                                        </label>
                                                                        <div class="input-group mb-3">
                                                                            <input type="text" class="form-control strict_space" placeholder="<?= __('Enter coupon code', 'psyeventsmanager') ?>" value="" id="psyem_coupon" name="psyem_coupon" />
                                                                            <button class="btn btn-outline-primary psyemApplyCouponCode" type="button">
                                                                                <?= __('Apply', 'psyeventsmanager') ?>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <table class="table table-responsive table-hovered">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><?= __('Total Ticket Price', 'psyeventsmanager') ?> :</td>
                                                                            <th id="psyemTotalTicketPrice" class="text-center">
                                                                                <?= @$curreny_sign ?><?= psyem_roundPrecision($total_price ?? 0.00) ?>
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><?= __('Total Discount Price', 'psyeventsmanager') ?> :</td>
                                                                            <th id="psyemTotalDiscountPrice" class="text-center">
                                                                                <?= @$curreny_sign ?><?= psyem_roundPrecision($total_discount ?? 0.00) ?>
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th><?= __('Total Checkout Price', 'psyeventsmanager') ?> :</th>
                                                                            <th id="psyemTotalCheckoutPrice" class="text-center" style="font-size: 18px;">
                                                                                <?= @$curreny_sign ?><?= psyem_roundPrecision($checkout_price ?? 0.00) ?>
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th colspan="2" class="text-center">
                                                                                <?php if ($isBookingAllowed == 'Yes'): ?>
                                                                                    <button class="btn btn-primary" id="psyemContinuePaymentBtn">
                                                                                        <span class="spinner-border buttonLoader spinner-border-sm" style="display: none;"></span>
                                                                                        <?= __('Continue to Payment', 'psyeventsmanager') ?>
                                                                                    </button>
                                                                                <?php else: ?>
                                                                                    <p class="text-danger mb-1">
                                                                                        <span class="dashicons dashicons-info"></span>
                                                                                        <?= __('Registration for this event has been closed', 'psyeventsmanager') ?>
                                                                                    </p>
                                                                                <?php endif; ?>
                                                                            </th>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php if ($isBookingAllowed == 'Yes'): ?>
                                                        <div class="card-body" style="display: none;" id="psyemPaymentFormCont">
                                                            <div class="card">
                                                                <div class="card-body stripeFormCont">
                                                                    <form id="psySignupStripeForm" name="psySignupStripeForm" class="payment-form"
                                                                        action="" method="POST" autocomplete="nope">
                                                                        <section class="stripeCreditForm">
                                                                            <input type="hidden" class="client_secret_info" name="client_secret_info"
                                                                                value="" />
                                                                            <div id="payment-element">
                                                                                <!--Stripe.js injects the Payment Element-->
                                                                                <div class="stripeLoader">
                                                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                                                        aria-hidden="true"></span>
                                                                                    <?= __('Please wait', 'psyeventsmanager') ?>...
                                                                                </div>
                                                                            </div>
                                                                            <div class="input-box d-block mb-2">
                                                                                <span id="payment-message"
                                                                                    class="text-danger p-0 text-start commonErrorMesg">
                                                                                </span>
                                                                            </div>

                                                                            <div class="input-box d-flex align-items-center mt-4 mb-4">
                                                                                <label class="font-12 m-0 paymentTermsLabel" for="paymentTermsChkb">
                                                                                    <?= __('By submitting payment details, I hereby agreed with the terms & conditions', 'psyeventsmanager') ?>
                                                                                </label>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-12 text-right">
                                                                                    <button id="submit">
                                                                                        <div class="spinner spinner-border spinner-border-sm hidden" id="spinner"></div>
                                                                                        <span id="button-text"><?= __('PAY NOW', 'psyeventsmanager') ?></span>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </section>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($eventRegType == 'Free'): ?>
                                            <div class="col-md-8" id="psyemFreeSection">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title"><?= __('Participants Info', 'psyeventsmanager') ?></h5>
                                                    </div>

                                                    <div class="card-body" id="psyemBasicInfoCont">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="psyem_name" class="fw-bold d-block mb-2">
                                                                    <?= __('Name', 'psyeventsmanager') ?>
                                                                </label>
                                                                <input type="name" class="form-control strict_space" id="psyem_name" name="psyem_name" value="" placeholder="<?= __('Enter your name', 'psyeventsmanager') ?>" />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="psyem_email" class="fw-bold d-block mb-2">
                                                                    <?= __('Email', 'psyeventsmanager') ?>
                                                                </label>
                                                                <input type="email" class="form-control strict_space" id="psyem_email" name="psyem_email" value="" placeholder="<?= __('Enter your valid email', 'psyeventsmanager') ?>" />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="psyem_company" class="fw-bold d-block mb-2">
                                                                    <?= __('Company', 'psyeventsmanager') ?>
                                                                </label>
                                                                <input type="text" class="form-control strict_space" id="psyem_company" name="psyem_company" value="" placeholder="<?= __('Enter company name', 'psyeventsmanager') ?>" />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="psyem_tickets" class="fw-bold d-block mb-2">
                                                                    <?= __('Participants', 'psyeventsmanager') ?>
                                                                </label>
                                                                <input type="number" class="form-control strict_integer strict_space" id="psyem_tickets" name="psyem_tickets" value="1" placeholder="<?= __('Select total participants', 'psyeventsmanager') ?>" />
                                                            </div>
                                                            <div class="col-md-12 text-center mt-5">
                                                                <?php if ($isBookingAllowed == 'Yes'): ?>
                                                                    <button class="btn btn-primary" id="psyemContinueFreeBtn">
                                                                        <span class="spinner-border buttonLoader spinner-border-sm" style="display: none;"></span>
                                                                        <?= __('Submit to Book', 'psyeventsmanager') ?>
                                                                    </button>
                                                                <?php else: ?>
                                                                    <p class="text-danger mb-1">
                                                                        <span class="dashicons dashicons-info"></span>
                                                                        <?= __('Registration for this event has been closed', 'psyeventsmanager') ?>
                                                                    </p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($eventRegType == 'Invitation'): ?>
                                            <div class="col-md-8" id="psyemInvitationSection">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title"><?= __('Participants Info', 'psyeventsmanager') ?></h5>
                                                    </div>

                                                    <div class="card-body" id="psyemBasicInfoCont">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="psyem_name" class="fw-bold d-block mb-2">
                                                                    <?= __('Name', 'psyeventsmanager') ?>
                                                                </label>
                                                                <input type="name" class="form-control strict_space" id="psyem_name" name="psyem_name" value="" placeholder="<?= __('Enter your name', 'psyeventsmanager') ?>" />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="psyem_email" class="fw-bold d-block mb-2">
                                                                    <?= __('Email', 'psyeventsmanager') ?>
                                                                </label>
                                                                <input type="email" class="form-control strict_space" id="psyem_email" name="psyem_email" value="" placeholder="<?= __('Enter your valid email', 'psyeventsmanager') ?>" />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="psyem_company" class="fw-bold d-block mb-2">
                                                                    <?= __('Company', 'psyeventsmanager') ?>
                                                                </label>
                                                                <input type="text" class="form-control strict_space" id="psyem_company" name="psyem_company" value="" placeholder="<?= __('Enter company name', 'psyeventsmanager') ?>" />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="psyem_tickets" class="fw-bold d-block mb-2">
                                                                    <?= __('Participants', 'psyeventsmanager') ?>
                                                                </label>
                                                                <input type="number" class="form-control strict_integer strict_space" id="psyem_tickets" name="psyem_tickets" value="1" placeholder="<?= __('Select total participants', 'psyeventsmanager') ?>" />
                                                            </div>
                                                            <div class="col-md-12 text-center mt-5">
                                                                <?php if ($isBookingAllowed == 'Yes'): ?>
                                                                    <button class="btn btn-primary" id="psyemContinueInvitationBtn">
                                                                        <span class="spinner-border buttonLoader spinner-border-sm" style="display: none;"></span>
                                                                        <?= __('Submit to Book', 'psyeventsmanager') ?>
                                                                    </button>
                                                                <?php else: ?>
                                                                    <p class="text-danger mb-1">
                                                                        <span class="dashicons dashicons-info"></span>
                                                                        <?= __('Registration for this event has been closed', 'psyeventsmanager') ?>
                                                                    </p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!($eventRegType == 'Paid') && !($eventRegType == 'Free') && !($eventRegType == 'Invitation')): ?>
                                            <div class="col-md-8" id="psyemFalseSection">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="post-checkout mb-5">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12 mb-3">
                                                                                    <div class="alert alert-danger" role="alert">
                                                                                        <span class="dashicons dashicons-info"></span>
                                                                                        <?= __('Registration for this event has been closed', 'psyeventsmanager') ?>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="alert alert-info" role="alert">
                                                                                        <?= __('Click', 'psyeventsmanager') ?>
                                                                                        <a href="<?= psyem_GetPageLinkByID($psyem_event_listing_page_id) ?>" class="alert-link">
                                                                                            <?= __('here', 'psyeventsmanager') ?>
                                                                                        </a>
                                                                                        <?= __('to view events or book new tickets', 'psyeventsmanager') ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="post-checkout col-md-12 mb-5">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="alert alert-danger" role="alert">
                                                <?= __('Event data not found to process checkout', 'psyeventsmanager') ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="alert alert-info" role="alert">
                                                <?= __('Click', 'psyeventsmanager') ?>
                                                <a href="<?= psyem_GetPageLinkByID($psyem_event_listing_page_id) ?>" class="alert-link">
                                                    <?= __('here', 'psyeventsmanager') ?>
                                                </a>
                                                <?= __('to view events or book new tickets', 'psyeventsmanager') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }   ?>
        </div>
    </div>
</div>
<script>
    let psyemCheckoutEncKey = "<?= $EventIdEnc ?>";
</script>

<?php return ob_get_clean(); ?>