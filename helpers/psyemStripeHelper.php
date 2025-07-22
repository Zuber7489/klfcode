<?php
require PSYEM_PATH . 'packages/vendor/autoload.php';

use Stripe\Stripe;

function psyem_CreateStripeCustomer($data = [], $stripeSecretKey = '')
{

    /*
    $address = [
        'line1'         => @$UserData->address,
        'city'          => @$UserData->city_name,
        'state'         => @$UserData->state_name,
        'country'       => $countryName,
        'postal_code'   => @$UserData->pin
    ];

    $data = [
        'name'          => @$UserData->firstname . ' ' . @$UserData->lastname,
        'address'       => @$address,
        'email'         => @$UserData->email,
        'phone'         => '+1' . @$UserData->phone,
        'metadata'      => array('user_id'  => $user_id, 'customer_source' => 'signup')
    ];
    */

    $resp = [];
    if (!empty($stripeSecretKey)) {
        try {
            Stripe::setApiKey($stripeSecretKey);
            $stripe                  = new \Stripe\StripeClient($stripeSecretKey);
            $stripeCustomer          = $stripe->customers->create($data);
            $stripe_customer_id      = (isset($stripeCustomer->id)) ? $stripeCustomer->id : '';
            if (!empty($stripe_customer_id)) {
                $resp = $stripeCustomer;
            }
        } catch (\Exception $e) {
            error_log('psyem_CreateStripeCustomer  ERROR :: ' . $e->getMessage());
        }
    }
    return $resp;
}


function psyem_CreateStripeProduct($data = [], $stripeSecretKey = '')
{

    /*  
    $data =  array( 
                "name" 		   => $ProductName,
                "type" 		   => "service",	
                "metadata"     => array(                   
                    'user_id' 	   => $userId,
                    'stripe_token' => $stripeToken,
                    'coupon_data'  => $CouponData,                   	
                )
            );
    */

    $resp = [];
    if (!empty($stripeSecretKey)) {
        try {
            Stripe::setApiKey($stripeSecretKey);
            $StripeProduct     = \Stripe\Product::create($data);
            $stripe_product_id = (isset($StripeProduct->id)) ? $StripeProduct->id : '';
            if (!empty($stripe_product_id)) {
                $resp = $StripeProduct;
            }
        } catch (\Exception $e) {
            error_log('psyem_CreateStripeProduct  ERROR :: ' . $e->getMessage());
        }
    }
    return $resp;
}



function psyem_CreateStripePrice($data = [], $stripeSecretKey = '')
{

    /*  
    $data =  array(
        'unit_amount'           => 1000, // in cents, so $10 // use any one 
        'unit_amount_decimal'   => 10.00, // in cents, so $10 // use any one 
        'custom_unit_amount '   => 10.00, // in cents, so $10 // use any one 
        'currency'              => 'usd',
        'recurring'             => [
                'interval' => 'month',
                'interval_count' => 1, 
        ],
        'product'           => $stripe_productid,
        "metadata"     => array(        
            'user_id' 	 	=> $userId,
            'stripe_token' 	=> $stripeToken,
            'coupon_data'   => $CouponData,
           
        )					
    );
    */

    $resp = [];
    if (!empty($stripeSecretKey)) {
        try {
            Stripe::setApiKey($stripeSecretKey);
            $StripePrice     = \Stripe\Price::create($data);
            $stripe_price_id = (isset($StripePrice->id)) ? $StripePrice->id : '';
            if (!empty($stripe_price_id)) {
                $resp = $StripePrice;
            }
        } catch (\Exception $e) {
            error_log('psyem_CreateStripePrice  ERROR :: ' . $e->getMessage());
        }
    }
    return $resp;
}

