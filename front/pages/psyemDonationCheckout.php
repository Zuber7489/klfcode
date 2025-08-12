<?php ob_start(); ?>

<script>
// JavaScript-based Chinese language detection
function isChineseLanguageDetected() {
    // Method 1: Check if gtranslate has set Chinese
    if (typeof gtranslateSettings !== 'undefined' && gtranslateSettings.current_language === 'zh') {
        return true;
    }
    
    // Method 2: Check HTML lang attribute
    var htmlLang = document.documentElement.lang;
    if (htmlLang === 'zh' || htmlLang === 'zh-HK' || htmlLang === 'zh-CN') {
        return true;
    }
    
    // Method 3: Check for Chinese text in navigation or headers
    var navItems = document.querySelectorAll('nav a, .nav a, header a');
    for (var i = 0; i < navItems.length; i++) {
        if (navItems[i].textContent.includes('關於我們') || navItems[i].textContent.includes('捐款')) {
            return true;
        }
    }
    
    // Method 4: Check current URL for Chinese indicators
    if (window.location.href.includes('/zh/') || window.location.href.includes('lang=zh')) {
        return true;
    }
    
    // Method 5: Check referrer URL (if user came from Chinese page)
    if (document.referrer.includes('/zh/') || document.referrer.includes('lang=zh')) {
        return true;
    }
    
    // Method 6: Check for Chinese characters in page title or headers
    var pageTitle = document.title;
    if (/[\u4e00-\u9fff]/.test(pageTitle)) {
        return true;
    }
    
    // Method 7: Check localStorage/sessionStorage for language preference
    if (localStorage.getItem('preferred_language') === 'zh' || 
        sessionStorage.getItem('current_language') === 'zh') {
        return true;
    }
    
    return false;
}

