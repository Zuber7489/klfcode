<?php ob_start(); ?>

<?php
$sessKey       = 'donation_cart';
$donationCart  = (isset($_SESSION[$sessKey])) ? $_SESSION[$sessKey] :  [];
$amount_enc    = (isset($donationCart['amount_enc'])) ? $donationCart['amount_enc'] :  [];
$amount        = (isset($donationCart['amount'])) ? $donationCart['amount'] :  [];
$amount_for    = (isset($donationCart['amount_for'])) ? $donationCart['amount_for'] :  '';
$amount        =  ($amount > 0) ?  $amount : 0.00;
if (!empty($amount_enc)):
    $CurrenyType                    = psyem_GetCurrenyType();
    $psyem_options                  = psyem_GetOptionsWithPrefix();
    $psyem_currency_exchange_rate   = @$psyem_options['psyem_currency_exchange_rate'];
    $psyem_donation_page_id         = @$psyem_options['psyem_donation_page_id'];

    $donation_page_link             = psyem_GetPageLinkByID($psyem_donation_page_id);
    $donation_page_link             = (!empty($donation_page_link)) ? $donation_page_link : 'javascript:void(0);';

    $amount_id     = 0;
    $amount_type   = $amount_enc;
    if (!empty($amount_enc) && $amount_enc != 'Custom') {
        $amount_id       = psyem_safe_b64decode_id($amount_enc);
        $amount_info     = ($amount_id > 0) ? psyem_GetSinglePostWithMetaPrefix('psyem-amounts', $amount_id, 'psyem_amount_') : [];
        $amount_meta     = @$amount_info['meta_data'];
        $amount_type     = @$amount_meta['psyem_amount_type'];
        $amount_price    = @$amount_meta['psyem_amount_price'];
        $amount          = ($amount_price > 0) ?  $amount_price : $amount;
        if ($CurrenyType != 'USD') {
            $usdAmount   = psyem_ConvertUsdToHkd($amount_price, $psyem_currency_exchange_rate);
            $amount      = ($usdAmount > 0) ?  $usdAmount : $amount;
        }
    }
    $cartAmount          = psyem_roundPrecision($amount);
