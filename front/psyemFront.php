<?php
require PSYEM_PATH . 'packages/vendor/autoload.php';

use Stripe\Stripe;

class psyemFrontManager
{
    var $REQ;

    function __construct()
    {
        global $wpdb, $post, $current_user;
        $this->REQ    = (isset($_REQUEST) && !empty($_REQUEST)) ? $_REQUEST : array();
        $this->psyem_EventsManagerFrontActions();
    }

    function psyem_EventsManagerFrontActions()
    {
        global $wpdb, $post, $current_user;

        add_action('init', array(&$this, PSYEM_PREFIX . 'StartSession'), 1);
        add_action('init', array(&$this, PSYEM_PREFIX . 'ManageAllShortcodes'));

        add_action('wp_head', array(&$this, PSYEM_PREFIX . 'AddCustomFrontHeadstyles'));

        // front scripts
        add_action('wp_enqueue_scripts', array(&$this, PSYEM_PREFIX . 'RegisterEventsManagerFrontScripts'));
        add_action('wp_enqueue_scripts', array(&$this, PSYEM_PREFIX . 'EnqueueEventsManagerFrontScripts'));

        // misc
        add_filter('page_template', array(&$this,  PSYEM_PREFIX . 'AssignFrontPageTemplates'));
        add_filter('template_include', array(&$this,  PSYEM_PREFIX . 'AssignFrontPageTemplates'));

        // ajax 
        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_cart_prices', array(&$this, PSYEM_PREFIX . 'ManageEventOrderCartPricesAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_cart_prices', array(&$this, PSYEM_PREFIX . 'ManageEventOrderCartPricesAjax'));

        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_stripe_intent', array(&$this, PSYEM_PREFIX . 'ManageEventOrderStripeIntentAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_stripe_intent', array(&$this, PSYEM_PREFIX . 'ManageEventOrderStripeIntentAjax'));

        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_stripe_payment', array(&$this, PSYEM_PREFIX . 'ManageEventOrderStripePaymentAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_stripe_payment', array(&$this, PSYEM_PREFIX . 'ManageEventOrderStripePaymentAjax'));

        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_free_booking', array(&$this, PSYEM_PREFIX . 'ManageEventOrderFreeBookingAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_free_booking', array(&$this, PSYEM_PREFIX . 'ManageEventOrderFreeBookingAjax'));

        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_projectsafe_form', array(&$this, PSYEM_PREFIX . 'ManageProjectsafeFormDataAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_projectsafe_form', array(&$this, PSYEM_PREFIX . 'ManageProjectsafeFormDataAjax'));

        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_donation_amounts', array(&$this, PSYEM_PREFIX . 'ManageDonationAmountsDataAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_donation_amounts', array(&$this, PSYEM_PREFIX . 'ManageDonationAmountsDataAjax'));
        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_process_amounts', array(&$this, PSYEM_PREFIX . 'ManageDonationAmountsProcessAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_process_amounts', array(&$this, PSYEM_PREFIX . 'ManageDonationAmountsProcessAjax'));
        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_donation_intent', array(&$this, PSYEM_PREFIX . 'ManageDonationStripeIntentAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_donation_intent', array(&$this, PSYEM_PREFIX . 'ManageDonationStripeIntentAjax'));
        add_action('wp_ajax_'               . PSYEM_PREFIX . 'manage_donation_payment', array(&$this, PSYEM_PREFIX . 'ManageDonationStripePaymentAjax'));
        add_action('wp_ajax_nopriv_'        . PSYEM_PREFIX . 'manage_donation_payment', array(&$this, PSYEM_PREFIX . 'ManageDonationStripePaymentAjax'));


        // miscmanage_projectsafe_form
        add_action('rest_api_init',         array(&$this, PSYEM_PREFIX . 'RegisterRestApiRoutes'));
        add_filter('posts_search',          array(&$this, PSYEM_PREFIX . 'CustomPostSearchQuery'), 10, 2);
    }

    function psyem_StartSession()
    {
        if (!session_id()) {
            session_start();
        }
    }

    function psyem_AddCustomFrontHeadstyles()
    {
        echo '<style>
            .single-psyem-speakers .type-psyem-speakers{margin-top: 200px;}
            .single-psyem-partners .type-psyem-partners{ margin-top: 200px;}
            .single-psyem-coupons .type-psyem-coupons{margin-top: 200px;}
            .single-psyem-participants .type-psyem-participants{margin-top: 200px;}            
        </style>';
    }

    function psyem_RegisterEventsManagerFrontScripts()
    {
        // js
        wp_register_script(PSYEM_PREFIX . 'bootstrap5frntjs',   PSYEM_ASSETS . '/js/bootstrap.min.js', array('jquery'), PSYEM_VERSION, true);
        wp_register_script(PSYEM_PREFIX . 'select2frntjs',   PSYEM_ASSETS . '/libs/select2/select2.full.min.js', array('jquery'), PSYEM_VERSION, true);
        wp_register_script(PSYEM_PREFIX . 'toasterfrntjs',   PSYEM_ASSETS . '/libs/toastr/toastr.js', array('jquery'), PSYEM_VERSION, true);
        wp_register_script(PSYEM_PREFIX . 'swal2frntjs',   PSYEM_ASSETS . '/libs/swal2/sweetalert2.js', array('jquery'), PSYEM_VERSION, true);
        wp_register_script(PSYEM_PREFIX . 'helperfrntjs', PSYEM_ASSETS . '/js/psyemHelpers.js', array('jquery'), time(), true);
        wp_register_script(PSYEM_PREFIX . 'eventdetailsfrntjs', PSYEM_ASSETS . '/js/psyemFrontEventDetails.js', array('jquery'), time(), true);
        wp_register_script(PSYEM_PREFIX . 'projectsafefrntjs', PSYEM_ASSETS . '/js/psyemFrontProjectsafe.js', array('jquery'), time(), true);
        wp_register_script(PSYEM_PREFIX . 'checkoutfrntjs', PSYEM_ASSETS . '/js/psyemCheckout.js', array('jquery'), time(), true);
        wp_register_script(PSYEM_PREFIX . 'donationfrntjs', PSYEM_ASSETS . '/js/psyemFrontDonation.js', array('jquery'), time(), true);
        wp_register_script(PSYEM_PREFIX . 'psyemlistingfrntjs', PSYEM_ASSETS . '/js/psyemPostsListing.js', array('jquery'), time(), true);
        wp_register_script(PSYEM_PREFIX . 'eventslistfrntjs', PSYEM_ASSETS . '/js/psyemEventsList.js', array('jquery'), time(), true);
        wp_register_script(PSYEM_PREFIX . 'eventverifyqrfrntjs', PSYEM_ASSETS . '/js/psyemOrderVerifyQr.js', array('jquery'), time(), true);
        wp_register_script(PSYEM_PREFIX . 'orderthankyoufrntjs', PSYEM_ASSETS . '/js/psyemOrderThankyou.js', array('jquery'), time(), true);

        wp_register_script(PSYEM_PREFIX . 'selectizefrntjs',   PSYEM_ASSETS . '/libs/selectize/selectize.min.js', array('jquery'), PSYEM_VERSION, true);
        wp_register_script(PSYEM_PREFIX . 'stripefrntjs', 'https://js.stripe.com/v3/', array('jquery'), PSYEM_VERSION, true);
        // css
        wp_register_style(PSYEM_PREFIX . 'bootstrap5frntcss',   PSYEM_ASSETS . '/css/bootstrap.min.css', array(), PSYEM_VERSION);
        wp_register_style(PSYEM_PREFIX . 'select2frntcss',   PSYEM_ASSETS . '/libs/select2/select2.min.css', array(), PSYEM_VERSION);
        wp_register_style(PSYEM_PREFIX . 'toasterfrntcss',   PSYEM_ASSETS . '/libs/toastr/toastr.css', array(), PSYEM_VERSION);
        wp_register_style(PSYEM_PREFIX . 'swal2frntcss',   PSYEM_ASSETS . '/libs/swal2/sweetalert2.css', array(), PSYEM_VERSION);
        wp_register_style(PSYEM_PREFIX . 'helperfrntcss',   PSYEM_ASSETS . '/css/psyemHelpers.css', array(), time());
        wp_register_style(PSYEM_PREFIX . 'eventslistfrntcss',   PSYEM_ASSETS . '/css/psyemEventsList.css', array(), time());
        wp_register_style(PSYEM_PREFIX . 'projectsafefrntcss',   PSYEM_ASSETS . '/css/psyemFrontProjectsafe.css', array(), time());
        wp_register_style(PSYEM_PREFIX . 'donationfrntcss',   PSYEM_ASSETS . '/css/psyemFrontDonation.css', array(), time());
        wp_register_style(PSYEM_PREFIX . 'psyemlistingfrntcss',   PSYEM_ASSETS . '/css/psyemPostsListing.css', array(), time());
    }

    function psyem_EnqueueEventsManagerFrontScripts()
    {


        if (is_singular('psyem-events')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5frntjs');
            wp_enqueue_script(PSYEM_PREFIX . 'select2frntjs');
            wp_enqueue_script(PSYEM_PREFIX . 'toasterfrntjs');
            wp_enqueue_script(PSYEM_PREFIX . 'swal2frntjs');
            wp_enqueue_script(PSYEM_PREFIX . 'helperfrntjs');

            wp_localize_script(PSYEM_PREFIX . 'eventdetailsfrntjs', 'psyem_cart_ajax', array(
                'cart_ajaxurl'           => admin_url('admin-ajax.php'),
                'cart_nonce'             => esc_attr(wp_create_nonce('_nonce')),
                'cart_price_action'      => PSYEM_PREFIX . 'manage_cart_prices',
                'server_error'           => esc_html('Something went wrong with server end, Please try later', 'psyeventsmanager')
            ));
            wp_enqueue_script(PSYEM_PREFIX . 'eventdetailsfrntjs');

            wp_enqueue_style('dashicons');
            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5frntcss');
            wp_enqueue_style(PSYEM_PREFIX . 'select2frntcss');
            wp_enqueue_style(PSYEM_PREFIX . 'toasterfrntcss');
            wp_enqueue_style(PSYEM_PREFIX . 'swal2frntcss');
            wp_enqueue_style(PSYEM_PREFIX . 'helperfrntcss');
            wp_enqueue_style(PSYEM_PREFIX . 'eventslistfrntcss');
        }

        if (is_singular('psyem-partners')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5frntjs');

            wp_enqueue_style('dashicons');
            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5frntcss');
            wp_enqueue_style(PSYEM_PREFIX . 'helperfrntcss');
            wp_enqueue_style(PSYEM_PREFIX . 'eventslistfrntcss');
        }

        if (is_singular('psyem-speakers')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5frntjs');

            wp_enqueue_style('dashicons');
            wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5frntcss');
            wp_enqueue_style(PSYEM_PREFIX . 'helperfrntcss');
            wp_enqueue_style(PSYEM_PREFIX . 'eventslistfrntcss');
        }
    }