// Apply translations when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Debug information
    console.log('=== Chinese Language Detection Debug ===');
    console.log('Current URL:', window.location.href);
    console.log('Referrer URL:', document.referrer);
    console.log('HTML Lang:', document.documentElement.lang);
    console.log('Page Title:', document.title);
    
    var isChineseDetected = isChineseLanguageDetected();
    console.log('Chinese Language Detected:', isChineseDetected);
    
    if (isChineseDetected) {
        // Apply Chinese translations
        var translations = {
            'Donation Summary': '捐款摘要',
            'Monthly Donation': '每月定額捐款',
            'One time Donation': '一次性捐款', 
            'Change Amount?': '更改金額？',
            'Card & Billing': '信用卡和賬單',
            'Billing Address': '帳單地址',
            'Additional Details': '額外信息',
            'Country': '國家',
            'Address Line 1': '地址欄1',
            'Address Line 2': '地址欄2',
            'Town/City': '城市',
            'District': '區域',
            'First Name': '名字',
            'Last Name': '姓氏',
            'Email Address': '電子郵箱',
            'Phone Number': '電話號碼',
            'Company/Organisation': '公司/組織',
            'Sign up for our Newsletter': '登記接收我們的通訊',
            'I agree to the': '我同意',
            'Terms & Conditions': '使用條款',
            'Continue to Payment': '確認捐款',
            'Card Details': '信用卡詳情',
            'Please wait': '請稍候',
            'By submitting payment details, I hereby agreed with the terms & conditions': '提交付款詳情即表示我同意條款及細則',
            'Donate & Confirm': '確認捐款',
            'Thank You': '謝謝您',
            'Thank you for your donation': '感謝您的捐款',
            'REFERENCE ID': '參考編號',
            'BACK TO HOMEPAGE': '返回主頁',
            'Please select an amount first to proceed with the donation checkout': '請先選擇金額以進行捐款結帳'
        };
        
        // Apply translations to specific elements with more targeted approach
        
        // Left sidebar translations
        var donationSummary = document.querySelector('.leftChangeAmount h6');
        if (donationSummary && donationSummary.textContent.includes('Donation Summary')) {
            donationSummary.textContent = '捐款摘要';
        }
        
        var donationType = document.querySelector('.psyemDonationType');
        if (donationType) {
            if (donationType.textContent.includes('Monthly')) {
                donationType.innerHTML = donationType.innerHTML.replace('Monthly', '每月');
            }
            if (donationType.textContent.includes('One time')) {
                donationType.innerHTML = donationType.innerHTML.replace('One time', '一次性');
            }
            if (donationType.textContent.includes('Donation')) {
                donationType.innerHTML = donationType.innerHTML.replace('Donation', '捐款');
            }
        }
        
        var changeAmount = document.querySelector('.leftChangeAmount a');
        if (changeAmount && changeAmount.textContent.includes('Change Amount?')) {
            changeAmount.textContent = '更改金額？';
        }
        
        // Main form translations
        var cardBilling = document.querySelector('.cardBillingDetail h1');
        if (cardBilling && cardBilling.textContent.includes('Card & Billing')) {
            cardBilling.textContent = '信用卡和賬單';
        }
        
        // Section headers
        var billingAddress = document.querySelector('h5');
        if (billingAddress && billingAddress.textContent.includes('Billing Address')) {
            billingAddress.textContent = '帳單地址';
        }
        
        var additionalDetails = document.querySelectorAll('h5');
        additionalDetails.forEach(function(header) {
            if (header.textContent.includes('Additional Details')) {
                header.textContent = '額外信息';
            }
        });
        
        // Form labels
        var labels = document.querySelectorAll('label');
        labels.forEach(function(label) {
            var text = label.textContent;
            Object.keys(translations).forEach(function(english) {
                if (text.includes(english) && !text.includes(translations[english])) {
                    label.innerHTML = label.innerHTML.replace(english, translations[english]);
                }
            });
        });
        
                 // Newsletter and Terms & Conditions
         var newsletterSpan = document.querySelectorAll('.newsletter-agree_wrapper span');
         newsletterSpan.forEach(function(span) {
             if (span.textContent.includes('Sign up for our Newsletter')) {
                 span.textContent = '登記接收我們的通訊';
             }
             if (span.textContent.includes('I agree to the')) {
                 span.innerHTML = span.innerHTML.replace('I agree to the', '我同意');
             }
         });
         
         var termsLink = document.querySelectorAll('.newsletter-agree_wrapper a');
         termsLink.forEach(function(link) {
             if (link.textContent.includes('Terms & Conditions')) {
                 link.textContent = '使用條款';
             }
         });
         
                  // Buttons and links
         var continueBtn = document.querySelector('#psyemContinuePaymentBtn');
         if (continueBtn && continueBtn.textContent.includes('Continue to Payment')) {
             continueBtn.innerHTML = continueBtn.innerHTML.replace('Continue to Payment', '確認捐款');
         }
         
         // Card Details section
         var cardDetailsHeaders = document.querySelectorAll('h5');
         cardDetailsHeaders.forEach(function(header) {
             if (header.textContent.includes('Card Details')) {
                 header.textContent = '信用卡詳情';
             }
         });
         
         // Payment form elements
         var pleaseWaitText = document.querySelector('.stripeLoader');
         if (pleaseWaitText && pleaseWaitText.textContent.includes('Please wait')) {
             pleaseWaitText.innerHTML = pleaseWaitText.innerHTML.replace('Please wait', '請稍候');
         }
         
         var paymentTermsLabel = document.querySelector('.paymentTermsLabel');
         if (paymentTermsLabel && paymentTermsLabel.textContent.includes('By submitting payment details, I hereby agreed with the terms & conditions')) {
             paymentTermsLabel.textContent = '提交付款詳情即表示我同意條款及細則';
         }
         
         var donateConfirmBtn = document.querySelector('#button-text');
         if (donateConfirmBtn && donateConfirmBtn.textContent.includes('Donate & Confirm')) {
             donateConfirmBtn.textContent = '捐款並確認';
         }
         
         // Thank You section
         var thankYouTitle = document.querySelector('.card-title');
         if (thankYouTitle && thankYouTitle.textContent.includes('Thank You')) {
             thankYouTitle.textContent = '謝謝您';
         }
         
         var thankYouMessage = document.querySelectorAll('.alert-success');
         thankYouMessage.forEach(function(alert) {
             if (alert.textContent.includes('Thank you for your donation')) {
                 alert.textContent = '感謝您的捐款';
             }
             if (alert.innerHTML.includes('REFERENCE ID')) {
                 alert.innerHTML = alert.innerHTML.replace('REFERENCE ID', '參考編號');
             }
         });
         
         var backToHomepage = document.querySelector('.alert-link');
         if (backToHomepage && backToHomepage.textContent.includes('BACK TO HOMEPAGE')) {
             backToHomepage.textContent = '返回主頁';
         }
         
         // Error message for no amount selected
         var noAmountAlert = document.querySelectorAll('.alert-info strong');
         noAmountAlert.forEach(function(alert) {
             if (alert.textContent.includes('Please select an amount first to proceed with the donation checkout')) {
                 alert.textContent = '請先選擇金額以進行捐款結帳';
             }
         });
         
         // Header Navigation Translations
         var headerTranslations = {
             'About Us': '關於我們',
             'Programmes': '項目',
             'Events': '活動', 
             'News': '新聞',
             'Knowledge Hub': '知識中心',
             'Get Involved': '參與',
             'Donate': ': 捐款'
         };
         
         // More comprehensive selectors for navigation
         var navSelectors = [
             'nav a', '.nav a', 'header a', '.navigation a', '.menu a',
             '.navbar a', '.main-menu a', '.site-navigation a', 
             '.primary-menu a', '.header-menu a', '#menu a',
             '.top-menu a', '.main-navigation a'
         ];
         
         navSelectors.forEach(function(selector) {
             var links = document.querySelectorAll(selector);
             links.forEach(function(link) {
                 var text = link.textContent.trim();
                 if (headerTranslations[text]) {
                     link.textContent = headerTranslations[text];
                 }
             });
         });
         
         // Footer Translations - More aggressive approach
         var footerTranslations = {
             'GET IN TOUCH': '聯絡我們',
             'Get In Touch': '聯絡我們',
             'TERMS & CONDITIONS': '條款及細則',
             'Terms & Conditions': '條款及細則',
             'SIGN UP TO THE NEWSLETTER': '登記接收電子通訊',
             'Sign Up to the Newsletter': '登記接收電子通訊',
             'PRIVACY POLICY': '私隱聲明',
             'Privacy Policy': '私隱聲明'
         };
         
         // Much more comprehensive footer search - search ALL links in page
         var allLinks = document.querySelectorAll('a');
         allLinks.forEach(function(link) {
             var text = link.textContent.trim();
             if (footerTranslations[text]) {
                 link.textContent = footerTranslations[text];
                 console.log('Footer link translated:', text, '→', footerTranslations[text]);
             }
         });
         
         // Also search for text nodes that might not be in links
         var allElements = document.querySelectorAll('*');
         allElements.forEach(function(element) {
             var text = element.textContent.trim();
             // Only translate if this is a leaf node (no child elements with text)
             if (element.children.length === 0 && footerTranslations[text]) {
                 element.textContent = footerTranslations[text];
                 console.log('Footer text translated:', text, '→', footerTranslations[text]);
             }
         });
         
                  console.log('Chinese translations applied via JavaScript');
     }
     
     // Also apply translations after a short delay to catch any dynamically loaded content
     setTimeout(function() {
         if (isChineseLanguageDetected()) {
             console.log('Re-applying translations after delay...');
             
             // Re-apply header translations
             var headerTranslations = {
                 'About Us': '關於我們',
                 'Programmes': '項目',
                 'Events': '活動', 
                 'News': '新聞',
                 'Knowledge Hub': '知識中心',
                 'Get Involved': '參與',
                 'Donate': '捐款'
             };
             
             var navSelectors = [
                 'nav a', '.nav a', 'header a', '.navigation a', '.menu a',
                 '.navbar a', '.main-menu a', '.site-navigation a', 
                 '.primary-menu a', '.header-menu a', '#menu a',
                 '.top-menu a', '.main-navigation a'
             ];
             
             navSelectors.forEach(function(selector) {
                 var links = document.querySelectorAll(selector);
                 links.forEach(function(link) {
                     var text = link.textContent.trim();
                     if (headerTranslations[text]) {
                         link.textContent = headerTranslations[text];
                     }
                 });
             });
             
             // Re-apply footer translations with aggressive approach
             var footerTranslations = {
                 'GET IN TOUCH': '聯絡我們',
                 'Get In Touch': '聯絡我們',
                 'TERMS & CONDITIONS': '條款及細則',
                 'Terms & Conditions': '條款及細則',
                 'SIGN UP TO THE NEWSLETTER': '登記接收電子通訊',
                 'Sign Up to the Newsletter': '登記接收電子通訊',
                 'PRIVACY POLICY': '私隱聲明',
                 'Privacy Policy': '私隱聲明'
             };
             
             // Search ALL links again
             var allLinks = document.querySelectorAll('a');
             allLinks.forEach(function(link) {
                 var text = link.textContent.trim();
                 if (footerTranslations[text]) {
                     link.textContent = footerTranslations[text];
                     console.log('Delayed footer link translated:', text, '→', footerTranslations[text]);
                 }
             });
             
             // Search all elements again
             var allElements = document.querySelectorAll('*');
             allElements.forEach(function(element) {
                 var text = element.textContent.trim();
                 if (element.children.length === 0 && footerTranslations[text]) {
                     element.textContent = footerTranslations[text];
                     console.log('Delayed footer text translated:', text, '→', footerTranslations[text]);
                 }
             });
             
             console.log('Header/Footer translations re-applied after delay');
         }
     }, 1000);
 });
 </script>

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