function psyem_CreateStripeSubscription($data = [], $stripeSecretKey = '')
{
    /*  
    $data =  array(
					'customer' 				=> $StripeCustomerId,
					'collection_method' 	=> 'charge_automatically',				
					'items' 				=> array(array('plan' => $StripePlanId)),
					"metadata"     		=> array(
						'user_id' 	 	=> $userId,
						'stripe_token' 	=> $stripeToken,
						'coupon_data'   => $CouponData,
						
					) 
				);

            $data = [
                'customer'          => $stripe_customer_id,
                'items'             => [['price' => $stripe_plan_id]],
                'metadata'          => $metadata,
                'payment_behavior'  => 'default_incomplete',
                'payment_settings'  => ['save_default_payment_method' => 'on_subscription'],
                'expand'            => ['latest_invoice.payment_intent']
            ];
    */

    $resp = [];
    if (!empty($stripeSecretKey)) {
        try {
            Stripe::setApiKey($stripeSecretKey);
            $StripeSubscription     = \Stripe\Subscription::create($data);
            $stripe_subscription_id = (isset($StripeSubscription->id)) ? $StripeSubscription->id : '';
            if (!empty($stripe_subscription_id)) {
                $resp = $StripeSubscription;
            }
        } catch (\Exception $e) {
            error_log('psyem_CreateStripePrice  ERROR :: ' . $e->getMessage());
        }
    }
    return $resp;
}


function psyem_GetStripeProductDetails($stripe_product_id = '', $stripeSecretKey = '')
{
    $resp = [];
    if (!empty($stripe_product_id)) {
        try {
            Stripe::setApiKey($stripeSecretKey);
            $stripe = new \Stripe\StripeClient($stripeSecretKey);
            $resp = $stripe->products->retrieve($stripe_product_id, []);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            error_log("psyem_GetStripeProductDetails Error: " . $stripe_product_id . ' :: ' . $errorMessage);
        }
    }
    return $resp;
}


function psyem_GetStripePlanDetails($stripe_price_id = '', $stripeSecretKey = '')
{
    $resp = [];
    if (!empty($stripe_price_id)) {
        try {
            Stripe::setApiKey($stripeSecretKey);
            $stripe = new \Stripe\StripeClient($stripeSecretKey);
            $resp = $stripe->prices->retrieve($stripe_price_id, []);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            error_log("psyem_GetStripePlanDetails Error: " . $stripe_price_id . ' :: ' . $errorMessage);
        }
    }
    return $resp;
}

function psyem_GetStripeChargeData($stripe_charge_id = '', $stripeSecretKey = '')
{
    $resp = [];
    if (!empty($stripe_charge_id)) {
        try {
            Stripe::setApiKey($stripeSecretKey);
            $stripe = new \Stripe\StripeClient($stripeSecretKey);
            $resp = $stripe->charges->retrieve($stripe_charge_id, []);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            error_log("psyem_GetStripeChargeData Error: " . $stripe_charge_id . ' :: ' . $errorMessage);
        }
    }
    return $resp;
}


function psyem_GetStripeCustomerData($stripe_customer_id = '', $stripeSecretKey = '')
{
    $resp = [];
    if (!empty($stripe_customer_id)) {
        try {
            Stripe::setApiKey($stripeSecretKey);
            $stripe = new \Stripe\StripeClient($stripeSecretKey);
            $resp = $stripe->customers->retrieve($stripe_customer_id, []);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            error_log("psyem_GetStripeCustomerData Error: " . $stripe_customer_id . ' :: ' . $errorMessage);
        }
    }
    return $resp;
}




function psyem_GetStripePaymentSubscriptionId($Object)
{
    $resp       = '';
    $ObjectType = @$Object->object;
    switch ($ObjectType) {
        case 'subscription':
            $resp = @$Object->id;
            break;
        case 'invoice':
            $resp = @$Object->subscription;
            break;
    }
    return $resp;
}

function psyem_GetStripePaymentObjectMetaData($Object)
{
    $resp       = array();
    $ObjectType = @$Object->object;
    switch ($ObjectType) {
        case 'subscription':
            $resp                 = @$Object->metadata;
            break;
        case 'invoice':
            $subscription_details = @$Object->subscription_details;
            $resp                 = @$subscription_details->metadata;
            break;
    }
    return $resp;
}

function psyem_GetStripePaymentCustomerId($Object)
{
    $resp       = '';
    $ObjectType = @$Object->object;
    switch ($ObjectType) {
        case 'subscription':
            $resp                 = @$Object->customer;
            break;
        case 'invoice':
            $resp                 = @$Object->customer;
            break;
        case 'charge':
            $resp                 = @$Object->customer;
            break;
        case 'payment_intent':
            $resp                 = @$Object->customer;
            break;
    }
    return $resp;
}