    function psyem_AssignFrontPageTemplates($page_template)
    {

        if (is_singular('psyem-events')) {
            $page_template = PSYEM_PATH . 'front/pages/psyemEventDetails.php';
        }

        if (is_singular('psyem-partners')) {
            $page_template = PSYEM_PATH . 'front/pages/psyemPartnerDetails.php';
        }

        if (is_singular('psyem-speakers')) {
            $page_template = PSYEM_PATH . 'front/pages/psyemSpeakerDetails.php';
        }

        return $page_template;
    }

    function psyem_ManageEventOrderCartPricesAjax()
    {
        global $wpdb;
        $postData           = $this->REQ;

        $CheckoutTickets    = @$postData['checkout_tickets'];
        $CheckoutCoupon     = $postData['checkout_coupon'];
        $EventIdEnc         = $postData['checkout_key'];
        $CheckoutSource     = $postData['checkout_source'];
        $EventId            = (!empty($EventIdEnc)) ? psyem_safe_b64decode_id($EventIdEnc) : 0;
        $psyemEventInfo     = psyem_GetSinglePostWithMetaPrefix('psyem-events', $EventId, 'psyem_event_');

        $resp      = array(
            'status'     => 'error',
            'message'    => __('Event ticket price has been failed to calculate', 'psyeventsmanager'),
            'validation' => [],
            'data'       => []
        );

        $isvalid                = psyem_ValidateEventOrderCartPriceCalculationData($postData);
        if (!empty($isvalid)) {
            $resp['validation'] = $isvalid;
            wp_send_json($resp, 200);
        }

        $paymentPrices = psyem_GetEventCheckoutPrices($CheckoutTickets, $psyemEventInfo, $CheckoutCoupon);
        $resp['data']  = $paymentPrices;

        if (!empty($psyemEventInfo)) {
            $resp      = array(
                'status'     => 'success',
                'message'    => __('Event ticket price calculated', 'psyeventsmanager'),
                'validation' => [],
                'data'       => $paymentPrices
            );
        }
        wp_send_json($resp, 200);
    }

    function psyem_ManageEventOrderStripeIntentAjax()
    {

        global $wpdb;
        $postData  = $this->REQ;

        $resp      = array(
            'status'     => 'error',
            'message'    => __('Payment intent has been failed to process', 'psyeventsmanager'),
            'validation' => [],
            'data'       => array('clientSecret' => '', 'PaymentIntentId' => '')
        );

        $isvalid            = psyem_ValidateEventOrderIntentData($postData);
        if (!empty($isvalid)) {
            $resp['validation'] = $isvalid;
            wp_send_json($resp, 200);
        }

        $CurrencyType                   = psyem_GetCurrenyType();
        $psyem_options                  = psyem_GetOptionsWithPrefix();
        $currency_exchange_rate         = @$psyem_options['psyem_currency_exchange_rate'];

        $CheckoutName       = @$postData['checkout_name'];
        $CheckoutEmail      = @$postData['checkout_email'];
        $CheckoutCoupon     = @$postData['checkout_coupon'];
        $CheckoutCompany    = @$postData['checkout_company'];

        $EventIdEnc         = @$postData['checkout_key'];
        $EventId            = (!empty($EventIdEnc)) ? psyem_safe_b64decode_id($EventIdEnc) : 0;
        $psyemEventInfo     = psyem_GetSinglePostWithMetaPrefix('psyem-events', $EventId, 'psyem_event_');
        $psyemEventMeta     = @$psyemEventInfo['meta_data'];

        $ChekoutPrices       = (isset($_SESSION[$EventIdEnc])) ? $_SESSION[$EventIdEnc] :  [];
        $ChekoutPrices       = psyem_UnsetCartCheckoutData($ChekoutPrices);

        $isBookingAllowed   = psyem_IsEventBookingAllowed(0, $psyemEventInfo);
        if ($isBookingAllowed != 'Yes') {
            $resp      = array(
                'status'     => 'error',
                'message'    => __('Booking is not allowed for this event', 'psyeventsmanager'),
                'validation' => [],
                'data'       => array('clientSecret' => '', 'PaymentIntentId' => '')
            );
            wp_send_json($resp, 200);
        }

        if (!empty($psyemEventInfo) && !empty($ChekoutPrices)) {

            $checkoutPrice = @$ChekoutPrices['checkout_price'];
            $totalPrice    = psyem_roundPrecision(($checkoutPrice));
            if ($totalPrice > 1) {
                $payable_amount               = psyem_FloatToInt($totalPrice);

                if ($payable_amount > 1) {
                    $psyem_options            = psyem_GetOptionsWithPrefix();
                    $psyem_stripe_publish_key = @$psyem_options['psyem_stripe_publish_key'];
                    $psyem_stripe_secret_key  = @$psyem_options['psyem_stripe_secret_key'];

                    $metadata               = array(
                        'participant_company' => $CheckoutCompany,
                        'participant_email'   => $CheckoutEmail,
                        'participant_name'    => $CheckoutName,
                        'checkout_price'      => $totalPrice,
                        'checkout_event'      => $EventId,
                        'checkout_coupon'     => $CheckoutCoupon,
                        'payment_source'      => 'psyem_event_order'
                    );

                    $metadata = array_merge($metadata, $ChekoutPrices);
                    // meta data key must be string
                    $metadata['coupon_data'] = (!empty($metadata['coupon_data'])) ? json_encode($metadata['coupon_data']) : '';
                    $metadata['cart_data']   = (!empty($metadata['cart_data'])) ? json_encode($metadata['cart_data']) : '';

                    if (!empty($psyem_stripe_secret_key)) {
                        try {
                            Stripe::setApiKey($psyem_stripe_secret_key);

                            $paymentIntent = \Stripe\PaymentIntent::create([
                                'amount' => $payable_amount,
                                'currency' => strtolower($CurrencyType),
                                'payment_method_types' => [
                                    "card"
                                ],
                                'metadata' => $metadata
                            ]);

                            $PaymentIntentId =  @$paymentIntent->id;
                            $clientSecret    =  @$paymentIntent->client_secret;

                            $resp  = array(
                                'status'     => 'success',
                                'message'    => __('Payment intent created', 'psyeventsmanager'),
                                'validation' => [],
                                'data'       => array('clientSecret' =>  $clientSecret, 'PaymentIntentId' => $PaymentIntentId)
                            );
                        } catch (\Exception $e) {
                            error_log('ManageEventOrderStripeIntentAjax  ERROR :: ' . $e->getMessage());
                            $resp      = array(
                                'status'     => 'error',
                                'message'    => $e->getMessage(),
                                'validation' => [],
                                'data'       => array('clientSecret' => '', 'PaymentIntentId' => '')
                            );
                        }
                    }
                }
            }
        }
        wp_send_json($resp, 200);
    }

