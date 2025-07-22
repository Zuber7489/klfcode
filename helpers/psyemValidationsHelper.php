<?php

function psyem_ValidateSettingsData($post = [])
{
    $errors = array();
    if (!empty($post) && is_array($post)) {
        if (!isset($post['Settings']) || empty($post['Settings'])) {
            $errors[] = __('Settings are required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }
        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }
    }
    return $errors;
}

function psyem_ValidateParticipantsCsvData($post = [])
{
    $errors = array();
    if (!empty($post) && is_array($post)) {
        if (!isset($post['participant_order_id']) || empty($post['participant_order_id'])) {
            $errors[] = __('Order ID is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }

        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }

        $fileTmpName = @$_FILES['participant_csv_file']['tmp_name'];
        $fileName    = @$_FILES['participant_csv_file']['name'];
        $file_type   = pathinfo($fileName, PATHINFO_EXTENSION);
        if ($file_type != 'csv') {
            $errors[] = __('Invalid file type. Only CSV files are allowed', 'psyeventsmanager');
        }
    }
    return $errors;
}

function psyem_ValidateOrderPrintTicketsData($post = [])
{
    $errors = array();
    if (!empty($post) && is_array($post)) {
        if (!isset($post['order_id']) || empty($post['order_id'])) {
            $errors[] = __('Order ID is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }

        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }
    }
    return $errors;
}

function psyem_ValidateCopyEventData($post = [])
{
    $errors = array();
    if (!empty($post) && is_array($post)) {
        if (!isset($post['event_id']) || empty($post['event_id'])) {
            $errors[] = __('Event ID is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }

        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }
    }
    return $errors;
}

function psyem_ValidateOrderSendTicketsData($post = [])
{
    $errors = array();
    if (!empty($post) && is_array($post)) {
        if (!isset($post['order_id']) || empty($post['order_id'])) {
            $errors[] = __('Order ID is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }

        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }
    }
    return $errors;
}

function psyem_ValidateEventOrderCartPriceCalculationData($post = [])
{
    $errors = array();
    if (!isset($post['checkout_key']) || empty($post['checkout_key'])) {
        $errors[] = __('Event key is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['checkout_tickets']) || empty($post['checkout_tickets'])) {
        $errors[] = __('At least one ticket with participant is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['checkout_source']) || empty($post['checkout_source'])) {
        $errors[] = __('Price calculation source is missing', 'psyeventsmanager');
    }
    if (!isset($post['action']) || empty($post['action'])) {
        $errors[] = __('Action is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['_nonce']) || empty($post['_nonce'])) {
        $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
    }

    if (isset($post['_nonce']) && !empty($post['_nonce'])) {
        $do_check      = check_ajax_referer('_nonce', '_nonce', false);
        if ($do_check == false) {
            $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
        }
        $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
        if ($do_nonce == false) {
            $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
        }
    }

    return $errors;
}

function psyem_ValidateEventOrderIntentData($post = [])
{
    $errors = array();

    if (!isset($post['checkout_name']) || empty($post['checkout_name'])) {
        $errors[] = __('Participant name is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['checkout_email']) || empty($post['checkout_email'])) {
        $errors[] = __('Participant email required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['checkout_key']) || empty($post['checkout_key'])) {
        $errors[] = __('Event key is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['checkout_tickets']) || empty($post['checkout_tickets'])) {
        $errors[] = __('Ticket is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['action']) || empty($post['action'])) {
        $errors[] = __('Action is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['_nonce']) || empty($post['_nonce'])) {
        $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
    }

    if (isset($post['_nonce']) && !empty($post['_nonce'])) {
        $do_check      = check_ajax_referer('_nonce', '_nonce', false);
        if ($do_check == false) {
            $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
        }
        $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
        if ($do_nonce == false) {
            $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
        }
    }

    return $errors;
}

function psyem_ValidateEventOrderPaymentData($post = [])
{
    $errors = array();
    if (!isset($post['intent_id']) || empty($post['intent_id'])) {
        $errors[] = __('Payment intent is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['stripe_status']) || empty($post['stripe_status'])) {
        $errors[] = __('Payment status is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['checkout_name']) || empty($post['checkout_name'])) {
        $errors[] = __('Participant name is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['checkout_email']) || empty($post['checkout_email'])) {
        $errors[] = __('Participant email required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['checkout_key']) || empty($post['checkout_key'])) {
        $errors[] = __('Event key is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['action']) || empty($post['action'])) {
        $errors[] = __('Action is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['_nonce']) || empty($post['_nonce'])) {
        $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
    }

    if (isset($post['_nonce']) && !empty($post['_nonce'])) {
        $do_check      = check_ajax_referer('_nonce', '_nonce', false);
        if ($do_check == false) {
            $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
        }
        $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
        if ($do_nonce == false) {
            $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
        }
    }

    return $errors;
}

function psyem_ValidateEventOrderFreeBookingData($post = [])
{
    $errors = array();
    if (!isset($post['checkout_name']) || empty($post['checkout_name'])) {
        $errors[] = __('Participant name is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['checkout_email']) || empty($post['checkout_email'])) {
        $errors[] = __('Participant email required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['checkout_key']) || empty($post['checkout_key'])) {
        $errors[] = __('Event key is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['checkout_tickets']) || empty($post['checkout_tickets'])) {
        $errors[] = __('Participants count is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['action']) || empty($post['action'])) {
        $errors[] = __('Action is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['_nonce']) || empty($post['_nonce'])) {
        $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
    }

    if (isset($post['_nonce']) && !empty($post['_nonce'])) {
        $do_check      = check_ajax_referer('_nonce', '_nonce', false);
        if ($do_check == false) {
            $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
        }
        $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
        if ($do_nonce == false) {
            $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
        }
    }

    return $errors;
}

function psyem_ValidateOfflineRegistrationData($post = [])
{
    $errors = array();
    if (!empty($post) && is_array($post)) {
        if (!isset($post['offline_event']) || empty($post['offline_event'])) {
            $errors[] = __('Event is required', 'psyeventsmanager');
        }
        if (!isset($post['offline_firstname']) || empty($post['offline_firstname'])) {
            $errors[] = __('Participant first name is required', 'psyeventsmanager');
        }
        if (!isset($post['offline_lastname']) || empty($post['offline_lastname'])) {
            $errors[] = __('Participant last name is required', 'psyeventsmanager');
        }
        if (!isset($post['offline_email']) || empty($post['offline_email'])) {
            $errors[] = __('Participant email is required', 'psyeventsmanager');
        }
        if (!isset($post['offline_tickets']) || empty($post['offline_tickets'])) {
            $errors[] = __('Participants count is required', 'psyeventsmanager');
        }

        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }
        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }
    }
    return $errors;
}

function psyem_ValidateProjectSafeFormData($post = [])
{

    $errors = array();
    if (!empty($post) && is_array($post)) {

        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }
        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }

        if (!isset($post['field_form_type']) || empty($post['field_form_type'])) {
            $errors[] = __('Input source is missing', 'psyeventsmanager');
        }

        if (!isset($post['field_form_key']) || empty($post['field_form_key'])) {
            $errors[] = __('Input key is missing', 'psyeventsmanager');
        }

        $form_type   = @$post['field_form_type'];

        if ($form_type == 'Participant') {
            if (!isset($post['field_first_name']) || empty($post['field_first_name'])) {
                $errors[] = __('First name field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_last_name']) || empty($post['field_last_name'])) {
                $errors[] = __('Last name field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_gender']) || empty($post['field_gender'])) {
                $errors[] = __('Gender field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_dob_day']) || empty($post['field_dob_day'])) {
                $errors[] = __('DOB date field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_dob_month']) || empty($post['field_dob_month'])) {
                $errors[] = __('DOB month field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_dob_year']) || empty($post['field_dob_year'])) {
                $errors[] = __('DOB year field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_sexual_experience']) || empty($post['field_sexual_experience'])) {
                $errors[] = __('Sexual experience field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_cervical_screening']) || empty($post['field_cervical_screening'])) {
                $errors[] = __('Cervical screening field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_undergoing_treatment']) || empty($post['field_undergoing_treatment'])) {
                $errors[] = __('Undergoing treatment field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_received_hpv']) || empty($post['field_received_hpv'])) {
                $errors[] = __('Received HPV vaccine field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_pregnant']) || empty($post['field_pregnant'])) {
                $errors[] = __('Are you pregnant field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_hysterectomy']) || empty($post['field_hysterectomy'])) {
                $errors[] = __('Did you have a hysterectomy field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_agree_clinical']) || empty($post['field_agree_clinical'])) {
                $errors[] = __('Clinical study consent field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_info_sheet']) || empty($post['field_info_sheet'])) {
                $errors[] = __('Information sheet consent field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_participation']) || empty($post['field_participation'])) {
                $errors[] = __('Voluntary participation consent field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_agree_study']) || empty($post['field_agree_study'])) {
                $errors[] = __('Cervical screening programme consent field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_agree_doctor']) || empty($post['field_agree_doctor'])) {
                $errors[] = __('Professional opinion consent field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_agree_tnc']) || empty($post['field_agree_tnc'])) {
                $errors[] = __('Terms and conditions consent field is required', 'psyeventsmanager');
            }
        }

        if ($form_type == 'Contact') {
            if (
                (!isset($post['field_contact_sms']) || empty($post['field_contact_sms'])) &&
                (!isset($post['field_contact_email']) || empty($post['field_contact_email']))
            ) {
                $errors[] = __('Contact way field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_phone']) || empty($post['field_phone'])) {
                $errors[] = __('Phone field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_email']) || empty($post['field_email'])) {
                $errors[] = __('Email field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_region']) || empty($post['field_region'])) {
                $errors[] = __('Region field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_district']) || empty($post['field_district'])) {
                $errors[] = __('District field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_address']) || empty($post['field_address'])) {
                $errors[] = __('Address field is required', 'psyeventsmanager');
            }
            if (!isset($post['field_source']) || empty($post['field_source'])) {
                $errors[] = __('Contact source field is required', 'psyeventsmanager');
            }
        }
    }

    return $errors;
}