function psyem_GetStripePaymentIntentId($Object)
{
    $resp       = '';
    $ObjectType = @$Object->object;
    switch ($ObjectType) {
        case 'subscription':
            $resp                 = '';
            break;
        case 'invoice':
            $resp                 = @$Object->payment_intent;
            break;
        case 'charge':
            $resp                 = @$Object->payment_intent;
            break;
        case 'payment_intent':
            $resp                 = @$Object->id;
            break;
    }
    return $resp;
}

function psyem_GetStripePaymentMethodId($Object)
{
    $resp       = '';
    $ObjectType = @$Object->object;
    switch ($ObjectType) {
        case 'subscription':
            $resp                 = '';
            break;
        case 'invoice':
            $resp                 = '';
            break;
        case 'charge':
            $resp                 =  @$Object->payment_method;
            break;
        case 'payment_intent':
            $resp                 = @$Object->payment_method;
            break;
    }

    return $resp;
}

function psyem_GetStripePaymentChargeId($Object)
{
    $resp       = '';
    $ObjectType = @$Object->object;
    switch ($ObjectType) {
        case 'subscription':
            $resp                 = '';
            break;
        case 'invoice':
            $resp                 = @$Object->charge;
            break;
        case 'charge':
            $resp                 = @$Object->id;
            break;
        case 'payment_intent':
            $resp                 = @$Object->latest_charge;
            break;
    }
    return $resp;
}

function psyem_GetStripePaymentInvoiceId($Object)
{
    $resp       = '';
    $ObjectType = @$Object->object;
    switch ($ObjectType) {
        case 'subscription':
            $resp                 = @$Object->latest_invoice;
            break;
        case 'invoice':
            $resp                 = @$Object->id;
            break;
        case 'charge':
            $resp                 = @$Object->invoice;
            break;
        case 'payment_intent':
            $resp                 = @$Object->invoice;
            break;
    }
    return $resp;
}

function psyem_GetStripePaymentCurreny($Object)
{
    $resp       = '';
    $ObjectType = @$Object->object;
    switch ($ObjectType) {
        case 'subscription':
            $resp                 = @$Object->currency;
            break;
        case 'invoice':
            $resp                 = @$Object->currency;
            break;
        case 'charge':
            $resp                 = @$Object->currency;
            break;
        case 'payment_intent':
            $resp                 = @$Object->currency;
            break;
    }
    return $resp;
}

function psyem_GetStripePaymentPriceId($Object)
{
    $resp       = '';
    $ObjectType = @$Object->object;
    switch ($ObjectType) {
        case 'subscription':
            $planInfo             = @$Object->plan;
            $resp                 = @$planInfo->id;
            break;
        case 'invoice':
            $planInfo             = @$Object->lines;
            $lineData             = @$planInfo->data;
            $priceDataA           = (isset($lineData[0]->price)) ? @$lineData[0]->price : '';
            $priceDataB           = (isset($lineData->price)) ? @$lineData->price : '';
            $resp                 = (!empty($priceDataA->id)) ? @$priceDataA->id : @$priceDataB->id;
            break;
    }
    return $resp;
}

function psyem_GetStripePaymentProductId($Object)
{
    $resp       = '';
    $ObjectType = @$Object->object;
    switch ($ObjectType) {
        case 'subscription':
            $planInfo             = @$Object->plan;
            $resp                 = @$planInfo->product;
            break;
        case 'invoice':
            $planInfo             = @$Object->lines;
            $lineData             = @$planInfo->data;
            $priceDataA           = (isset($lineData[0]->price)) ? @$lineData[0]->price : '';
            $priceDataB           = (isset($lineData->price)) ? @$lineData->price : '';
            $resp                 = (!empty($priceDataA->product)) ? @$priceDataA->product : @$priceDataB->product;
            break;
    }
    return $resp;
}

function psyem_GetStripeCurrentPaymentMode()
{
    return 'Sandbox';
}



function psyem_GetStripeCustomProductName($metadata = [])
{
    $ProductName = @$metadata['amount_for'] . '-' . @$metadata['amount'];

    return $ProductName . '__' . time();
}