    function psyem_ManageEventOrderStripePaymentAjax()
    {

        global $wpdb;
        $postData  = $this->REQ;

        $resp      = array(
            'status'     => 'error',
            'message'    => __('Booking order has been failed to process', 'psyeventsmanager'),
            'validation' => [],
            'data'       => []
        );

        $isvalid            = psyem_ValidateEventOrderPaymentData($postData);
        if (!empty($isvalid)) {
            $resp['validation'] = $isvalid;
            wp_send_json($resp, 200);
        }

        $payment_intent_id           = @$postData['intent_id'];
        $payment_tripe_status        = @$postData['stripe_status'];
        $psyem_options               = psyem_GetOptionsWithPrefix();
        $psyem_stripe_publish_key    = @$psyem_options['psyem_stripe_publish_key'];
        $psyem_stripe_secret_key     = @$psyem_options['psyem_stripe_secret_key'];

        if (!empty($payment_intent_id) && !empty($psyem_stripe_secret_key) && !empty($payment_tripe_status)) {
            try {
                $stripe             = new \Stripe\StripeClient($psyem_stripe_secret_key);

                $payment_intent     = $stripe->paymentIntents->retrieve($payment_intent_id,  []);
                $payment_intent_body = null;
                if ($payment_intent) {
                    $payment_intent_body = $payment_intent->jsonSerialize();
                }

                $payment_intent_id  = (isset($payment_intent->id))                  ? $payment_intent->id : '';
                $client_secret      = (isset($payment_intent->client_secret))       ? $payment_intent->client_secret : '';
                $charge_id          = (isset($payment_intent->latest_charge))       ? $payment_intent->latest_charge : '';
                $payment_method     = (isset($payment_intent->payment_method))      ? $payment_intent->payment_method : '';
                $payment_status     = (isset($payment_intent->status))              ? $payment_intent->status : '';
                $receipt_email      = (isset($payment_intent->receipt_email))       ? $payment_intent->receipt_email : '';
                $amount             = (isset($payment_intent->amount))              ? $payment_intent->amount : '';
                $amount_received    = (isset($payment_intent->amount_received))     ? $payment_intent->amount_received : '';
                $created_timestamp  = (isset($payment_intent->created))             ? $payment_intent->created : '';

                $metadata           = (isset($payment_intent->metadata))            ? $payment_intent->metadata : '';
                $participant_company = (isset($metadata->participant_company))         ? $metadata->participant_company : '';
                $participant_email  = (isset($metadata->participant_email))         ? $metadata->participant_email : '';
                $participant_name   = (isset($metadata->participant_name))          ? $metadata->participant_name : '';
                $checkout_tickets   = (isset($metadata->checkout_tickets))          ? $metadata->checkout_tickets : ''; // 2
                $checkout_total     = (isset($metadata->total_price))               ? $metadata->total_price : ''; // 45.50
                $checkout_price     = (isset($metadata->checkout_price))            ? $metadata->checkout_price : ''; // 45.50
                $total_discount     = (isset($metadata->total_discount))            ? $metadata->total_discount : ''; // 45.50
                $checkout_event     = (isset($metadata->checkout_event))            ? $metadata->checkout_event : ''; // 19
                $checkout_coupon    = (isset($metadata->checkout_coupon))           ? $metadata->checkout_coupon : ''; // 19
                $payment_source     = (isset($metadata->payment_source))            ? $metadata->payment_source : ''; // psyem_event_order

                $coupon_data        = (isset($metadata->coupon_data))               ? $metadata->coupon_data : ''; // 19
                $coupon_data        = (!empty($coupon_data))                        ? json_decode($coupon_data, true) : []; // 19

                $cart_data        = (isset($metadata->cart_data))                   ? $metadata->cart_data : ''; // 19
                $cart_data        = (!empty($cart_data))                            ? json_decode($cart_data, true) : []; // 19

                $psyemEventInfo     = psyem_GetSinglePostWithMetaPrefix('psyem-events', $checkout_event, 'psyem_event_');

                if (!empty($payment_intent_id)) {
                    $presp              = $payment_status;
                    $dbStatus           = $payment_status;
                    switch ($payment_status) {
                        case "succeeded":
                            $presp = 'succeeded';
                            $dbStatus = 'Success';
                            break;
                        case "processing":
                            $presp = 'processing';
                            $dbStatus = 'Proccessed';
                            break;
                        case "requires_payment_method":
                            $presp = 'requires_payment_method';
                            $dbStatus = 'Failed';
                            break;
                        case "requires_action":
                            $presp = 'requires_action';
                            $dbStatus = 'Failed';
                            break;
                        default:
                            $presp = 'error';
                            $dbStatus = 'Failed';
                            break;
                    }

                    $current_time     = current_time('mysql');
                    $current_time_gmt = current_time('mysql', 1); // Get GMT time
                    // Create order
                    $orderPostContent = @$psyemEventInfo['title'] . ' ' . strtolower($psyemEventInfo['title']) . ' ' . $participant_name . ' ' . $participant_email . ' ' . strtolower($participant_name) . ' ' . strtolower($participant_email);

                    $orderTitle = ucfirst($participant_name) . ' - Event Order - ' . $checkout_event . ' -- ' . $dbStatus;
                    $post_data  = array(
                        'post_title'    => ucfirst($orderTitle),
                        'post_name'     => sanitize_title($orderTitle),
                        'post_status'   => 'publish',
                        'post_content'  => $orderPostContent,
                        'post_excerpt'  => @$psyemEventInfo['title'] . ' Order',
                        'post_type'     => 'psyem-orders',
                        'post_date'     => $current_time,
                        'post_date_gmt' => $current_time_gmt,
                    );
                    $insertOResp         = $wpdb->insert($wpdb->posts, $post_data);
                    $inserted_order_id  = @$wpdb->insert_id;

                    if ($inserted_order_id  > 0) {
                        $orderEnc =  psyem_safe_b64encode_id($inserted_order_id);
                        $orderPostContent = $orderEnc . ' ' . @$psyemEventInfo['title'] . ' ' . strtolower($psyemEventInfo['title']) . ' ' . $participant_name . ' ' . $participant_email . ' ' . strtolower($participant_name) . ' ' . strtolower($participant_email);

                        // Update the post
                        $orderTitle = ucfirst($participant_name)  . ' - ' . $dbStatus . ' - ' . $orderEnc;
                        $updated_post_data = array(
                            'ID'           => $inserted_order_id,
                            'post_title'   => ucfirst($orderTitle),
                            'post_name'    => sanitize_title($orderTitle),
                            'post_content'  => $orderPostContent,
                        );
                        wp_update_post($updated_post_data);

                        $psyemEventMeta     = @$psyemEventInfo['meta_data'];

                        // // update order meta 
                        update_post_meta($inserted_order_id, 'psyem_order_client_secret', $client_secret);
                        update_post_meta($inserted_order_id, 'psyem_order_intent_id', $payment_intent_id);
                        update_post_meta($inserted_order_id, 'psyem_order_charge_id', $charge_id);
                        update_post_meta($inserted_order_id, 'psyem_order_payment_method', $payment_method);
                        update_post_meta($inserted_order_id, 'psyem_order_payment_status', $dbStatus);
                        update_post_meta($inserted_order_id, 'psyem_order_receipt_email', $receipt_email);
                        update_post_meta($inserted_order_id, 'psyem_order_payment_datetime', $created_timestamp);
                        update_post_meta($inserted_order_id, 'psyem_order_payment_payload', $payment_intent_body);

                        update_post_meta($inserted_order_id, 'psyem_order_event_id', $checkout_event);
                        update_post_meta($inserted_order_id, 'psyem_order_participant_name', $participant_name);
                        update_post_meta($inserted_order_id, 'psyem_order_participant_email', $participant_email);

                        update_post_meta($inserted_order_id, 'psyem_order_checkout_amount', $checkout_price);
                        update_post_meta($inserted_order_id, 'psyem_order_total_amount', $checkout_total);
                        update_post_meta($inserted_order_id, 'psyem_order_stripe_amount', psyem_IntToFloat($amount));
                        update_post_meta($inserted_order_id, 'psyem_order_recieved_amount', psyem_IntToFloat($amount_received));
                        update_post_meta($inserted_order_id, 'psyem_order_payment_source', $payment_source);

                        $total_slots = psyem_GetOrderTotalSlotsFromStripe($cart_data);
                        update_post_meta($inserted_order_id, 'psyem_order_tickets_info', $cart_data);
                        update_post_meta($inserted_order_id, 'psyem_order_total_slots', $total_slots);
                        update_post_meta($inserted_order_id, 'psyem_order_used_slots', 0);
                        update_post_meta($inserted_order_id, 'psyem_order_used_slots_info', []);

                        update_post_meta($inserted_order_id, 'psyem_order_total_discount', $total_discount);
                        update_post_meta($inserted_order_id, 'psyem_order_coupon', $checkout_coupon);
                        update_post_meta($inserted_order_id, 'psyem_order_coupon_data', $coupon_data);

                        // check if exiting particpant
                        $participantPostContent = $orderEnc . ' ' . @$psyemEventInfo['title'] . ' ' . strtolower($psyemEventInfo['title']) . ' ' . $participant_name . ' ' . $participant_email . ' ' . strtolower($participant_name) . ' ' . strtolower($participant_email);

                        $participantInfo = psyem_getPostByMetakeyAndValue('psyem-participants', 'psyem_participant_email', $participant_email);
                        $participantId   = @$participantInfo['ID'];
                        $participantsArr = [];
                        if ($participantId > 0) {
                            $participantsArr = ['Main' =>  $participantId];
                            $updated_post_data = array(
                                'ID'           => $participantId,
                                'post_content' => $participantPostContent
                            );
                            wp_update_post($updated_post_data);
                            update_post_meta($participantId, 'psyem_participant_event_id', $checkout_event);
                        } else {
                            // create Main Participant
                            $post_data = array(
                                'post_title'    => ucfirst($participant_name),
                                'post_name'     => sanitize_title($participant_name),
                                'post_status'   => 'publish',
                                'post_type'     => 'psyem-participants',
                                'post_content'  => $participantPostContent,
                                'post_date'     => $current_time,
                                'post_date_gmt' => $current_time_gmt,
                            );
                            $insertPResp                = $wpdb->insert($wpdb->posts, $post_data);
                            $inserted_participant_id    = @$wpdb->insert_id;
                            if ($inserted_participant_id > 0) {
                                $pNameArr         = psyem_SplitFullName($participant_name);
                                $participantFName = @$pNameArr['first_name'];
                                $participantLName = @$pNameArr['last_name'];

                                update_post_meta($inserted_participant_id, 'psyem_participant_first_name', ucfirst($participantFName));
                                update_post_meta($inserted_participant_id, 'psyem_participant_last_name', ucfirst($participantLName));
                                update_post_meta($inserted_participant_id, 'psyem_participant_company', $participant_company);
                                update_post_meta($inserted_participant_id, 'psyem_participant_name', ucfirst($participant_name));
                                update_post_meta($inserted_participant_id, 'psyem_participant_email', strtolower($participant_email));
                                update_post_meta($inserted_participant_id, 'psyem_participant_type', 'Main');
                                update_post_meta($inserted_participant_id, 'psyem_participant_event_id', $checkout_event);
                                $participantsArr = ['Main' => $inserted_participant_id];
                            }
                            $participantId    = @$inserted_participant_id;
                        }
                        // update order particpant
                        update_post_meta($inserted_order_id, 'psyem_order_participants', $participantsArr);
                        // update event slots count
                        psyem_UpdateEventSlotsCount($psyemEventInfo, $total_slots, 0);


                        // send email
                        if ($dbStatus == 'Success') {
                            // psyem_SendEventOrderBookingEmail($checkout_event, $inserted_order_id, $participantId);
                        }
                        $resp      = array(
                            'status'     => 'success',
                            'message'    => __('Booking order has been successfully proccessed', 'psyeventsmanager'),
                            'validation' => [],
                            'data'       => [
                                'order_enc'      => $orderEnc,
                                'order_id'       => $inserted_order_id,
                                'participant_id' => $participantId,
                            ]
                        );
                    }
                }
            } catch (\Exception $e) {
                error_log('ManageEventOrderStripePaymentAjax  ERROR :: ' . $e->getMessage());
            }
        } else {
            $resp      = array(
                'status'     => 'error',
                'message'    => __('Booking order payment intent is empty', 'psyeventsmanager'),
                'validation' => [],
                'data'       => []
            );
        }
        wp_send_json($resp, 200);
    }