function psyem_ValidateProjectSafeExportData($post)
{
    $errors = array();
    if (!empty($post) && is_array($post)) {
        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }
        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }
    }
    return $errors;
}

function psyem_ValidateParticipantsExportData($post)
{
    $errors = array();
    if (!empty($post) && is_array($post)) {
        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }
        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }
    }
    return $errors;
}

function psyem_ValidateExportEventAttendeesData($post)
{
    $errors = array();
    if (!empty($post) && is_array($post)) {
        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }
        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }
    }
    return $errors;
}


function psyem_ValidateDonationAmountsData($post)
{
    $errors = array();
    if (!empty($post) && is_array($post)) {
        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }
        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }
        if (!isset($post['amount_type']) || empty($post['amount_type'])) {
            $errors[] = __('Amount type is required', 'psyeventsmanager');
        } else {
            if (!in_array($post['amount_type'], ['Monthly', 'Onetime'])) {
                $errors[] = __('Amount type is invalid', 'psyeventsmanager');
            }
        }
    }
    return $errors;
}

function psyem_ValidateDonationAmountProcessData($post)
{
    $errors = array();
    if (!empty($post) && is_array($post)) {
        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }
        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }
        if (!isset($post['amount_enc']) || empty($post['amount_enc'])) {
            $errors[] = __('Amount type is invalid', 'psyeventsmanager');
        } else {
            if ($post['amount_enc'] == 'Custom') {
                if (!isset($post['amount']) || empty($post['amount'])) {
                    $errors[] = __('Amount must be greater than 1', 'psyeventsmanager');
                } else {
                    if ($post['amount'] > 0) {
                    } else {
                        $errors[] = __('Amount must be greater than 1', 'psyeventsmanager');
                    }
                }
            }
        }
    }
    return $errors;
}