?>
    <style>
        .psyemDonationCheckout .newsletter-agree_wrapper .checkbox.agree {
            background: url(<?= PSYEM_ASSETS . '/images/cross.png' ?>) no-repeat center;
        }
    </style>

    <div class="donation-details psyemDonationCont psyemDonationCheckout" data-aid="<?= $amount_id ?>" style="display: none;">
        <div class="container borderContent">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-3 pNoneLeftRightbilling">
                    <div class="leftChangeAmount">
                        <h6><?= __('Donation Summary', 'psyeventsmanager') ?></h6>
                        <small class="d-block psyemDonationType">
                            <?php if ($amount_for == 'Monthly') { ?>
                                <?= __('Monthly', 'psyeventsmanager') ?>
                            <?php  } ?>
                            <?php if ($amount_for == 'Onetime') { ?>
                                <?= __('One time', 'psyeventsmanager') ?>
                            <?php  } ?>
                            <?= __('Donation', 'psyeventsmanager') ?>
                        </small>
                        <h2>
                            <?= formatPriceWithComma($cartAmount) ?>
                            <span class="currency"><?= psyem_GetCurrenySign() ?> </span>
                        </h2>
                        <a href="<?= $donation_page_link ?>"> <?= __('Change Amount?', 'psyeventsmanager') ?> </a>
                    </div>
                </div>

                <div class="col-md-12 col-lg-8 pNoneLeftRightbilling">
                    <div class="cardBillingDetail hideThankyouCont">
                        <h1> <?= __('Card & Billing', 'psyeventsmanager') ?></h1>
                        <form id="psyemDonationCheckoutForm" action="" method="post">
                            <input type="hidden" name="amount_enc" value="<?= $amount_enc ?>">
                            <input type="hidden" name="amount" value="<?= $amount ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="mb-3"> <?= __('Billing Address', 'psyeventsmanager') ?> </h5>
                                            <div class="inputFormBox">
                                                <div class="form-group col-md-12 mt-2">
                                                    <input type="text" placeholder=" " class="strict_space" id="billing_country" name="billing_country" value="">
                                                    <label for="billing_country">
                                                        <?= __('Country', 'psyeventsmanager') ?> <span class="required">*</span>
                                                    </label>
                                                    <span class="field-error error_billing_country"></span>
                                                </div>
                                                <div class="form-group col-md-12 mt-2">
                                                    <input type="text" placeholder=" " class="strict_space" id="billing_address" name="billing_address" value="">
                                                    <label for="billing_address">
                                                        <?= __('Address Line 1', 'psyeventsmanager') ?> <span class="required">*</span>
                                                    </label>
                                                    <span class="field-error error_billing_address"></span>
                                                </div>
                                                <div class="form-group col-md-12 mt-2">
                                                    <input type="text" placeholder=" " class="strict_space" id="billing_address2" name="billing_address2" value="">
                                                    <label for="billing_address2">
                                                        <?= __('Address Line 2', 'psyeventsmanager') ?>
                                                    </label>
                                                    <span class="field-error error_billing_address2"></span>
                                                </div>
                                                <div class="form-group col-md-12 mt-2">
                                                    <input type="text" placeholder=" " class="strict_space" id="billing_city" name="billing_city" value="">
                                                    <label for="billing_city">
                                                        <?= __('Town/City', 'psyeventsmanager') ?> <span class="required">*</span>
                                                    </label>
                                                    <span class="field-error error_billing_city"></span>
                                                </div>
                                                <div class="form-group col-md-12 mt-2">
                                                    <input type="text" placeholder=" " class="strict_space" id="billing_district" name="billing_district" value="">
                                                    <label for="billing_district">
                                                        <?= __('District', 'psyeventsmanager') ?> <span class="required">*</span>
                                                    </label>
                                                    <span class="field-error error_billing_district"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="mb-3">
                                                <?= __('Additional Details', 'psyeventsmanager') ?>
                                            </h5>
                                            <div class="inputFormBox">
                                                <div class="form-group col-md-12 mt-2">
                                                    <input type="text" placeholder=" " class="strict_space" id="first_name" name="first_name" value="">
                                                    <label for="first_name">
                                                        <?= __('First Name', 'psyeventsmanager') ?> <span class="required">*</span>
                                                    </label>
                                                    <span class="field-error error_first_name"></span>
                                                </div>
                                                <div class="form-group col-md-12 mt-2">
                                                    <input type="text" placeholder=" " class="strict_space" id="last_name" name="last_name" value="">
                                                    <label for="last_name">
                                                        <?= __('Last Name', 'psyeventsmanager') ?> <span class="required">*</span>
                                                    </label>
                                                    <span class="field-error error_last_name"></span>
                                                </div>
                                                <div class="form-group col-md-12 mt-2">
                                                    <input type="email" placeholder=" " class="strict_space" id="email" name="email" value="">
                                                    <label for="email">
                                                        <?= __('Email Address', 'psyeventsmanager') ?> <span class="required">*</span>
                                                    </label>
                                                    <span class="field-error error_email"></span>
                                                </div>
                                                <div class="form-group col-md-12 mt-2">
                                                    <input type="text" placeholder=" " class="strict_space strict_integer" id="phone" name="phone" value="">
                                                    <label for="phone">
                                                        <?= __('Phone Number', 'psyeventsmanager') ?> <span class="required">*</span>
                                                    </label>
                                                    <span class="field-error error_phone"></span>
                                                </div>
                                                <div class="form-group col-md-12 mt-2">
                                                    <input type="text" placeholder=" " class="strict_space" id="company" name="company" value="">
                                                    <label for="company">
                                                        <?= __('Company/Organisation', 'psyeventsmanager') ?> <span class="required">*</span>
                                                    </label>
                                                    <span class="field-error error_company"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="newsletter-agree_wrapper">
                                                <div class="checkbox-newsletter newsletterChk  checkbox agree"></div>
                                                <span>
                                                    <?= __('Sign up for our Newsletter', 'psyeventsmanager') ?>
                                                </span>
                                                <input type="hidden" class="hidden_input newsletter_agree" name="newsletter_agree" value="Agreed">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="newsletter-agree_wrapper">
                                                <div class="checkbox-newsletter tandcCheck checkbox agree"></div>
                                                <span>
                                                    <?= __('I agree to the', 'psyeventsmanager') ?>
                                                    <a href="<?= (isset($psyem_options) && isset($psyem_options['psyem_terms_url'])) ? $psyem_options['psyem_terms_url'] : '' ?>">
                                                        <?= __('Terms & Conditions', 'psyeventsmanager') ?>
                                                    </a>
                                                </span>
                                                <input type="hidden" class="hidden_input tandc_agree" name="tandc_agree" value="Agreed">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="donateConirm" id="psyemContinuePaymentBtn">
                                                <span class="spinner-border spinner-border-sm buttonLoader" role="status"
                                                    aria-hidden="true" style="display: none;"></span>
                                                <?= __('Continue to Payment', 'psyeventsmanager') ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-md-12 mt-3" id="psyemPaymentFormCont" style="display: none;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">
                                            <?= __('Card Details', 'psyeventsmanager') ?>
                                        </h5>
                                    </div>

                                    <div class="col-md-12 stripeFormCont" id="psyemPaymentSection">
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
                                                        <?= __('Please wait', 'psyeventsmanager') ?>
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
                                                        <button id="submit" class="donateConirm">
                                                            <div class="spinner spinner-border spinner-border-sm hidden" id="spinner"></div>
                                                            <span id="button-text"><?= __('Donate & Confirm', 'psyeventsmanager') ?></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </section>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cardBillingDetail showThankyouCont" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <?= __('Thank You', 'psyeventsmanager') ?>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-success mb-5" role="alert">
                                            <?= __('Thank you for your donation', 'psyeventsmanager') ?>
                                        </div>
                                        <div class="alert alert-success" role="alert">
                                            <strong>
                                                <?= __('REFERENCE ID', 'psyeventsmanager') ?>: <span class="psyemPsReferenceNo"></span>
                                            </strong>
                                        </div>
                                        <div class="" role="alert">
                                            <a href="<?php echo home_url(); ?>" class="alert-link text-decoration-none">
                                                <?= __('BACK TO HOMEPAGE', 'psyeventsmanager') ?>
                                            </a>
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
<?php else: ?>
    <div class="cardBillingDetail">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info" role="alert">
                            <strong>
                                <?= __('Please select an amount first to proceed with the donation checkout', 'psyeventsmanager') ?>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php return ob_get_clean(); ?>