    function psyem_ManageEventOrderFreeBookingAjax()
    {
        global $wpdb;
        $postData  = $this->REQ;

        $resp      = array(
            'status'     => 'error',
            'message'    => __('Booking order has been failed to process', 'psyeventsmanager'),
            'validation' => [],
            'data'       => []
        );

        $isvalid            = psyem_ValidateEventOrderFreeBookingData($postData);
        if (!empty($isvalid)) {
            $resp['validation'] = $isvalid;
            wp_send_json($resp, 200);
        }

        $checkout_tickets   = @$postData['checkout_tickets'];
        $participant_name   = @$postData['checkout_name'];
        $participant_email  = @$postData['checkout_email'];
        $checkout_company   = @$postData['checkout_company'];
        $registration_type  = @$postData['registration_type'];

        $EventIdEnc         = @$postData['checkout_key'];
        $EventId            = (!empty($EventIdEnc)) ? psyem_safe_b64decode_id($EventIdEnc) : 0;
        $psyemEventInfo     = psyem_GetSinglePostWithMetaPrefix('psyem-events', $EventId, 'psyem_event_');
        $psyemEventMeta     = @$psyemEventInfo['meta_data'];

        $isBookingAllowed   = psyem_IsEventBookingAllowed(0, $psyemEventInfo);
        if ($isBookingAllowed != 'Yes') {
            $resp      = array(
                'status'     => 'error',
                'message'    => __('Booking is not allowed for this event', 'psyeventsmanager'),
                'validation' => [],
                'data'       => []
            );
            wp_send_json($resp, 200);
        }



        if (!empty($psyemEventInfo) && !empty($psyemEventMeta)) {
            try {
                $dbStatus         = 'Free';
                $current_time     = current_time('mysql');
                $current_time_gmt = current_time('mysql', 1); // Get GMT time
                // Create order
                $orderPostContent = @$psyemEventInfo['title'] . ' ' . strtolower($psyemEventInfo['title']) . ' ' . $participant_name . ' ' . $participant_email . ' ' . strtolower($participant_name) . ' ' . strtolower($participant_email);


                $orderTitle = ucfirst($participant_name) . ' - Event Order - ' . $EventId . ' -- ' . $dbStatus;
                $post_data  = array(
                    'post_title'    => ucfirst($orderTitle),
                    'post_name'     => sanitize_title($orderTitle),
                    'post_status'   => 'publish',
                    'post_content'   => $orderPostContent,
                    'post_excerpt'  => @$psyemEventInfo['title'] . ' Order',
                    'post_type'     => 'psyem-orders',
                    'post_date'     => $current_time,
                    'post_date_gmt' => $current_time_gmt,
                );
                $insertOResp         = $wpdb->insert($wpdb->posts, $post_data);
                $inserted_order_id   = @$wpdb->insert_id;

                if ($inserted_order_id  > 0) {
                    $orderEnc =  psyem_safe_b64encode_id($inserted_order_id);
                    $orderPostContent = $orderEnc . ' ' . @$psyemEventInfo['title'] . ' ' . strtolower($psyemEventInfo['title']) . ' ' . $participant_name . ' ' . $participant_email . ' ' . strtolower($participant_name) . ' ' . strtolower($participant_email);

                    // Update the post
                    $orderTitle = ucfirst($participant_name)  . ' - ' . $dbStatus . ' - ' . $orderEnc;
                    $updated_post_data = array(
                        'ID'           => $inserted_order_id,
                        'post_title'   => ucfirst($orderTitle),
                        'post_name'    => sanitize_title($orderTitle),
                        'post_content'  => $orderPostContent,
                    );
                    wp_update_post($updated_post_data);

                    // // update order meta 
                    update_post_meta($inserted_order_id, 'psyem_order_client_secret', '');
                    update_post_meta($inserted_order_id, 'psyem_order_intent_id', '');
                    update_post_meta($inserted_order_id, 'psyem_order_charge_id', '');
                    update_post_meta($inserted_order_id, 'psyem_order_payment_method',  $dbStatus);
                    update_post_meta($inserted_order_id, 'psyem_order_payment_status', $dbStatus);
                    update_post_meta($inserted_order_id, 'psyem_order_receipt_email', '');
                    update_post_meta($inserted_order_id, 'psyem_order_payment_datetime', strtotime($current_time));
                    update_post_meta($inserted_order_id, 'psyem_order_payment_payload', []);

                    update_post_meta($inserted_order_id, 'psyem_order_event_id', $EventId);
                    update_post_meta($inserted_order_id, 'psyem_order_participant_name', $participant_name);
                    update_post_meta($inserted_order_id, 'psyem_order_participant_email', $participant_email);

                    update_post_meta($inserted_order_id, 'psyem_order_checkout_amount', 0.00);
                    update_post_meta($inserted_order_id, 'psyem_order_total_amount', 0.00);
                    update_post_meta($inserted_order_id, 'psyem_order_stripe_amount', 0.00);
                    update_post_meta($inserted_order_id, 'psyem_order_recieved_amount', 0.00);
                    update_post_meta($inserted_order_id, 'psyem_order_payment_source', $dbStatus);

                    update_post_meta($inserted_order_id, 'psyem_order_total_slots', $checkout_tickets);
                    update_post_meta($inserted_order_id, 'psyem_order_used_slots', 0);
                    update_post_meta($inserted_order_id, 'psyem_order_used_slots_info', []);

                    update_post_meta($inserted_order_id, 'psyem_order_coupon', '');
                    update_post_meta($inserted_order_id, 'psyem_order_coupon_data', []);
                    
                    // Store registration type if provided
                    if ($registration_type == 'Invitation') {
                        update_post_meta($inserted_order_id, 'psyem_order_registration_type', 'Invitation');
                    }

                    // check if exiting particpant
                    $participantPostContent = $orderEnc . ' ' . @$psyemEventInfo['title'] . ' ' . strtolower($psyemEventInfo['title']) . ' ' . $participant_name . ' ' . $participant_email . ' ' . strtolower($participant_name) . ' ' . strtolower($participant_email);

                    $participantInfo = psyem_getPostByMetakeyAndValue('psyem-participants', 'psyem_participant_email', $participant_email);
                    $participantId   = @$participantInfo['ID'];
                    $participantsArr = [];
                    if ($participantId > 0) {
                        $participantsArr = ['Main' =>  $participantId];
                        $updated_post_data = array(
                            'ID'           => $participantId,
                            'post_content' => $participantPostContent
                        );
                        wp_update_post($updated_post_data);
                        update_post_meta($participantId, 'psyem_participant_event_id', $EventId);
                    } else {
                        // create Main Participant
                        $post_data = array(
                            'post_title'    => ucfirst($participant_name),
                            'post_name'     => sanitize_title($participant_name),
                            'post_status'   => 'publish',
                            'post_type'     => 'psyem-participants',
                            'post_content'  => $participantPostContent,
                            'post_date'     => $current_time,
                            'post_date_gmt' => $current_time_gmt,
                        );
                        $insertPResp                = $wpdb->insert($wpdb->posts, $post_data);
                        $inserted_participant_id    = @$wpdb->insert_id;
                        if ($inserted_participant_id > 0) {
                            $pNameArr = psyem_SplitFullName($participant_name);
                            $participantFName = @$pNameArr['first_name'];
                            $participantLName = @$pNameArr['last_name'];

                            update_post_meta($inserted_participant_id, 'psyem_participant_first_name', ucfirst($participantFName));
                            update_post_meta($inserted_participant_id, 'psyem_participant_last_name', ucfirst($participantLName));
                            update_post_meta($inserted_participant_id, 'psyem_participant_company', $checkout_company);
                            update_post_meta($inserted_participant_id, 'psyem_participant_name', ucfirst($participant_name));
                            update_post_meta($inserted_participant_id, 'psyem_participant_email', strtolower($participant_email));
                            update_post_meta($inserted_participant_id, 'psyem_participant_type', 'Main');
                            update_post_meta($inserted_participant_id, 'psyem_participant_event_id', $EventId);
                            $participantsArr = ['Main' => $inserted_participant_id];
                        }
                        $participantId    = @$inserted_participant_id;
                    }
                    // update order particpant
                    update_post_meta($inserted_order_id, 'psyem_order_participants', $participantsArr);
                    // update event slots count
                    psyem_UpdateEventSlotsCount($psyemEventInfo, $checkout_tickets, 0);

                    // send email
                    psyem_SendEventOrderBookingEmail($EventId, $inserted_order_id, $participantId);

                    $resp      = array(
                        'status'     => 'success',
                        'message'    => __('Booking order has been successfully proccessed', 'psyeventsmanager'),
                        'validation' => [],
                        'data'       => [
                            'order_enc'      => $orderEnc,
                            'order_id'       => $inserted_order_id,
                            'participant_id' => $participantId,
                        ]
                    );
                }
            } catch (\Exception $e) {
                error_log('ManageEventOrderStripePaymentAjax  ERROR :: ' . $e->getMessage());
            }
        } else {
            $resp      = array(
                'status'     => 'error',
                'message'    => __('Booking order failed to process', 'psyeventsmanager'),
                'validation' => [],
                'data'       => []
            );
        }
        wp_send_json($resp, 200);
    }

    function psyem_CustomPostSearchQuery($search, $wp_query)
    {
        global $wpdb;

        $search_term    = $wp_query->get('search_term');
        if (empty($search_term)) {
            return $search;
        }

        $post_type              = @$wp_query->query['post_type'];
        $search_term_like       = sanitize_text_field($search_term);
        $search_term_like       = $wpdb->esc_like($search_term_like);
        $search_term_like       = '%' . $search_term_like . '%';

        $table_prefix = $wpdb->prefix;
        $table_posts  = $table_prefix . 'posts';

        if ($post_type == 'psyem-events') {
            $search = $wpdb->prepare(
                " AND (
                    LOWER(" . $table_posts . ".post_title) LIKE LOWER(%s) OR 
                    LOWER(" . $table_posts . ".post_excerpt) LIKE LOWER(%s) OR 
                    LOWER(" . $table_posts . ".post_content) LIKE LOWER(%s)
                ) ",
                $search_term_like,
                $search_term_like,
                $search_term_like
            );
        }