function psyem_ValidateDonationIntentData($post)
{
    $errors = array();
    if (!empty($post) && is_array($post)) {
        if (!isset($post['action']) || empty($post['action'])) {
            $errors[] = __('Action is required to process the request', 'psyeventsmanager');
        }
        if (!isset($post['_nonce']) || empty($post['_nonce'])) {
            $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
        }
        if (isset($post['_nonce']) && !empty($post['_nonce'])) {
            $do_check      = check_ajax_referer('_nonce', '_nonce', false);
            if ($do_check == false) {
                $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
            }
            $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
            if ($do_nonce == false) {
                $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
            }
        }
        if (!isset($post['amount_enc']) || empty($post['amount_enc'])) {
            $errors[] = __('Amount type is invalid', 'psyeventsmanager');
        } else {
            if ($post['amount_enc'] == 'Custom') {
                if (!isset($post['amount']) || empty($post['amount'])) {
                    $errors[] = __('Amount must be greater than 1', 'psyeventsmanager');
                } else {
                    if ($post['amount'] > 0) {
                    } else {
                        $errors[] = __('Amount must be greater than 1', 'psyeventsmanager');
                    }
                }
            }
        }
    }
    return $errors;
}

function psyem_ValidateDonationPaymentData($post = [])
{
    $errors = array();
    if (!isset($post['intent_id']) || empty($post['intent_id'])) {
        $errors[] = __('Payment intent is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['stripe_status']) || empty($post['stripe_status'])) {
        $errors[] = __('Payment status is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['action']) || empty($post['action'])) {
        $errors[] = __('Action is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['_nonce']) || empty($post['_nonce'])) {
        $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
    }

    if (isset($post['_nonce']) && !empty($post['_nonce'])) {
        $do_check      = check_ajax_referer('_nonce', '_nonce', false);
        if ($do_check == false) {
            $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
        }
        $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
        if ($do_nonce == false) {
            $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
        }
    }

    return $errors;
}

function psyem_ValidateProjectSafeTypeData($post = [])
{

    $errors = array();
    $task   = @$post['task'];
    if (!isset($post['task']) || empty($post['task'])) {
        $errors[] = __('Task is required to process the request', 'psyeventsmanager');
    } else {
        if (!in_array($task, ['Create', 'Remove'])) {
            $errors[] = __('Task is invalid to process the request', 'psyeventsmanager');
        }
    }

    if ($task == 'Create') {
        if (!isset($post['title']) || empty($post['title'])) {
            $errors[] = __('Title is required to process the request', 'psyeventsmanager');
        }
    }
    if ($task == 'Remove') {
        if (!isset($post['row_slug']) || empty($post['row_slug'])) {
            $errors[] = __('Record slug is required to process the request', 'psyeventsmanager');
        }
    }

    if (!isset($post['action']) || empty($post['action'])) {
        $errors[] = __('Action is required to process the request', 'psyeventsmanager');
    }
    if (!isset($post['_nonce']) || empty($post['_nonce'])) {
        $errors[] = __('Nonce is missing, Invalid request', 'psyeventsmanager');
    }

    if (isset($post['_nonce']) && !empty($post['_nonce'])) {
        $do_check      = check_ajax_referer('_nonce', '_nonce', false);
        if ($do_check == false) {
            $errors[] = __('No kiddies please! Referer not matched', 'psyeventsmanager');
        }
        $do_nonce   = wp_verify_nonce($post['_nonce'], '_nonce');
        if ($do_nonce == false) {
            $errors[] = __('No kiddies please! Security nonce not matched', 'psyeventsmanager');
        }
    }
    return $errors;
}