        if ($post_type == 'psyem-knowledges') {
            $search = $wpdb->prepare(
                " AND (
                    LOWER(" . $table_posts . ".post_title) LIKE LOWER(%s) OR 
                    LOWER(" . $table_posts . ".post_excerpt) LIKE LOWER(%s) OR 
                    LOWER(" . $table_posts . ".post_content) LIKE LOWER(%s)
                ) ",
                $search_term_like,
                $search_term_like,
                $search_term_like
            );
        }

        if ($post_type == 'psyem-news') {
            $search = $wpdb->prepare(
                " AND (
                    LOWER(" . $table_posts . ".post_title) LIKE LOWER(%s) OR 
                    LOWER(" . $table_posts . ".post_excerpt) LIKE LOWER(%s) OR 
                    LOWER(" . $table_posts . ".post_content) LIKE LOWER(%s)
                ) ",
                $search_term_like,
                $search_term_like,
                $search_term_like
            );
        }

        if ($post_type == 'psyem-programmes') {
            $search = $wpdb->prepare(
                " AND (
                    LOWER(" . $table_posts . ".post_title) LIKE LOWER(%s) OR 
                    LOWER(" . $table_posts . ".post_excerpt) LIKE LOWER(%s) OR 
                    LOWER(" . $table_posts . ".post_content) LIKE LOWER(%s)
                ) ",
                $search_term_like,
                $search_term_like,
                $search_term_like
            );
        }
        return $search;
    }

    /* PSYEM APIS - BGN */

    function psyem_RegisterRestApiRoutes()
    {

        register_rest_route('psyeventsmanager/v1', '/event/list', array(
            'methods'  => 'GET',
            'callback' => array(&$this, PSYEM_PREFIX . 'GetEventsList'),
            'args' => array(
                'user_token' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        return psyem_ValidateApiAuthToken($param);
                    }
                ),
                'from_date' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        // Validate date in format "YYYY-MM-DD"
                        if (!empty($param)) {
                            return preg_match('/^\d{4}-\d{2}-\d{2}$/', $param) === 1;
                        }
                        return true;
                    }
                ),
                'to_date' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        // Validate date in format "YYYY-MM-DD"
                        if (!empty($param)) {
                            return preg_match('/^\d{4}-\d{2}-\d{2}$/', $param) === 1;
                        }
                        return true;
                    }
                ),
                'search_key' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        // Validate search string (alphanumeric and spaces)
                        if (!empty($param)) {
                            return preg_match('/^[a-zA-Z0-9\s]+$/', $param) === 1;
                        }
                        return true;
                    }
                ),
                'event_type' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        // Validate enum "Paid" or "Free"
                        if (!empty($param)) {
                            return in_array($param, ['Paid', 'Free']);
                        }
                        return true;
                    }
                ),
            ),
            'permission_callback' => '__return_true',
        ));

        register_rest_route('psyeventsmanager/v1', '/event/detail', array(
            'methods'  => 'GET',
            'callback' => array(&$this, PSYEM_PREFIX . 'GetEventDetails'),
            'args' => array(
                'user_token' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        return psyem_ValidateApiAuthToken($param);
                    }
                ),
                'id' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        return (is_numeric($param) && $param > 0);
                    }
                )
            ),
            'permission_callback' => '__return_true',
        ));

        register_rest_route('psyeventsmanager/v1', '/login', array(
            'methods'  => 'POST',
            'callback' => array(&$this, PSYEM_PREFIX . 'GetLoginAccessToken'),
            'args' => array(
                'wp_username' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        return (!empty($param));
                    }
                ),
                'wp_password' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        return (!empty($param));
                    }
                )
            ),
            'permission_callback' => '__return_true',
        ));

        register_rest_route('psyeventsmanager/v1', '/logout', array(
            'methods'  => 'POST',
            'callback' => array(&$this, PSYEM_PREFIX . 'RemoveLoginAccessToken'),
            'args' => array(
                'user_token' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        return psyem_ValidateApiAuthToken($param);
                    }
                ),
                'user_id' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        return (!empty($param) && is_numeric($param) && $param > 0);
                    }
                )
            ),
            'permission_callback' => '__return_true',
        ));
    }

    function psyem_GetEventsList(WP_REST_Request $request)
    {
        $loginUID     = (is_user_logged_in() &&  get_current_user_id() > 0) ? get_current_user_id()  : 0;
        $parameters   = $request->get_params(); // ? all query params       
        $resp         = array('status' => 'error', 'message' => __('Events data not found', 'psyeventsmanager'), 'data' => [], 'pagination' => []);

        $psyemEvents    = psyem_GetAllEventsForForApi($parameters);
        if (!empty($psyemEvents)) {
            $resp     = array('status' => 'success', 'message' => __('Events data found', 'psyeventsmanager'));
            $resp     = array_merge($resp, $psyemEvents);
        }

        $response = new WP_REST_Response($resp);
        $response->set_status(200);
        return $response;
    }

    function psyem_GetEventDetails(WP_REST_Request $request)
    {
        $loginUID     = (is_user_logged_in() &&  get_current_user_id() > 0) ? get_current_user_id()  : 0;
        $parameters   = $request->get_params(); // ? all query params
        $event_id     = $request->get_param('id');

        $resp         = array('status' => 'error', 'message' => __('Event data not found', 'psyeventsmanager'), 'data' => []);
        if ($event_id > 0) {
            $psyemEvent   = psyem_GetSinglePostWithMetaPrefixForApi('psyem-events', $event_id, 'psyem_event_');
            if (!empty($psyemEvent)) {
                $resp     = array('status' => 'success', 'message' => __('Event data found', 'psyeventsmanager'), 'data' => $psyemEvent);
            }
        }

        $response = new WP_REST_Response($resp);
        $response->set_status(200);
        return $response;
    }

    function psyem_GetLoginAccessToken(WP_REST_Request $request)
    {

        $resp            = array('status' => 'error', 'message' => __('Login failed', 'psyeventsmanager'), 'data' => []);
        $parameters      = $request->get_params();
        $wp_username     = $request->get_param('wp_username');
        $wp_password     = $request->get_param('wp_password');

        $logUser         = wp_authenticate($wp_username, $wp_password);
        if (is_wp_error($logUser)) {
            $resp['message'] = 'Invalid username or password.';
            $response = new WP_REST_Response($resp);
            $response->set_status(200);
            return $response;
        }

        $UID             = @$logUser->ID;
        if ($UID > 0) {
            $token          = wp_generate_password(32, false);
            $enckey         = psyem_safe_b64encode_id($UID);
            $logToken       = $token . '__wpu__' . $enckey;
            $current_time   = current_time('mysql');
            update_user_meta($UID, 'psyem_user_auth_token', $logToken);
            update_user_meta($UID, 'psyem_user_auth_time', $current_time);
            $userData = array(
                'user_id'       => @$UID,
                'user_login'    => @$logUser->user_login,
                'user_nicename' => @$logUser->user_nicename,
                'user_email'    => @$logUser->user_email,
                'display_name'  => @$logUser->display_name,
                'user_token'    => @$logToken,
            );
            $resp            = array('status' => 'success', 'message' => __('Login successful', 'psyeventsmanager'), 'data' => $userData);
        }
        $response = new WP_REST_Response($resp);
        $response->set_status(200);
        return $response;
    }

    function psyem_RemoveLoginAccessToken(WP_REST_Request $request)
    {

        $resp    = array('status' => 'error', 'message' => __('Logout failed', 'psyeventsmanager'), 'data' => []);
        $user_id = $request->get_param('user_id');
        $user_token = $request->get_param('user_token');

        if (empty($user_id)) {
            $resp['message'] = 'User ID is required.';
            $response = new WP_REST_Response($resp);
            $response->set_status(200);
            return $response;
        }

        $psyem_user_auth_token = get_user_meta($user_id, 'psyem_user_auth_token', true);
        if (!empty($psyem_user_auth_token) && $psyem_user_auth_token == $user_token) {
            delete_user_meta($user_id, 'psyem_user_auth_token'); // Remove token
            delete_user_meta($user_id, 'psyem_user_auth_time'); // Remove time
            $psyem_user_auth_token = get_user_meta($user_id, 'psyem_user_auth_token', true);

            if (empty($psyem_user_auth_token)) {
                $resp            = array('status' => 'success', 'message' => __('Logout successful', 'psyeventsmanager'), 'data' => []);
            }
        }

        $response = new WP_REST_Response($resp);
        $response->set_status(200);
        return $response;
    }
    /* PSYEM APIS - END */

    /* PSYEM SHORTCODES - BGN */

    function psyem_ManageAllShortcodes()
    {
        add_shortcode('psyem-projectsafe-form',     array(&$this, PSYEM_PREFIX . 'ManageProjectsafeFormShortcode'));
        add_shortcode('psyem-donation-form',        array(&$this, PSYEM_PREFIX . 'ManageDonationFormShortcode'));
        add_shortcode('psyem-donation-onetime',     array(&$this, PSYEM_PREFIX . 'ManageDonationOnetimeFormShortcode'));
        add_shortcode('psyem-donation-checkout',    array(&$this, PSYEM_PREFIX . 'ManageDonationCheckoutFormShortcode'));

        add_shortcode('psyem-knowledgehub-list',    array(&$this, PSYEM_PREFIX . 'ManageKnowledgehubListShortcode'));
        add_shortcode('psyem-programmes-list',      array(&$this, PSYEM_PREFIX . 'ManageProgrammesListShortcode'));
        add_shortcode('psyem-news-list',            array(&$this, PSYEM_PREFIX . 'ManageNewsListShortcode'));

        add_shortcode('psyem-events-list',          array(&$this, PSYEM_PREFIX . 'ManageEventsListShortcode'));
        add_shortcode('psyem-event-checkout',       array(&$this, PSYEM_PREFIX . 'ManageEventCheckoutShortcode'));
        add_shortcode('psyem-event-thankyou',       array(&$this, PSYEM_PREFIX . 'ManageEventOrderThankyouShortcode'));
        add_shortcode('psyem-event-order-verifyqr', array(&$this, PSYEM_PREFIX . 'ManageEventOrderVerifyqrShortcode'));

        add_filter('widget_text', 'shortcode_unautop');
        add_filter('widget_text', 'do_shortcode', 11);
    }

    /* Event shortcodes BGN */

    function psyem_EnqueueEventsShortcodeScripts($type = '')
    {

        wp_enqueue_script('jquery');
        wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5frntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'toasterfrntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'helperfrntjs');

        wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5frntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'toasterfrntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'helperfrntcss');

        if ($type == 'List') {
            wp_enqueue_script(PSYEM_PREFIX . 'eventslistfrntjs');
            wp_enqueue_style(PSYEM_PREFIX . 'eventslistfrntcss');
        }

        if ($type == 'Checkout') {
            wp_enqueue_script(PSYEM_PREFIX . 'stripefrntjs');
            $psyem_options                  = psyem_GetOptionsWithPrefix();
            $psyem_event_thankyou_page_id   = @$psyem_options['psyem_event_thankyou_page_id'];

            $psyem_stripe_publish_key       = @$psyem_options['psyem_stripe_publish_key'];
            wp_localize_script(PSYEM_PREFIX . 'checkoutfrntjs', 'psyem_order_ajax', array(
                'order_ajaxurl'             => admin_url('admin-ajax.php'),
                'order_nonce'               => esc_attr(wp_create_nonce('_nonce')),
                'order_price_action'        => PSYEM_PREFIX . 'manage_cart_prices',
                'order_intent_action'       => PSYEM_PREFIX . 'manage_stripe_intent',
                'order_save_action'         => PSYEM_PREFIX . 'manage_stripe_payment',
                'order_send_ticket_action'  => PSYEM_PREFIX . 'manage_order_send_tickets',
                'order_free_booking_action' => PSYEM_PREFIX . 'manage_free_booking',
                'order_thankou_url'         => psyem_GetPageLinkByID($psyem_event_thankyou_page_id),
                'stripe_public_key'         => $psyem_stripe_publish_key,
                'server_error'              => esc_html('Something went wrong with server end, Please try later', 'psyeventsmanager')
            ));
            wp_enqueue_script(PSYEM_PREFIX . 'checkoutfrntjs');
        }

        if ($type == 'Thankyou') {
            wp_enqueue_script(PSYEM_PREFIX . 'orderthankyoufrntjs');
        }
    }

    function psyem_EnqueueOrderVerifyqrShortcodeScripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5frntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'helperfrntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'eventverifyqrfrntjs');

        wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5frntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'helperfrntcss');
    }

    function psyem_ManageEventsListShortcode($atts)
    {
        global $wpdb;
        $args = shortcode_atts(
            array('type' => ''),
            $atts,
            'psyem-events-list'
        );

        $this->psyem_EnqueueEventsShortcodeScripts('List');

        $shortcodeOutput = '';
        $pfFilePath = PSYEM_PATH . 'front/pages/psyemEventsListShortcode.php';
        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
            $shortcodeOutput .= require $pfFilePath;
        }

        return $shortcodeOutput;
    }

    function psyem_ManageEventCheckoutShortcode($atts)
    {
        global $wpdb;
        $args = shortcode_atts(
            array('type' => ''),
            $atts,
            'psyem-event-checkout'
        );

        $this->psyem_EnqueueEventsShortcodeScripts('Checkout');

        $shortcodeOutput = '';
        $pfFilePath = PSYEM_PATH . 'front/pages/psyemEventCheckoutShortcode.php';
        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
            $shortcodeOutput .= require $pfFilePath;
        }

        return $shortcodeOutput;
    }

    function psyem_ManageEventOrderThankyouShortcode($atts)
    {
        global $wpdb;
        $args = shortcode_atts(
            array('type' => ''),
            $atts,
            'psyem-event-thankyou'
        );

        $this->psyem_EnqueueEventsShortcodeScripts('Thankyou');

        $shortcodeOutput = '';
        $pfFilePath = PSYEM_PATH . 'front/pages/psyemEventThankyouShortcode.php';
        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
            $shortcodeOutput .= require $pfFilePath;
        }

        return $shortcodeOutput;
    }

    function psyem_ManageEventOrderVerifyqrShortcode($atts)
    {
        global $wpdb;
        $args = shortcode_atts(
            array('type' => ''),
            $atts,
            'psyem-event-order-verifyqr'
        );
        $this->psyem_EnqueueOrderVerifyqrShortcodeScripts();


        $shortcodeOutput = '';
        $pfFilePath = PSYEM_PATH . 'front/pages/psyemOrderVerifyqrShortcode.php';
        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
            $shortcodeOutput .= require $pfFilePath;
        }

        return $shortcodeOutput;
    }

    /* Event shortcodes END */

    function psyem_EnqueueProjectsafeShortcodeScripts()
    {

        wp_enqueue_script('jquery');
        wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5frntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'select2frntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'toasterfrntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'swal2frntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'helperfrntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'selectizefrntjs');

        wp_localize_script(PSYEM_PREFIX . 'projectsafefrntjs', 'psyem_projectsafe', array(
            'projectsafe_ajaxurl'       => admin_url('admin-ajax.php'),
            'projectsafe_nonce'         => esc_attr(wp_create_nonce('_nonce')),
            'projectsafe_key'           => psyem_safe_b64encode(time()),
            'projectsafe_form_action'   => PSYEM_PREFIX . 'manage_projectsafe_form',
            'server_error'           => esc_html('Something went wrong with server end, Please try later', 'psyeventsmanager')
        ));

        wp_enqueue_script(PSYEM_PREFIX . 'projectsafefrntjs');
        wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5frntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'select2frntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'toasterfrntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'swal2frntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'helperfrntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'projectsafefrntcss');
    }

    function psyem_ManageProjectsafeFormShortcode($atts)
    {
        global $wpdb;
        $args = shortcode_atts(
            array('type' => 'project-safe'),
            $atts,
            'psyem-projectsafe-form'
        );

        $projectsafe_type = (isset($args['type']) && !empty($args['type'])) ? ($args['type']) : 'project-safe';

        $this->psyem_EnqueueProjectsafeShortcodeScripts();

        // reset seesion on page load
        $projectKey = 'ProjectSafe';
        $_SESSION[$projectKey]     = array();
        $psyem_options             = psyem_GetOptionsWithPrefix();

        $shortcodeOutput = '';
        $pfFilePath = PSYEM_PATH . 'front/pages/psyemProjectsafeForm.php';
        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
            $shortcodeOutput .= require $pfFilePath;
        }

        return $shortcodeOutput;
    }

    function psyem_ManageProjectsafeFormDataAjax()
    {

        global $wpdb;
        $postData  = $this->REQ;

        $resp      = array(
            'status'     => 'error',
            'message'    => __('Project safe request has been failed to process', 'psyeventsmanager'),
            'validation' => [],
            'data'       => []
        );

        $isvalid            = psyem_ValidateProjectSafeFormData($postData);
        if (!empty($isvalid)) {
            $resp['validation'] = $isvalid;
            wp_send_json($resp, 200);
        }

        $saveResp  = psyem_ManageProjectsafeFormData($postData);

        if (!empty($saveResp)) {
            $record_id = @$saveResp['record_id'];
            $mesgS = __('Project safe request has been sucessfully submitted', 'psyeventsmanager');
            $mesgI = __('Participant details are captured, Please submit contact informations', 'psyeventsmanager');
            $message   = ($record_id > 0) ? $mesgS : $mesgI;

            $resp      = array(
                'status'     => 'success',
                'message'    => $message,
                'validation' => [],
                'data'       => $saveResp
            );
        }
        wp_send_json($resp, 200);
    }

    function psyem_EnqueueDonationShortcodeScripts($params = [])
    {

        wp_enqueue_script('jquery');
        wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5frntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'toasterfrntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'swal2frntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'helperfrntjs');

        $localizeVars = array(
            'donation_ajaxurl'        => admin_url('admin-ajax.php'),
            'donation_nonce'          => esc_attr(wp_create_nonce('_nonce')),
            'donation_key'            => psyem_safe_b64encode(time()),
            'donation_amount_action'  => PSYEM_PREFIX . 'manage_donation_amounts',
            'donation_process_action' => PSYEM_PREFIX . 'manage_process_amounts',
            'donation_intent_action'  => PSYEM_PREFIX . 'manage_donation_intent',
            'donation_payment_action' => PSYEM_PREFIX . 'manage_donation_payment',
            'server_error'            => esc_html('Something went wrong with server end, Please try later', 'psyeventsmanager')
        );

        $psyem_options            = psyem_GetOptionsWithPrefix();
        $psyem_stripe_publish_key = @$psyem_options['psyem_stripe_publish_key'];
        $localizeVars['stripe_public_key'] = $psyem_stripe_publish_key;
        wp_enqueue_script(PSYEM_PREFIX . 'stripefrntjs');

        wp_localize_script(PSYEM_PREFIX . 'donationfrntjs', 'psyem_donation', $localizeVars);
        wp_enqueue_script(PSYEM_PREFIX . 'donationfrntjs');

        wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5frntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'toasterfrntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'swal2frntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'helperfrntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'donationfrntcss');
    }

    function psyem_ManageDonationFormShortcode($atts)
    {

        global $wpdb;
        $args = shortcode_atts(
            array('exclude' => "", 'include' => ""),
            $atts,
            'psyem-donation-form'
        );

        $exclude = (isset($args['exclude'])) ? ($args['exclude']) : '';
        $include = (isset($args['include'])) ? ($args['include']) : '';

        $this->psyem_EnqueueDonationShortcodeScripts(array('Donation'));

        // reset seesion on page load
        $projectKey                 = 'donation_cart';
        //$_SESSION[$projectKey]      = array();    

        $psyem_options                  = psyem_GetOptionsWithPrefix();
        $onetime_donation_page_id       = @$psyem_options['psyem_onetime_donation_page_id'];
        $onetime_donation_page_link     = psyem_GetPageLinkByID($onetime_donation_page_id);
        $onetime_donation_page_link     = (!empty($onetime_donation_page_link)) ? $onetime_donation_page_link : 'javascript:void(0);';

        $shortcodeOutput = '';
        $pfFilePath = PSYEM_PATH . 'front/pages/psyemDonationForm.php';
        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
            $shortcodeOutput .= require $pfFilePath;
        }

        return $shortcodeOutput;
    }

    function psyem_ManageDonationOnetimeFormShortcode($atts)
    {
        global $wpdb;
        $args = shortcode_atts(
            array('exclude' => "", 'include' => ""),
            $atts,
            'psyem-donation-onetime'
        );

        $exclude = (isset($args['exclude'])) ? ($args['exclude']) : '';
        $include = (isset($args['include'])) ? ($args['include']) : '';

        $this->psyem_EnqueueDonationShortcodeScripts(array('Onetime'));

        // reset seesion on page load
        $projectKey                 = 'donation_cart';
        //$_SESSION[$projectKey]      = array();    

        $psyem_options                  = psyem_GetOptionsWithPrefix();
        $onetime_donation_page_id       = @$psyem_options['psyem_onetime_donation_page_id'];
        $onetime_donation_page_link     = psyem_GetPageLinkByID($onetime_donation_page_id);
        $onetime_donation_page_link     = (!empty($onetime_donation_page_link)) ? $onetime_donation_page_link : 'javascript:void(0);';

        $shortcodeOutput = '';
        $pfFilePath = PSYEM_PATH . 'front/pages/psyemDonationOnetime.php';
        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
            $shortcodeOutput .= require $pfFilePath;
        }

        return $shortcodeOutput;
    }

    function psyem_ManageDonationCheckoutFormShortcode($atts)
    {
        global $wpdb;
        $args = shortcode_atts(
            array('exclude' => "", 'include' => ""),
            $atts,
            'psyem-donation-checkout'
        );

        $exclude = (isset($args['exclude'])) ? ($args['exclude']) : '';
        $include = (isset($args['include'])) ? ($args['include']) : '';

        $this->psyem_EnqueueDonationShortcodeScripts(array('Checkout'));

        // reset seesion on page load
        $projectKey                 = 'donation_cart';
        // $_SESSION[$projectKey]      = array();
        $psyem_options              = psyem_GetOptionsWithPrefix();
        $shortcodeOutput = '';
        $pfFilePath = PSYEM_PATH . 'front/pages/psyemDonationCheckout.php';
        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
            $shortcodeOutput .= require $pfFilePath;
        }

        return $shortcodeOutput;
    }
    /* PSYEM SHORTCODES - END */

    /* DONATION AJAX BGN */
    function psyem_ManageDonationAmountsDataAjax()
    {
        global $wpdb;
        $postData  = $this->REQ;

        $resp      = array(
            'status'     => 'error',
            'message'    => __('Donation amounts has been failed to fetch', 'psyeventsmanager'),
            'phtml'       => ''
        );

        $isvalid            = psyem_ValidateDonationAmountsData($postData);
        if (!empty($isvalid)) {
            $resp['validation'] = $isvalid;
            wp_send_json($resp, 200);
        }

        $amount_type      = @$postData['amount_type'];
        $DonationAmounts  = psyem_getPostsByMetakeyAndValueData('psyem-amounts', 'psyem_amount_type', $amount_type, '=', 'psyem_amount_');

        $sourceModalTitle = @$postData['modal_title'];

        if (!empty($DonationAmounts) && count($DonationAmounts)) {
            $ahtml = '';
            $pfFilePath = PSYEM_PATH . 'front/pages/psyemDonationAmountsModal.php';
            if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                $ahtml .= require $pfFilePath;
                $ahtml = trim($ahtml);
            }
            if (!empty($ahtml)) {
                $resp      = array(
                    'status'     => 'success',
                    'message'    => __('Donation amount records data found', 'psyeventsmanager'),
                    'phtml'      => $ahtml
                );
            }
        }
        wp_send_json($resp, 200);
    }

    function psyem_ManageDonationAmountsProcessAjax()
    {
        global $wpdb;
        $postData  = $this->REQ;

        $resp      = array(
            'status'     => 'error',
            'message'    => __('Donation cart amount process has been failed', 'psyeventsmanager'),
            'validation' => [],
            'redirectto' => ''
        );

        $isvalid            = psyem_ValidateDonationAmountProcessData($postData);
        if (!empty($isvalid)) {
            $resp['validation'] = $isvalid;
            wp_send_json($resp, 200);
        }

        unset($postData['action']);
        unset($postData['_nonce']);
        $sessKey = 'donation_cart';
        $_SESSION[$sessKey]     = $postData;

        $donationCart       = (isset($_SESSION[$sessKey])) ? $_SESSION[$sessKey] :  [];
        if (!empty($donationCart)) {
            $psyem_options               = psyem_GetOptionsWithPrefix();
            $donation_checkout_page_id   = @$psyem_options['psyem_donation_checkout_page_id'];
            $donation_checkout_page_link = psyem_GetPageLinkByID($donation_checkout_page_id);
            if (empty($donation_checkout_page_link)) {
                $resp      = array(
                    'status'     => 'error',
                    'message'    => __('Donation checkout page is not linked', 'psyeventsmanager'),
                    'validation' => [],
                    'redirectto' => ''
                );
            } else {
                $resp      = array(
                    'status'     => 'success',
                    'message'    => __('Donation cart amount successfully processed', 'psyeventsmanager'),
                    'validation' => [],
                    'redirectto' =>  $donation_checkout_page_link
                );
            }
        }
        wp_send_json($resp, 200);
    }

    function psyem_ManageDonationStripeIntentAjax()
    {

        global $wpdb;
        $postData  = $this->REQ;

        $resp      = array(
            'status'     => 'error',
            'message'    => __('Payment intent has been failed to process', 'psyeventsmanager'),
            'validation' => [],
            'data'       => array('clientSecret' => '', 'PaymentIntentId' => '')
        );

        $isvalid            = psyem_ValidateDonationIntentData($postData);
        if (!empty($isvalid)) {
            $resp['validation'] = $isvalid;
            wp_send_json($resp, 200);
        }

        $reqAmountEnc       = @$postData['amount_enc'];
        $reqAmount          = @$postData['amount'];
        $sessKey            = 'donation_cart';
        $donationCart       = (isset($_SESSION[$sessKey])) ? $_SESSION[$sessKey] :  [];
        $amount_enc         = (isset($donationCart['amount_enc'])) ? $donationCart['amount_enc'] :  '';
        $amount_for         = (isset($donationCart['amount_for'])) ? $donationCart['amount_for'] :  '';
        $amount             = (isset($donationCart['amount'])) ? $donationCart['amount'] :  0;
        $amount             =  ($amount > 0) ?  $amount : 0.00;

        if ($reqAmountEnc != $amount_enc) {
            $resp['message'] = __('Payment intent action is invalid', 'psyeventsmanager');
            wp_send_json($resp, 200);
        }

        $CurrenyType                    = psyem_GetCurrenyType();
        $psyem_options                  = psyem_GetOptionsWithPrefix();
        $psyem_currency_exchange_rate   = @$psyem_options['psyem_currency_exchange_rate'];
        $psyem_stripe_publish_key       = @$psyem_options['psyem_stripe_publish_key'];
        $psyem_stripe_secret_key        = @$psyem_options['psyem_stripe_secret_key'];

        $flags         = [];
        $amount_id     = 0;
        $amount_type   = $amount_enc;
        $amount_name   = $amount_enc;
        if (!empty($amount_enc) && $amount_enc != 'Custom') {
            $flags[] = 1;
            $amount_id       = psyem_safe_b64decode_id($amount_enc);
            $amount_info     = ($amount_id > 0) ? psyem_GetSinglePostWithMetaPrefix('psyem-amounts', $amount_id, 'psyem_amount_') : [];
            $amount_meta     = @$amount_info['meta_data'];
            $amount_type     = @$amount_meta['psyem_amount_type'];
            $amount_price    = @$amount_meta['psyem_amount_price'];
            $amount_name     = @$amount_info['title'];
            $amount          = ($amount_price > 0) ?  $amount_price : $amount;
            if ($CurrenyType != 'USD') {
                $flags[] = 2;
                $usdAmount   = psyem_ConvertUsdToHkd($amount_price, $psyem_currency_exchange_rate);
                $amount      = ($usdAmount > 0) ?  $usdAmount : $amount;
            }
        }
        $totalPrice          = psyem_roundPrecision($amount);
        $postData            = psyem_UnsetDonationCheckoutData($postData);

        if ($totalPrice > 1) {
            $flags[] = 3;
            $payable_amount               = psyem_FloatToInt($totalPrice);
            // The minimum amount 100 cents to charge $1.00
            if ($payable_amount > 100) {
                $flags[] = 4;
                $metadata               = array(
                    'currency'              => $CurrenyType,
                    'amount_id'             => $amount_id,
                    'amount_name'           => $amount_name,
                    'amount_enc'            => $amount_enc,
                    'amount'                => $amount,
                    'amount_type'           => $amount_type,
                    'amount_for'            => $amount_for,
                    'payment_source'        => 'psyem_donation'
                );
                $metadata = array_merge($metadata, $postData);

                // Onetime intent
                if ($amount_for == 'Onetime') {
                    $flags[] = 5;
                    if ($amount_type == 'Custom' || $amount_type == 'Onetime') {
                        $flags[] = 6;
                        try {
                            $flags[] = 7;
                            Stripe::setApiKey($psyem_stripe_secret_key);

                            $paymentIntent              = \Stripe\PaymentIntent::create([
                                'amount'                => $payable_amount,
                                'currency'              => strtolower($CurrenyType),
                                'metadata'              => $metadata,
                                'payment_method_types'  => ["card"]
                            ]);

                            $PaymentIntentId =  @$paymentIntent->id;
                            $clientSecret    =  @$paymentIntent->client_secret;

                            $resp  = array(
                                'status'     => 'success',
                                'message'    => __('Payment intent created', 'psyeventsmanager'),
                                'validation' => [],
                                'data'       => array('clientSecret' =>  $clientSecret, 'PaymentIntentId' => $PaymentIntentId)
                            );
                        } catch (\Exception $e) {
                            $flags[] = 8;
                            error_log('psyem_ManageDonationStripeIntentAjax Onetime ERROR :: ' . $e->getMessage());
                            $resp      = array(
                                'status'     => 'error',
                                'message'    => $e->getMessage(),
                                'validation' => [],
                                'data'       => array('clientSecret' => '', 'PaymentIntentId' => '')
                            );
                        }
                    }
                }

                // Monthly intent
                if ($amount_for == 'Monthly') {
                    $flags[] = 9;
                    if ($amount_type == 'Custom' || $amount_type == 'Monthly') {
                        $flags[] = 10;
                        // create stripe customer                       
                        $StripeCustomerAddress = [
                            'line1'         => @$metadata['billing_address'],
                            'line2'         => @$metadata['billing_address2'],
                            'city'          => @$metadata['billing_district'],
                            'state'         => @$metadata['billing_city'],
                            'country'       => @$metadata['billing_country']
                        ];
                        $StripeCustomerParams = [
                            'name'          => @$metadata['first_name'] . ' ' . @$metadata['last_name'],
                            'address'       => @$StripeCustomerAddress,
                            'email'         => @$metadata['email'],
                            'metadata'      => $metadata
                        ];
                        $StripeCustomer          = psyem_CreateStripeCustomer($StripeCustomerParams, $psyem_stripe_secret_key);
                        $stripe_customer_id      = (isset($StripeCustomer->id)) ? $StripeCustomer->id : '';
                        // create stripe product
                        if (!empty($stripe_customer_id)) {
                            $flags[] = 11;
                            $StripeProductParams    = array(
                                "name"              => psyem_GetStripeCustomProductName($metadata),
                                "type"              => "service",
                                "metadata"          => $metadata
                            );;
                            $StripeProduct          = psyem_CreateStripeProduct($StripeProductParams, $psyem_stripe_secret_key);
                            $stripe_product_id      = (isset($StripeProduct->id)) ? $StripeProduct->id : '';
                            // create stripe price
                            if (!empty($stripe_product_id)) {
                                $flags[] = 12;

                                $StripePriceParams          = array(
                                    'unit_amount'           => $payable_amount,
                                    'currency'              => strtolower($CurrenyType),
                                    'recurring'             => [
                                        'interval'          => 'month',
                                        'interval_count'    => 1,
                                    ],
                                    'product'               => $stripe_product_id,
                                    "metadata"              => $metadata
                                );
                                $StripePrice         = psyem_CreateStripePrice($StripePriceParams, $psyem_stripe_secret_key);
                                $stripe_price_id     = (isset($StripePrice->id)) ? $StripePrice->id : '';
                                // create stripe subscription
                                if (!empty($stripe_price_id)) {
                                    $flags[] = 13;
                                    $StripeSubscriptionParams   = array(
                                        'customer'          => $stripe_customer_id,
                                        'items'             => [['price' => $stripe_price_id]],
                                        'payment_behavior'  => 'default_incomplete',
                                        'payment_settings'  => [
                                            'payment_method_types' => ['card'],
                                            'save_default_payment_method' => 'on_subscription'
                                        ],
                                        'expand'            => ['latest_invoice.payment_intent'],
                                        'metadata'          => $metadata,
                                    );
                                    $StripeSubscription         = psyem_CreateStripeSubscription($StripeSubscriptionParams, $psyem_stripe_secret_key);
                                    $stripe_subscription_id     = (isset($StripeSubscription->id)) ? $StripeSubscription->id : '';
                                    if (!empty($stripe_subscription_id)) {
                                        $flags[] = 14;
                                        $StripeLatestInvoice    = @$StripeSubscription->latest_invoice;
                                        $StripePaymentIntent    = @$StripeLatestInvoice->payment_intent;

                                        // get stripe payment intent
                                        $PaymentIntentId      = psyem_GetStripePaymentIntentId($StripePaymentIntent);
                                        if (!empty($PaymentIntentId)) {
                                            $flags[] = 15;
                                            $clientSecret  = @$StripePaymentIntent->client_secret;
                                            $resp       = array(
                                                'status'     => 'success',
                                                'message'    => __('Payment intent created', 'psyeventsmanager'),
                                                'validation' => [],
                                                'data'       => array('clientSecret' =>  $clientSecret, 'PaymentIntentId' => $PaymentIntentId)
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $resp['flags'] = $flags;
        wp_send_json($resp, 200);
    }

    function psyem_ManageDonationStripePaymentAjax()
    {

        global $wpdb;
        $postData  = $this->REQ;

        $resp      = array(
            'status'     => 'error',
            'message'    => __('Donation amount has been failed to process', 'psyeventsmanager'),
            'validation' => [],
            'data'       => []
        );

        $isvalid            = psyem_ValidateDonationPaymentData($postData);
        if (!empty($isvalid)) {
            $resp['validation'] = $isvalid;
            wp_send_json($resp, 200);
        }

        $payment_intent_id           = @$postData['intent_id'];
        $payment_tripe_status        = @$postData['stripe_status'];
        $psyem_options               = psyem_GetOptionsWithPrefix();
        $psyem_stripe_secret_key     = @$psyem_options['psyem_stripe_secret_key'];

        if (!empty($payment_intent_id) && !empty($psyem_stripe_secret_key) && !empty($payment_tripe_status)) {
            try {
                $stripe             = new \Stripe\StripeClient($psyem_stripe_secret_key);

                $payment_intent     = $stripe->paymentIntents->retrieve($payment_intent_id,  []);
                $payment_intent_body = null;
                if ($payment_intent) {
                    $payment_intent_body = $payment_intent->jsonSerialize();
                }

                $payment_intent_id  = (isset($payment_intent->id))                  ? $payment_intent->id : '';
                $client_secret      = (isset($payment_intent->client_secret))       ? $payment_intent->client_secret : '';
                $charge_id          = (isset($payment_intent->latest_charge))       ? $payment_intent->latest_charge : '';
                $customer_id        = (isset($payment_intent->customer))            ? $payment_intent->customer : '';
                $payment_method     = (isset($payment_intent->payment_method))      ? $payment_intent->payment_method : '';
                $payment_status     = (isset($payment_intent->status))              ? $payment_intent->status : '';
                $receipt_email      = (isset($payment_intent->receipt_email))       ? $payment_intent->receipt_email : '';
                $amount             = (isset($payment_intent->amount))              ? $payment_intent->amount : '';
                $currency           = (isset($payment_intent->currency))          ? $payment_intent->currency : '';
                $amount_received    = (isset($payment_intent->amount_received))     ? $payment_intent->amount_received : '';
                $created_timestamp  = (isset($payment_intent->created))             ? $payment_intent->created : '';

                $metadata           = (isset($payment_intent->metadata))            ? $payment_intent->metadata : '';
                $payment_source     = (isset($metadata->payment_source))            ? $metadata->payment_source : ''; // psyem_donation
                if (empty($payment_source)) {
                    $StripeCustomer = psyem_GetStripeCustomerData($customer_id, $psyem_stripe_secret_key);
                    $metadata       =  (isset($StripeCustomer->metadata))           ? $StripeCustomer->metadata : '';
                }

                // amount info
                $amount_id          = (isset($metadata->amount_id))                 ? $metadata->amount_id : 0;
                $amount_enc         = (isset($metadata->amount_enc))                ? $metadata->amount_enc : '';
                $amount_type        = (isset($metadata->amount_type))               ? $metadata->amount_type : '';
                $amount_for         = (isset($metadata->amount_for))                ? $metadata->amount_for : '';
                $amount_name        = (isset($metadata->amount_name))               ? $metadata->amount_name : '';
                // donor info 
                $first_name        = (isset($metadata->first_name))                 ? $metadata->first_name : '';
                $last_name         = (isset($metadata->last_name))                  ? $metadata->last_name : '';
                $full_name         = $first_name . ' ' . $last_name;
                $email             = (isset($metadata->email))                      ? $metadata->email : '';
                $phone             = (isset($metadata->phone))                      ? $metadata->phone : '';
                $company           = (isset($metadata->company))                    ? $metadata->company : '';
                // donor billing info 
                $billing_country   = (isset($metadata->billing_country))            ? $metadata->billing_country : '';
                $billing_address   = (isset($metadata->billing_address))            ? $metadata->billing_address : '';
                $billing_address2  = (isset($metadata->billing_address2))           ? $metadata->billing_address2 : '';
                $billing_city      = (isset($metadata->billing_city))               ? $metadata->billing_city : '';
                $billing_district  = (isset($metadata->billing_district))           ? $metadata->billing_district : '';
                $amountInfo        = ($amount_id > 0) ? psyem_GetSinglePostWithMetaPrefix('psyem-amounts', $amount_id, 'psyem_amount_') : [];
                $amountMeta        = @$amountInfo['meta_data'];
                $amount_title      = (!empty($amountInfo['title'])) ? $amountInfo['title'] : $amount_enc . ' Donation';
                $amount_price      = (!empty($amountMeta['psyem_amount_price'])) ? $amountMeta['psyem_amount_price'] : $amount;

                if (!empty($payment_intent_id)) {
                    $dbStatus           = $payment_status;
                    switch ($payment_status) {
                        case "succeeded":
                            $presp = 'succeeded';
                            $dbStatus = 'Success';
                            break;
                        case "processing":
                            $presp = 'processing';
                            $dbStatus = 'Proccessed';
                            break;
                        case "requires_payment_method":
                            $presp = 'requires_payment_method';
                            $dbStatus = 'Pending';
                            break;
                        case "requires_action":
                            $presp = 'requires_action';
                            $dbStatus = 'Pending';
                            break;
                        default:
                            $presp = 'error';
                            $dbStatus = 'Failed';
                            break;
                    }

                    $current_time     = current_time('mysql');
                    $current_time_gmt = current_time('mysql', 1); // Get GMT time
                    // Create order
                    $orderPostContent = @$amount_title . ' ' . strtolower($amount_title) . ' ' . $full_name . ' ' . $email . ' ' . strtolower($full_name) . ' ' . strtolower($email);

                    $orderTitle = ucfirst($full_name) . ' - ' . $amount_title . ' -- ' . $dbStatus;
                    $post_data  = array(
                        'post_title'    => ucfirst($orderTitle),
                        'post_name'     => sanitize_title($orderTitle),
                        'post_status'   => 'publish',
                        'post_content'  => $orderPostContent,
                        'post_excerpt'  => @$amount_title . ' Donation Order',
                        'post_type'     => 'psyem-donations',
                        'post_date'     => $current_time,
                        'post_date_gmt' => $current_time_gmt,
                    );
                    $insertOResp         = $wpdb->insert($wpdb->posts, $post_data);
                    $inserted_order_id  = @$wpdb->insert_id;

                    if ($inserted_order_id  > 0) {
                        $orderEnc =  psyem_safe_b64encode_id($inserted_order_id);
                        $orderPostContent = $orderEnc . ' ' . @$amount_title . ' ' . strtolower($amount_title) . ' ' . $full_name . ' ' . $email . ' ' . strtolower($full_name) . ' ' . strtolower($email);

                        // Update the post
                        $orderTitle = ucfirst($full_name)  . ' Donation - ' . $dbStatus . ' - ' . $orderEnc;
                        $updated_post_data = array(
                            'ID'           => $inserted_order_id,
                            'post_title'   => ucfirst($orderTitle),
                            'post_name'    => sanitize_title($orderTitle),
                            'post_content'  => $orderPostContent,
                            'post_excerpt'  => $orderPostContent,
                        );
                        wp_update_post($updated_post_data);

                        try {
                            $payment_intent_body['metadata'] = $metadata;
                        } catch (\Exception $e) {
                            error_log('psyem_ManageDonationStripePaymentAjax metadata ERROR :: ' . $e->getMessage());
                        }
                        // // update order meta 
                        update_post_meta($inserted_order_id, 'psyem_donation_client_secret', $client_secret);
                        update_post_meta($inserted_order_id, 'psyem_donation_intent_id', $payment_intent_id);
                        update_post_meta($inserted_order_id, 'psyem_donation_charge_id', $charge_id);
                        update_post_meta($inserted_order_id, 'psyem_donation_payment_method', $payment_method);
                        update_post_meta($inserted_order_id, 'psyem_donation_payment_status', $dbStatus);
                        update_post_meta($inserted_order_id, 'psyem_donation_receipt_email', $receipt_email);
                        update_post_meta($inserted_order_id, 'psyem_donation_payment_datetime', $created_timestamp);
                        update_post_meta($inserted_order_id, 'psyem_donation_payment_payload', $payment_intent_body);

                        update_post_meta($inserted_order_id, 'psyem_donation_amount_currency', $currency);
                        update_post_meta($inserted_order_id, 'psyem_donation_amount_id', $amount_id);
                        update_post_meta($inserted_order_id, 'psyem_donation_amount_type', $amount_type);
                        update_post_meta($inserted_order_id, 'psyem_donation_amount_for', $amount_for);
                        update_post_meta($inserted_order_id, 'psyem_donation_amount_price', $amount_price);
                        update_post_meta($inserted_order_id, 'psyem_donation_stripe_amount', psyem_IntToFloat($amount));
                        update_post_meta($inserted_order_id, 'psyem_donation_recieved_amount', psyem_IntToFloat($amount_received));
                        update_post_meta($inserted_order_id, 'psyem_donation_payment_source', $payment_source);

                        update_post_meta($inserted_order_id, 'psyem_donation_price', psyem_IntToFloat($amount_received));
                        update_post_meta($inserted_order_id, 'psyem_donation_type', $amount_type);
                        update_post_meta($inserted_order_id, 'psyem_donation_status', $dbStatus);

                        update_post_meta($inserted_order_id, 'psyem_donation_first_name', $first_name);
                        update_post_meta($inserted_order_id, 'psyem_donation_last_name', $last_name);
                        update_post_meta($inserted_order_id, 'psyem_donation_full_name', $full_name);
                        update_post_meta($inserted_order_id, 'psyem_donation_email', $email);
                        update_post_meta($inserted_order_id, 'psyem_donation_phone', $phone);
                        update_post_meta($inserted_order_id, 'psyem_donation_company', $company);

                        update_post_meta($inserted_order_id, 'psyem_donation_billing_country', $billing_country);
                        update_post_meta($inserted_order_id, 'psyem_donation_billing_address', $billing_address);
                        update_post_meta($inserted_order_id, 'psyem_donation_billing_address2', $billing_address2);
                        update_post_meta($inserted_order_id, 'psyem_donation_billing_city', $billing_city);
                        update_post_meta($inserted_order_id, 'psyem_donation_billing_district', $billing_district);

                        $resp      = array(
                            'status'     => 'success',
                            'message'    => __('Donation has been successfully proccessed', 'psyeventsmanager'),
                            'validation' => [],
                            'data'       => [
                                'donation_enc' => $orderEnc,
                                'donation_id'  => $inserted_order_id
                            ]
                        );

                        $sessKey = 'donation_cart';
                        $_SESSION[$sessKey]     = [];
                    }
                }
            } catch (\Exception $e) {
                error_log('psyem_ManageDonationStripePaymentAjax  ERROR :: ' . $e->getMessage());
            }
        } else {
            $resp      = array(
                'status'     => 'error',
                'message'    => __('Donation payment intent is empty', 'psyeventsmanager'),
                'validation' => [],
                'data'       => []
            );
        }
        wp_send_json($resp, 200);
    }
    /* DONATION AJAX END */


    /* LISTS SHORTCODES BGN */

    function psyem_EnqueuePostsListShortcodeScripts($params = [])
    {

        wp_enqueue_script('jquery');
        wp_enqueue_script(PSYEM_PREFIX . 'bootstrap5frntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'helperfrntjs');
        wp_enqueue_script(PSYEM_PREFIX . 'psyemlistingfrntjs');

        wp_enqueue_style(PSYEM_PREFIX . 'bootstrap5frntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'helperfrntcss');
        wp_enqueue_style(PSYEM_PREFIX . 'psyemlistingfrntcss');
    }

    function psyem_ManageKnowledgehubListShortcode($atts)
    {
        global $wpdb;
        $args = shortcode_atts(
            array("exclude" => "", "include" => "", "limit" => 20,),
            $atts,
            'psyem-knowledgehub-list'
        );

        $exclude = (isset($args['exclude'])) ? ($args['exclude']) : '';
        $include = (isset($args['include'])) ? ($args['include']) : '';

        $this->psyem_EnqueuePostsListShortcodeScripts();

        $shortcodeOutput = '';
        $pfFilePath = PSYEM_PATH . 'front/pages/psyemKnowledgehubList.php';
        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
            $shortcodeOutput .= require $pfFilePath;
        }

        return $shortcodeOutput;
    }

    function psyem_ManageProgrammesListShortcode($atts)
    {
        global $wpdb;
        $args = shortcode_atts(
            array("exclude" => "", "include" => "", "limit" => 20),
            $atts,
            'psyem-programmes-list'
        );

        $exclude = (isset($args['exclude'])) ? ($args['exclude']) : '';
        $include = (isset($args['include'])) ? ($args['include']) : '';

        $this->psyem_EnqueuePostsListShortcodeScripts();

        $shortcodeOutput = '';
        $pfFilePath = PSYEM_PATH . 'front/pages/psyemProgrammesList.php';
        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
            $shortcodeOutput .= require $pfFilePath;
        }

        return $shortcodeOutput;
    }

    function psyem_ManageNewsListShortcode($atts)
    {

        global $wpdb;
        $args = shortcode_atts(
            array("exclude" => "", "include" => "", "limit" => 20),
            $atts,
            'psyem-news-list'
        );

        $exclude = (isset($args['exclude'])) ? ($args['exclude']) : '';
        $include = (isset($args['include'])) ? ($args['include']) : '';

        $this->psyem_EnqueuePostsListShortcodeScripts();

        $shortcodeOutput = '';
        $pfFilePath = PSYEM_PATH . 'front/pages/psyemNewsList.php';
        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
            $shortcodeOutput .= require $pfFilePath;
        }

        return $shortcodeOutput;
    }
    /* LISTS SHORTCODES END */
}
$psyemFrontManager = new psyemFrontManager();
