<?php
require PSYEM_PATH . 'packages/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;



function psyem_AdditionalTranslationKeywords()
{
	$array = [
		'plugin' => [
			'name' 							=> __('PSY Events Manager', 'psyeventsmanager'),
			'uri' 							=> esc_html__('https://smtlabs.io', 'psyeventsmanager'),
			'desc' 							=> esc_html__('Custom Plugin for Events, Tickets, Sales and Orders Managements', 'psyeventsmanager'),
			'author' 						=> __('Pawan Kumar', 'psyeventsmanager'),
		],
		'alerts' => [
			'confirm' 						=> esc_html__('Are you sure?', 'psyeventsmanager'),
			'del_title' 					=> esc_html__('Are you sure want to permanently delete this record?', 'psyeventsmanager'),
			'del_text' 						=> esc_html__('You wont be able to revert this', 'psyeventsmanager'),
			'copy_text' 					=> esc_html__('Want to create copy of clicked event?', 'psyeventsmanager'),
			'ticket_text' 					=> esc_html__('Want to send ticket to selected participants?', 'psyeventsmanager'),
			'create_title' 					=> esc_html__('Are you sure want to create new record?', 'psyeventsmanager'),
			'projectsafe_text' 				=> esc_html__('Sorry, You cannot meet the project requirement', 'psyeventsmanager'),
			'offline_title' 				=> esc_html__('Want to create offline registration?', 'psyeventsmanager'),
		],
		'common' => [
			'yes' 							=> __('Yes', 'psyeventsmanager'),
			'no' 							=> __('No', 'psyeventsmanager'),
			'remove' 						=> __('Remove', 'psyeventsmanager'),
		],
		'messages' => [
			'select_csv' 					=> __('Please select a CSV file', 'psyeventsmanager'),
			'select_participant' 			=> __('Please select participant', 'psyeventsmanager'),
			'slug_missing' 					=> __('Shortcode slug is missing', 'psyeventsmanager'),
			'type_missing' 					=> __('Please fill the type title', 'psyeventsmanager'),
			'save_failed' 					=> __('Information has been failed to save', 'psyeventsmanager'),
			'payment_save_failed'   		=> __('Payment info has been failed to update', 'psyeventsmanager'),
			'one_participant'   			=> __('At least one participant is required to book the ticket', 'psyeventsmanager'),
			'participants_number' 			=> __('Number of participants must be greater than 0', 'psyeventsmanager'),
			'requires_payment_method' 		=> __('Your payment process was not successfull, and requires_payment_method, please try again', 'psyeventsmanager'),
			'requires_action' 				=> __('Your payment process was not successful, and requires_action, please try again', 'psyeventsmanager'),
			'payment_failed' 				=> __('Payment process failed', 'psyeventsmanager'),
			'one_ticket'   					=> __('At least one ticket with participant is required to checkout', 'psyeventsmanager'),
		],
		'validations' => [
			'firstname_required' 			=> __('First name field is required', 'psyeventsmanager'),
			'firstname_invalid' 			=> __('First name is invalid, Add only alphabets [a-zA-Z]', 'psyeventsmanager'),
			'lastname_required' 			=> __('Last name field is required', 'psyeventsmanager'),
			'lastname_invalid' 				=> __('Last name is invalid, Add only alphabets [a-zA-Z]', 'psyeventsmanager'),
			'name_required' 				=> __('Please enter your name', 'psyeventsmanager'),
			'name_invalid' 					=> __('Please enter valid name, Add only alphabets [a-zA-Z]', 'psyeventsmanager'),
			'phone_required' 				=> __('Phone field is required', 'psyeventsmanager'),
			'phone_invalid' 				=> __('Phone field input is invalid, must be a 10 digits valid phone number', 'psyeventsmanager'),
			'email_required' 				=> __('Please enter your valid email', 'psyeventsmanager'),
			'email_invalid' 				=> __('Please enter a valid email address', 'psyeventsmanager'),
			'company_required' 				=> __('Company field is required', 'psyeventsmanager'),
			'company_invalid' 				=> __('Company is invalid, Add only alphabets [a-zA-Z]', 'psyeventsmanager'),
			'country_required' 				=> __('Country field is required', 'psyeventsmanager'),
			'country_invalid' 				=> __('Country is invalid, Add only alphabets [a-zA-Z]', 'psyeventsmanager'),
			'district_required' 			=> __('District field is required', 'psyeventsmanager'),
			'district_invalid' 				=> __('District is invalid, Add only alphabets [a-zA-Z]', 'psyeventsmanager'),
			'city_required' 				=> __('City field is required', 'psyeventsmanager'),
			'city_invalid' 					=> __('City is invalid, Add only alphabets [a-zA-Z]', 'psyeventsmanager'),
			'address_required' 				=> __('Address field is required', 'psyeventsmanager'),
			'participants_count' 			=> __('Please choose particpants count', 'psyeventsmanager'),
			'amount_required' 				=> __('Please enter some amount to process', 'psyeventsmanager'),
			'gender_required' 				=> __('Gender field is required', 'psyeventsmanager'),
			'dobdate_required' 				=> __('DOB date field is required', 'psyeventsmanager'),
			'dobmonth_required' 			=> __('DOB month field is required', 'psyeventsmanager'),
			'dobyear_required' 				=> __('DOB year field is required', 'psyeventsmanager'),
			'sexual_experience_required' 	=> __('Sexual experience field is required', 'psyeventsmanager'),
			'cervical_screening_required' 	=> __('Cervical screening field is required', 'psyeventsmanager'),
			'undergoing_treatment_required'	=> __('Undergoing treatment field is required', 'psyeventsmanager'),
			'received_hpv_required'			=> __('Received HPV vaccine field is required', 'psyeventsmanager'),
			'pregnant_required'				=> __('Are you pregnant field is required', 'psyeventsmanager'),
			'hysterectomy_required'			=> __('Did you have a hysterectomy field is required', 'psyeventsmanager'),
			'clinical_study_required'		=> __('Clinical study consent field is required', 'psyeventsmanager'),
			'info_sheet_required'			=> __('Information sheet consent field is required', 'psyeventsmanager'),
			'voluntary_required'			=> __('Voluntary participation consent field is required', 'psyeventsmanager'),
			'cervical_program_required'		=> __('Cervical screening program consent field is required', 'psyeventsmanager'),
			'opinion_required'				=> __('Professional opinion consent field is required', 'psyeventsmanager'),
			'tandc_required'				=> __('Terms and conditions consent field is required', 'psyeventsmanager'),
			'region_required'				=> __('Region field is required', 'psyeventsmanager'),
			'contact_source_required'		=> __('Contact source field is required', 'psyeventsmanager'),
			'event_required'				=> __('Please select event', 'psyeventsmanager'),
			'participants_required'			=> __('Please enter number of participants', 'psyeventsmanager'),
		]
	];

	return $array;
}

if (!function_exists('pr')) {
	function pr($data, $die = true)
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		if ($die) {
			die();
		}
	}
}

function psyem_safe_b64encode($string)
{
	$data = base64_encode($string);
	$data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
	return $data;
}

function psyem_safe_b64decode($string)
{
	$data = str_replace(array('-', '_'), array('+', '/'), $string);
	$mod4 = strlen($data) % 4;
	if ($mod4) {
		$data .= substr('====', $mod4);
	}
	return base64_decode($data);
}

function psyem_safe_b64encode_id($string)
{
	$string   = '_@_@_' . $string . '_@_@_' . $string;
	$data     = base64_encode($string);
	$data     = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
	return $data;
}

function psyem_safe_b64decode_id($string)
{
	$data = str_replace(array('-', '_'), array('+', '/'), $string);
	$mod4 = strlen($data) % 4;
	if ($mod4) {
		$data .= substr('====', $mod4);
	}

	$string = base64_decode($data);
	$_id_arr  = (!empty($string)) ? explode('_@_@_', $string) : array();
	$_id_a    = (!empty($_id_arr) && isset($_id_arr[1])) ? $_id_arr[1] : 0;
	$_id_b    = (!empty($_id_arr) && isset($_id_arr[2])) ? $_id_arr[2] : 0;
	$_id      = ($_id_a == $_id_b) ? $_id_a : '';
	return $_id;
}

function psyem_GetPreviousYearsFromYear($inputYear)
{
	$previousYears = [];
	for ($i = 1; $i <= 83; $i++) {
		$previousYears[] = $inputYear - $i;
	}
	return $previousYears;
}

function psyem_SplitFullName($fullName)
{
	$resp = array();
	if (!empty($fullName)) {
		$fullName = trim($fullName);
		$nameParts = explode(' ', $fullName);
		$firstName = array_shift($nameParts);
		$lastName = implode(' ', $nameParts);
		$resp = [
			'first_name' => $firstName,
			'last_name' => $lastName
		];
	}
	return $resp;
}

function psyem_updateOption($key, $val)
{
	update_option($key, $val);
}

function psyem_GetOptionsWithPrefix($prefix = 'psyem_')
{
	global $wpdb;
	$options = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE %s",
			$wpdb->esc_like($prefix) . '%'
		)
	);

	$optionsArr = array();
	if (!empty($options)) {
		foreach ($options as $soption) {
			$optionsArr[$soption->option_name] = $soption->option_value;
		}
	}
	return $optionsArr;
}

function psyem_GetPostAllMetakeyValWithPrefix($post_id = 0, $prefix = '')
{

	global $wpdb;
	$resp = array();

	if ($post_id > 0 && !empty($prefix)) {
		// Prepare the SQL query
		$options = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key LIKE %s AND post_id = %d",
				$wpdb->esc_like($prefix) . '%',
				$post_id
			)
		);

		if (!empty($options)) {
			foreach ($options as $soption) {
				$resp[$soption->meta_key] = $soption->meta_value; // Use meta_key and meta_value
			}
		}
	}

	return $resp; // Return an empty array if post_id is not valid
}

function psytp_GetAllPostsWithMetaPrefix($post_type = '', $limit = 0,  $excludes = array(), $meta_prefix = '')
{
	$resp = [];
	if (!empty($post_type)) {
		$limit = $limit > 0 ? $limit  : -1;
		$args = array(
			'post_type'      => $post_type,  // Custom post type name
			'posts_per_page' => $limit,           		// Retrieve all posts
			'post_status'    => 'publish',    		// Only get published posts
			'nopaging' 	     => true
		);

		if (!empty($excludes) && is_array($excludes) && count($excludes)) {
			$args['post__not_in'] = $excludes;
		}

		$query = new WP_Query($args);

		// Loop through the posts and get the required fields
		if ($query->have_posts()) {
			foreach ($query->posts as $ppost) {
				$ppostId = @$ppost->ID;
				$postImage = get_the_post_thumbnail_url($ppostId, 'full');
				$defaultImage = PSYEM_ASSETS . '/images/default.jpg';
				$image = ($postImage && !empty($postImage)) ? $postImage : $defaultImage;

				$post_excerpt   = @$ppost->post_excerpt;
				$excerpt 		= psyem_GetCroppedExcerpt($post_excerpt);

				$postData = array(
					'ID'    		=> $ppostId,
					'title' 		=> @$ppost->post_title,
					'excerpt'      	=> @$excerpt,
					'image'        	=> $image,
					'link'			=> get_post_permalink($ppostId)
				);

				if (!empty($meta_prefix)) {
					$postData['meta_data'] = psyem_GetPostAllMetakeyValWithPrefix($ppostId, $meta_prefix);
				}
				$resp[$ppostId] = $postData;
			}
		}
	}
	return $resp;
}

function psyem_GetSinglePostWithMetaPrefix($post_type = '', $post_id = 0, $meta_prefix = '', $excerptLength = 55)
{
	$resp = [];
	if (!empty($post_type) && $post_id > 0) {
		 $lang = (function_exists('pll_get_post_language')) ? pll_get_post_language($post_id) : '';
		 $lang = (!empty($lang)) ? $lang : psyem_GetCurrentLocale();		

		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'p'              => $post_id,
			'lang'           => $lang
		);

		$query = new WP_Query($args);
		if ($query->have_posts()) {
			foreach ($query->posts as $ppost) {
				$ppostId 		= @$ppost->ID;
				$postImage 		= get_the_post_thumbnail_url($ppostId, 'full');
				$defaultImage 	= PSYEM_ASSETS . '/images/default.jpg';
				$image   		= ($postImage && !empty($postImage)) ? $postImage : $defaultImage;
				$post_excerpt   = @$ppost->post_excerpt;
				$excerpt 		= psyem_GetCroppedExcerpt($post_excerpt, $excerptLength);

				$postData = array(
					'ID'    		=> $ppostId,
					'title' 		=> @$ppost->post_title,
					'excerpt'      	=> $excerpt,
					'image'        	=> $image,
					'link'			=> (get_post_permalink($ppostId)),
					'edit_link'		=> (get_edit_post_link($ppostId)),
				);

				if (!empty($meta_prefix)) {
					$postData['meta_data'] = psyem_GetPostAllMetakeyValWithPrefix($ppostId, $meta_prefix);
				}
				$resp = $postData;
			}
		}
	}
	return $resp;
}

function psyem_getPostByMetakeyAndValue($post_type = '', $meta_key = '', $meta_val = '', $compare = '=')
{
	$resp = [];
	if (!empty($post_type) && !empty($meta_key) && !empty($meta_val)) {
		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => $meta_key,
					'value'   => $meta_val,
					'compare' => $compare, // can be '=', '!=', '>', '<', 'LIKE', etc.
				)
			),
		);

		$query = new WP_Query($args);
		if ($query->have_posts()) {
			foreach ($query->posts as $ppost) {
				$ppostId  = @$ppost->ID;
				$postImage = get_the_post_thumbnail_url($ppostId, 'full');
				$defaultImage = PSYEM_ASSETS . '/images/default.jpg';
				$image = ($postImage && !empty($postImage)) ? $postImage : $defaultImage;

				$post_excerpt   = @$ppost->post_excerpt;
				$excerpt 		= psyem_GetCroppedExcerpt($post_excerpt);

				$postData = array(
					'ID'    		=> $ppostId,
					'title' 		=> @$ppost->post_title,
					'excerpt'      	=> $excerpt,
					'image'        	=> $image,
					'link'			=> (get_post_permalink($ppostId)),
					'edit_link'		=> (get_edit_post_link($ppostId)),
				);
				$resp = $postData;
			}
		}
	}

	return $resp;
}

function psyem_GetPageLinkBySlug($slug, $post_type = 'page')
{

	$args = [
		'name'        => $slug,
		'post_type'   => $post_type,
		'post_status' => 'publish',
		'numberposts' => 1,
	];

	$posts = get_posts($args);

	if (!empty($posts)) {
		$post_id = @$posts[0]->ID;
		// Polylang support: get translated post ID
		if (function_exists('pll_get_post')) {
			$translated_id = pll_get_post($post_id);
			if ($translated_id > 0) {
				$post_id = $translated_id;
			}
		}

		return get_permalink($post_id);
	}

	return 'javascript:void(0);';
}

function psyem_GetPageLinkByID($post_id, $post_type = 'page')
{
	$permalink  = '';
	$post_id 	= ($post_id > 0) ? $post_id : 0;
	$post 		= get_post($post_id);

	if ($post && ($post->post_type === $post_type || empty($post_type))) {
		// Polylang support: get translated post ID
		if (function_exists('pll_get_post')) {
			$translated_id = pll_get_post($post_id);
			if ($translated_id > 0) {
				$post_id = $translated_id;
			}
		}
		$permalink = get_permalink($post_id);
	}

	return (!empty($permalink) && !is_wp_error($permalink)) ? $permalink : 'javascript:void(0);';
}


function psyem_GetCurrenySign()
{

	$current_currency       = (isset($_SESSION) && isset($_SESSION['current_currency'])) ? $_SESSION['current_currency'] : '';
	return ($current_currency == 'USD') ? '$' : 'HK$';
}

function psyem_GetCurrenyType()
{

	$current_currency       = (isset($_SESSION) && isset($_SESSION['current_currency'])) ? $_SESSION['current_currency'] : '';
	return ($current_currency == 'USD') ? 'USD' : 'HKD';
}

function psyem_GetCurrentLocale()
{
	$current_lang = '';
	if (function_exists('pll_current_language')) {
		$current_lang = pll_current_language(); // Polylang
	} elseif (function_exists('wpml_current_language')) {
		$current_lang = apply_filters('wpml_current_language', NULL); // WPML
	} else {
		$current_lang = get_locale(); // Default WordPress
	}

	return strtolower((!empty($current_lang) && in_array($current_lang, ['en', 'zh'])) ? $current_lang : 'en');
}

function psyem_FloatToInt($amount, $precision = 2)
{
	if ($amount > 0 && $precision > 0) {
		$amount = sprintf("%." . $precision . "f", $amount);
		return round(($amount * 100), $precision);
	}
	return 0;
}

function psyem_IntToFloat($amount, $precision = 2)
{
	if ($precision > 0) {
		return (number_format(($amount / 100), $precision, '.', ''));
	}
	return 0;
}

function psyem_roundPrecision($amount, $precision = 2)
{
	if ($precision > 0) {
		return number_format($amount, $precision, '.', '');
	}
}

function psyem_GetEventCheckoutPrices($CheckoutTickets = [], $event = [], $couponCode = '')
{

	$CurrencyType = psyem_GetCurrenyType();
	$CurrencySign = psyem_GetCurrenySign();
	$resp = array(
		'curreny_type' 		=> $CurrencyType,
		'curreny_sign' 		=> $CurrencySign,
		'checkout_price'  	=> 0.00,
		'discount_price'  	=> 0.00,
		'total_price'  		=> 0.00,
		'individual_total'	=> 0.00,
		'group_total'		=> 0.00,
		'coupon_data'		=> [],
		'cart_data'		    => [],
		'coupon_message'  	=> '',
		'redirect_to'  		=> '',
		'checkout_coupon'   => '',
	);

	// ['id' => 0, 'code' => '', 'type' => '', 'discount' => 0, 'amount' => 0]
	$cart_data = [];
	$coupon_data = [];
	$individual_total = 0;
	$group_total = 0;
	$total_discount = 0;
	$total_price = 0;
	$checkout_price = 0;
	if (
		(!empty($CheckoutTickets) && is_array($CheckoutTickets) && count($CheckoutTickets) > 0) &&
		(!empty($event) && is_array($event))
	) {

		$eventId             = @$event['ID'];
		$event_regtype       = get_post_meta(@$eventId, 'psyem_event_registration_type', true);
		$event_tickets       = get_post_meta(@$eventId, 'psyem_event_tickets', true);
		$event_tickets       = (!empty($event_tickets)) ? $event_tickets : [];
		$checkout_key        = psyem_safe_b64encode_id($eventId);

		$psyem_options                  = psyem_GetOptionsWithPrefix();
		$psyem_event_checkout_page_id   = @$psyem_options['psyem_event_checkout_page_id'];
		$cart_checkout_url   			= psyem_GetPageLinkByID($psyem_event_checkout_page_id)  . '?checkkey=' . $checkout_key;

		// PSYTODO - add checkout page link in settings
		$resp['redirect_to'] = '';

		if ((!empty($event_tickets) && is_array($event_tickets) && count($event_tickets) > 0) && ($event_regtype != 'Free')) {

			$psyem_options                  = psyem_GetOptionsWithPrefix();
			$currency_exchange_rate         = @$psyem_options['psyem_currency_exchange_rate'];

			foreach ($CheckoutTickets as $ticket_id => $participants_count) {
				if (($ticket_id > 0 && $participants_count > 0) && in_array($ticket_id, $event_tickets)) {
					$eventTicketInfo = psyem_GetSinglePostWithMetaPrefix('psyem-tickets', $ticket_id, 'psyem_ticket_');
					$ticketMeta      = @$eventTicketInfo['meta_data'];
					$ticketType      = @$ticketMeta['psyem_ticket_type'];
					$ticketPrice     = psyem_roundPrecision(@$ticketMeta['psyem_ticket_price']);
					$ticketPCount    = @$ticketMeta['psyem_ticket_group_participant'];

					if ($CurrencyType != 'USD') {
						$ticketPrice = psyem_ConvertUsdToHkd($ticketPrice, $currency_exchange_rate);
					}
					$ticketPrice     = psyem_roundPrecision($ticketPrice);

					if ($ticketType == 'Individual' && $ticketPrice > 0) {
						$individual_ticket_price = psyem_roundPrecision(($ticketPrice * $participants_count));
						$individual_total 		 = psyem_roundPrecision(($individual_total + $individual_ticket_price));

						$cart_data[$ticket_id] = [
							'type' 			=> 'Individual',
							'price' 		=> $ticketPrice,
							'choosen_count' => $participants_count,
							'member_count' 	=> $participants_count,
							'ticket_meta' 	=> $ticketMeta,
							'title' 		=> @$eventTicketInfo['title'],
							'excerpt' 		=> @$eventTicketInfo['excerpt'],
							'cart_price'	=> $individual_ticket_price
						];

						// Apply couponparticipants_count
						if (!empty($couponCode)) {
							$ticketCouponsArr = get_post_meta(@$ticket_id, 'psyem_ticket_coupons', true);
							if (!empty($ticketCouponsArr) && is_array($ticketCouponsArr)) {
								$couponExist 	= psyem_getPostByMetakeyAndValue('psyem-coupons', 'psyem_coupon_unique_code', $couponCode);
								$couponID 		= @$couponExist['ID'] ?? 0;
								$couponData		= psyem_GetSinglePostWithMetaPrefix('psyem-coupons', $couponID, 'psyem_coupon_');
								if (in_array($couponID, $ticketCouponsArr)) {
									if (!empty($couponData)) {
										$couponMetaData 	= @$couponData['meta_data'];
										$couponType     	= @$couponMetaData['psyem_coupon_type'];
										$couponFixedPrice   = @$couponMetaData['psyem_coupon_discount_amount'];
										$couponPercentPrice = @$couponMetaData['psyem_coupon_discount_percentage'];

										$couponExpiryDate   = @$couponMetaData['psyem_coupon_expiry_date'];
										$isExpired          = psyem_isDateExpired($couponExpiryDate);
										if (!$isExpired) {
											$discount_price = 0;

											if ($couponType == 'Fixed') {
												if ($couponFixedPrice > 0) {
													if ($CurrencyType != 'USD') {
														//$couponFixedPrice = psyem_ConvertUsdToHkd($couponFixedPrice, $currency_exchange_rate);
													}

													$discount_price    			= psyem_roundPrecision(($couponFixedPrice * $participants_count));
													$coupon_data[$ticket_id]    = ['id' => $couponID, 'code' => $couponCode,  'type' => 'Fixed', 'discount' => $couponFixedPrice, 'amount' => $discount_price];
												}
											}

											if ($couponType == 'Percentage') {
												if ($couponPercentPrice > 0) {
													$single_coupon_price 	    = psyem_roundPrecision((($couponPercentPrice / 100) * $ticketPrice));

													if ($CurrencyType != 'USD') {
														//$single_coupon_price = psyem_ConvertUsdToHkd($single_coupon_price, $currency_exchange_rate);
													}

													$discount_price             = psyem_roundPrecision(($single_coupon_price * $participants_count));
													$coupon_data[$ticket_id]  	= ['id' => $couponID, 'code' => $couponCode,  'type' => 'Percentage', 'discount' => $couponPercentPrice, 'amount' => $discount_price];
												}
											}

											if ($discount_price > 0) {
												$discounted_amount = psyem_roundPrecision(($individual_ticket_price - $discount_price));
												$total_discount    = ($discounted_amount > 0) ? psyem_roundPrecision(($discount_price + $total_discount)) : $total_discount;
											}
										} else {
											$resp['coupon_message'] =  __('Your coupon code is expired', 'psyeventsmanager');
										}
									} else {
										$resp['coupon_message'] =  __('Your coupon code is invalid', 'psyeventsmanager');
									}
								} else {
									$resp['coupon_message'] =  __('Please check! Your coupon code is invalid', 'psyeventsmanager');
								}
							} else {
								$resp['coupon_message'] =  __('No coupons are allowed for booking this event', 'psyeventsmanager');
							}
						}
					}

					if ($ticketType == 'Group' && $ticketPrice  > 0 && $ticketPCount > 0) {
						$group_ticket_price = psyem_roundPrecision(($ticketPrice * $participants_count));
						$group_total 		= psyem_roundPrecision(($group_total + $group_ticket_price));

						$cart_data[$ticket_id] = [
							'type' 			=> 'Group',
							'price' 		=> $ticketPrice,
							'choosen_count' => $participants_count,
							'member_count' 	=> $ticketPCount,
							'ticket_meta' 	=> $ticketMeta,
							'title' 		=> @$eventTicketInfo['title'],
							'excerpt' 		=> @$eventTicketInfo['excerpt'],
							'cart_price'	=> $group_ticket_price
						];
					}
				}
			}

			$resp['cart_data'] 			= $cart_data;
			$resp['coupon_data'] 		= $coupon_data;
			$resp['individual_total'] 	= $individual_total;
			$resp['group_total'] 		= $group_total;
			$resp['total_discount'] 	= $total_discount;
			$resp['discount_price'] 	= $total_discount;
			$resp['checkout_coupon'] 	= ($total_discount > 0) ? $couponCode : '';

			$total_price 				= psyem_roundPrecision(($individual_total + $group_total));
			$resp['total_price'] 		= $total_price;

			$checkout_price 			= psyem_roundPrecision(($total_price - $total_discount));
			$resp['checkout_price'] 	= $checkout_price;

			$resp['redirect_to'] 		= ($checkout_price > 0) ? $cart_checkout_url : '';

			$_SESSION[$checkout_key] 	= $resp;
		}
	}
	return $resp;
}

function psyem_UnsetCartCheckoutData($cart_info_data = [])
{

	$resp = [];
	if (!empty($cart_info_data) && is_array($cart_info_data)) {
		$cart_data          = (isset($cart_info_data['cart_data']) && !empty($cart_info_data['cart_data'])) ? $cart_info_data['cart_data'] : [];
		$cart_unset         = [];

		if (!empty($cart_data) && is_array($cart_data)) {
			foreach ($cart_data as $cartTicketId => $cartTicketInfo) {
				$ticketMeta = @$cartTicketInfo['ticket_meta'];
				if (isset($ticketMeta['psyem_ticket_coupons'])) {
					unset($ticketMeta['psyem_ticket_coupons']);
				}
				if (isset($ticketMeta['psyem_ticket_type'])) {
					unset($ticketMeta['psyem_ticket_type']);
				}
				if (isset($ticketMeta['psyem_ticket_group_participant'])) {
					unset($ticketMeta['psyem_ticket_group_participant']);
				}
				if (isset($cartTicketInfo['excerpt'])) {
					unset($cartTicketInfo['excerpt']);
				}
				if (isset($cartTicketInfo['title'])) {
					unset($cartTicketInfo['title']);
				}
				$cartTicketInfo['ticket_meta'] = $ticketMeta;
				$cart_unset[$cartTicketId] = $cartTicketInfo;
			}
			$cart_info_data['cart_data'] = $cart_unset;
		}

		if (isset($cart_info_data['redirect_to'])) {
			unset($cart_info_data['redirect_to']);
		}
		$resp = $cart_info_data;
	}
	return $resp;
}

function psyem_isDateExpired($ymdDate)
{
	if (!empty($ymdDate)) {
		$currentDate = current_time('Y-m-d');
		return (strtotime($currentDate) > strtotime($ymdDate));
	}
	return false;
}

function psyem_GetCroppedExcerpt($content, $length = 55)
{
	$resp = '';
	if (!empty($content)) {
		$content = wp_strip_all_tags($content);
		if (!empty($content)) {
			$words = explode(' ', $content);
			if (count($words) > $length) {
				$words = array_slice($words, 0, $length);
				$resp = implode(' ', $words) . '...';
			} else {
				$resp = $content;
			}
		}
	}
	return $content;
}

function psyem_IsEventBookingAllowed($EventId = 0, $eventWithMetaInfo = [])
{

	try {
		if (empty($eventWithMetaInfo) && $EventId > 0) {
			$eventWithMetaInfo  = psyem_GetSinglePostWithMetaPrefix('psyem-events', $EventId, 'psyem_event_');
		}

		$EventId 				= @$eventWithMetaInfo['ID'];
		$psyemEventMeta         = @$eventWithMetaInfo['meta_data'];

		if ($EventId > 0) {
			// registration type
			$EventRegistrationType          = @$psyemEventMeta['psyem_event_registration_type'];
			if ($EventRegistrationType == 'Closed') {
				return 'No';
			}

			// start date time
			$EventStartDate         		= @$psyemEventMeta['psyem_event_startdate'];
			$EventStartTime                	= @$psyemEventMeta['psyem_event_starttime'];
			$startDateTime                  = $EventStartDate . ' ' . $EventStartTime;
			if (empty($EventStartDate) || empty($EventStartTime)) {
				return 'No';
			}

			// end date time 
			$EventEndDate                  	= @$psyemEventMeta['psyem_event_enddate'];
			$EventEndTime                  	= @$psyemEventMeta['psyem_event_endtime'];
			$endDateTime                    = $EventEndDate . ' ' . $EventEndTime;
			if (empty($EventEndDate) || empty($EventEndTime)) {
				return 'No';
			}

			// validate date time 
			$startDateTimeObj  = new DateTime($startDateTime, new DateTimeZone('UTC'));
			$endDateTimeObj    = new DateTime($endDateTime, new DateTimeZone('UTC'));
			$currentDateTime   = new DateTime('now', new DateTimeZone('UTC'));
			if ($endDateTimeObj < $startDateTimeObj) {
				return 'No';
			}

			// with current date time
			if ($startDateTimeObj > $currentDateTime) {
				return 'No';
			}

			// closing days
			$EventRegistrationClosingDays   = @$psyemEventMeta['psyem_event_registration_closing'];
			if (!empty($EventRegistrationClosingDays) && $EventRegistrationClosingDays > 0) {
				$startDateTimeObj->modify('+' . $EventRegistrationClosingDays . ' days');
				if ($currentDateTime >= $startDateTimeObj) {
					return 'No';
				}
			}

			$eventRegType       = (isset($psyemEventMeta['psyem_event_registration_type'])) ? $psyemEventMeta['psyem_event_registration_type'] :  '';
			if (!($eventRegType == 'Paid') && !($eventRegType == 'Free')) {
				return 'No';
			}
		}
	} catch (\Exception $e) {
		error_log('psyem_IsEventBookingAllowed LOGO ERROR :: ' . $e->getMessage());
	}
	return 'Yes';
}

function psyem_UpdateOrderUsedSlotsCount($order, $event, $participant)
{

	$resp = [];

	if (!empty($order) && !empty($event) && !empty($participant)) {

		$order_meta           	= @$order['meta_data'];
		$order_id           	= @$order['ID'];
		$orderParticipantsArr 	= get_post_meta(@$order_id, 'psyem_order_participants', true);
		$orderParticipantsIds 	= (!empty($orderParticipantsArr) && is_array($orderParticipantsArr)) ? array_values($orderParticipantsArr) : [];

		$event_meta           	= @$event['meta_data'];
		$event_id         		= @$event['ID'];

		$participant_meta     	= @$participant['meta_data'];
		$participant_id         = @$participant['ID'];

		if (!empty($orderParticipantsIds) && is_array($orderParticipantsIds) && in_array($participant_id, $orderParticipantsIds)) {
			$orderUsedSlotsCount	= @$order_meta['psyem_order_used_slots'];
			$orderUsedSlotsCount    = (!empty($orderUsedSlotsCount) && $orderUsedSlotsCount > 0) ? $orderUsedSlotsCount : 0;

			$orderUsedSlotsInfoArr  = get_post_meta(@$order_id, 'psyem_order_used_slots_info', true);
			$orderUsedSlotsInfoArr  = (!empty($orderUsedSlotsInfoArr) && is_array($orderUsedSlotsInfoArr)) ? $orderUsedSlotsInfoArr : [];

			if (isset($orderUsedSlotsInfoArr[$participant_id]) && !empty($orderUsedSlotsInfoArr[$participant_id])) {
				return array('status' => 'Already');
			}

			if ($participant_id > 0) {
				$orderUsedSlotsInfoArr[$participant_id] = array('Event' => $event_id, 'Order' => $order_id);
				update_post_meta($order_id, 'psyem_order_used_slots_info', $orderUsedSlotsInfoArr);

				$orderUsedSlotsInfoArr = get_post_meta(@$order_id, 'psyem_order_used_slots_info', true);
				$orderUsedSlotsInfoArr = (!empty($orderUsedSlotsInfoArr) && is_array($orderUsedSlotsInfoArr)) ? $orderUsedSlotsInfoArr : [];
				$orderUsedSlotsCount   = (!empty($orderUsedSlotsInfoArr) && is_array($orderUsedSlotsInfoArr)) ? count($orderUsedSlotsInfoArr) : 0;

				if ($orderUsedSlotsCount > 0 && !empty($orderUsedSlotsInfoArr)) {
					update_post_meta($order_id, 'psyem_order_used_slots', $orderUsedSlotsCount);
					// update event slots count
					psyem_UpdateEventSlotsCount($event, 0, 1);
					psyem_UpdateEventAttendeesCount($event, $participant);
					$resp = $orderUsedSlotsInfoArr;
				}
			}
		}
	}
	return $resp;
}

function psyem_UpdateEventAttendeesCount($event, $participant)
{

	$event_meta           	= @$event['meta_data'];
	$event_id         		= @$event['ID'];

	$participant_meta     	= @$participant['meta_data'];
	$participant_id         = @$participant['ID'];

	$resp = array();
	if ($event_id > 0 && $participant_id > 0) {
		$eventAttendeesInfoArr  	 = get_post_meta(@$event_id, 'psyem_event_attendees_info', true);
		$eventAttendeesInfoArr       = (!empty($eventAttendeesInfoArr) && is_array($eventAttendeesInfoArr)) ? $eventAttendeesInfoArr : [];
		$eventAttendeesCount   		 = (!empty($eventAttendeesInfoArr) && is_array($eventAttendeesInfoArr)) ? count($eventAttendeesInfoArr) : 0;

		if (isset($eventAttendeesInfoArr[$participant_id]) && !empty($eventAttendeesInfoArr[$participant_id]) && $eventAttendeesInfoArr[$participant_id] > 0) {
			// ignore particpant is already updated as attendees
		} else {
			$eventAttendeesInfoArr[$participant_id] = $participant_id;
			update_post_meta($event_id, 'psyem_event_attendees_info', $eventAttendeesInfoArr);
			$resp  	 = get_post_meta(@$event_id, 'psyem_event_attendees_info', true);
		}
	}
	return $resp;
}

function psyem_UpdateEventSlotsCount($event, $total = 0, $used = 0)
{
	$event_meta           	 = @$event['meta_data'];
	$event_id         		 = @$event['ID'];
	$psyem_event_total_slots = @$event_meta['psyem_event_total_slots'];
	$psyem_event_total_slots = ($psyem_event_total_slots > 0) ? $psyem_event_total_slots : 0;

	$psyem_event_used_slots  = @$event_meta['psyem_event_used_slots'];
	$psyem_event_used_slots = ($psyem_event_used_slots > 0) ? $psyem_event_used_slots : 0;

	$resp = array('psyem_event_total_slots' => 0, 'psyem_event_used_slots' => 0);
	if ($event_id > 0) {
		$psyem_event_total_slots = ($total > 0) ? ($psyem_event_total_slots + $total) : $psyem_event_total_slots;
		$psyem_event_used_slots  = ($used > 0) ? ($psyem_event_used_slots + $used) : $psyem_event_used_slots;
		update_post_meta($event_id, 'psyem_event_total_slots', $psyem_event_total_slots);
		update_post_meta($event_id, 'psyem_event_used_slots', $psyem_event_used_slots);

		$resp = array('psyem_event_total_slots' => $psyem_event_total_slots, 'psyem_event_used_slots' => $psyem_event_used_slots);
	}
	return $resp;
}

function psyem_GetSinglePostWithMetaPrefixForApi($post_type = '', $post_id = 0, $meta_prefix = '', $excerptLength = 55)
{
	$resp = [];
	if (!empty($post_type) && $post_id > 0) {
		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'p'              => $post_id,
		);
		$query = new WP_Query($args);
		if ($query->have_posts()) {
			foreach ($query->posts as $ppost) {
				$ppostId 		= @$ppost->ID;
				$postImage 		= get_the_post_thumbnail_url($ppostId, 'full');
				$defaultImage 	= PSYEM_ASSETS . '/images/default.jpg';
				$image   		= ($postImage && !empty($postImage)) ? $postImage : $defaultImage;
				$post_excerpt   = @$ppost->post_excerpt;
				$excerpt 		= psyem_GetCroppedExcerpt($post_excerpt, $excerptLength);

				$postData = array(
					'ID'    		=> $ppostId,
					'title' 		=> @$ppost->post_title,
					'excerpt'      	=> $excerpt,
					'image'        	=> $image,
				);

				if (!empty($meta_prefix)) {
					$postData['meta_data'] = psyem_GetPostAllMetakeyValWithPrefix($ppostId, $meta_prefix);

					if (!empty($postData['meta_data'])) {
						$event_medias       = get_post_meta($ppostId, 'psyem_event_media_urls', true);

						$meta_data          = @$postData['meta_data'];
						@$meta_data['psyem_event_media_urls']  = $event_medias;

						if (isset($meta_data['psyem_event_partners'])) {
							unset($meta_data['psyem_event_partners']);
						}
						if (isset($meta_data['psyem_event_speakers'])) {
							unset($meta_data['psyem_event_speakers']);
						}
						if (isset($meta_data['psyem_event_coupons'])) {
							unset($meta_data['psyem_event_coupons']);
						}
						if (isset($meta_data['psyem_event_attendees_info'])) {
							unset($meta_data['psyem_event_attendees_info']);
						}
						$postData['meta_data'] = $meta_data;
					}
				}
				$resp = $postData;
			}
		}
	}
	return $resp;
}

function psyem_GetAllEventsForOrder($params = array())
{

	$resp = ['pagination' => [], 'data' => []];

	// limit offset
	$limit 				= @$params['limit'];
	$limit              = ($limit > 0) ? (int) $limit : 20;
	$current_page   	= @$params['cpage'];
	$current_page   	= ($current_page > 0) ? (int) $current_page : 1;
	$offset 			= (($current_page - 1) * $limit);

	$search_key 		= @$params['search_key'];
	$search_term        = (!empty($search_key)) ? $search_key : '';

	$search_filter 		= @$params['search_filter'];
	$search_filter      = (!empty($search_filter) && in_array($search_filter, ['upcoming', 'past'])) ? $search_filter : '';

	$from_date 			= @$params['from_date'];
	$to_date 			= @$params['to_date'];
	$signup_type 		= @$params['signup_type'];

	$args  = array(
		'post_type'      => 'psyem-events',
		'posts_per_page' => $limit,
		'paged'          => $current_page,
		'post_status'    => 'publish',
		'meta_key' 		 => 'psyem_event_startdate',
		'orderby'  		 => 'meta_value',
		'order'    		 => 'DESC',
	);

	$meta_query = array(
		array(
			'key'     => 'psyem_event_startdate',
			'value'   => '',
			'compare' => '!='
		),
		array(
			'key'     => 'psyem_event_starttime',
			'value'   => '',
			'compare' => '!='
		),
		array(
			'key'     => 'psyem_event_enddate',
			'value'   => '',
			'compare' => '!='
		),
		array(
			'key'     => 'psyem_event_endtime',
			'value'   => '',
			'compare' => '!='
		),
		array(
			'key'     => 'psyem_event_registration_type',
			'value'   => '',
			'compare' => '!='
		),
		array(
			'key'     => 'psyem_event_registration_type',
			'value'   => 'Closed',
			'compare' => '!='
		)
	);

	if (!empty($search_filter)) {
		if ($search_filter == 'upcoming') {
			$meta_query[] = array(
				'relation' => 'OR',
				array(
					'key'     => 'psyem_event_startdate',
					'value'   => date('Y-m-d'),
					'compare' => '>=',
					'type'    => 'DATE'
				),
				array(
					'key'     => 'psyem_event_enddate',
					'value'   => date('Y-m-d'),
					'compare' => '>=',
					'type'    => 'DATE'
				),
			);
		}

		if ($search_filter == 'past') {
			$meta_query[] = array(
				array(
					'key'     => 'psyem_event_enddate',
					'value'   => date('Y-m-d'),
					'compare' => '<',
					'type'    => 'DATE'
				),
			);
		}
	}

	if (!empty($search_term)) {
		$search_term = sanitize_text_field($search_term);
		$args['search_term'] = $search_term;
	}

	if (!empty($from_date)) {
		$from_date = sanitize_text_field($from_date);
		$args['from_date'] = $from_date;
	}

	if (!empty($to_date)) {
		$to_date = sanitize_text_field($to_date);
		$args['to_date'] = $to_date;
	}

	if (!empty($signup_type)) {
		$signup_type = sanitize_text_field($signup_type);
		$args['signup_type'] = $signup_type;
	}

	$args['meta_query'] = $meta_query;
	$query = new WP_Query($args);

	$resp['pagination'] = array(
		'current_page' 	=> $current_page,
		'total_page'   	=> @$query->max_num_pages ?? 0,
		'posts_count'   => @$query->post_count ?? 0,
		'limit'   		=> $limit,
	);

	// Loop through the posts and get the required fields
	if ($query->have_posts()) {
		foreach ($query->posts as $ppost) {
			$ppostId 		= @$ppost->ID;
			$postImage 		= get_the_post_thumbnail_url($ppostId, 'full');
			$defaultImage 	= PSYEM_ASSETS . '/images/default.jpg';
			$image 			= ($postImage && !empty($postImage)) ? $postImage : $defaultImage;

			$post_excerpt   = @$ppost->post_excerpt;
			$excerpt 		= psyem_GetCroppedExcerpt($post_excerpt);

			$postData 		= array(
				'ID'    	=> $ppostId,
				'title' 	=> @$ppost->post_title,
				'excerpt'   => @$excerpt,
				'image'     => $image,
				'link'		=> get_post_permalink($ppostId),
				'date'		=> get_the_date('d F Y', $ppost)
			);
			$meta_prefix 			= 'psyem_event_';
			$meta_data 				= psyem_GetPostAllMetakeyValWithPrefix($ppostId, $meta_prefix);
			$postData['meta_data'] 	= $meta_data;

			$psyem_event_startdate  = @$meta_data['psyem_event_startdate'];
			$psyem_event_enddate    = @$meta_data['psyem_event_enddate'];
			try {
				$startDate = new DateTime(@$psyem_event_startdate);
				$endDate = new DateTime(@$psyem_event_enddate);
				$formattedStartDate = $startDate->format('d F Y');
				$formattedEndDate   = $endDate->format('d F Y');
				$postData['start_date']  = $formattedStartDate;
				$postData['end_date']  = $formattedEndDate;
			} catch (\Exception $e) {
			}
			$resp['data'][$ppostId] 		= $postData;
		}
	}

	return $resp;
}

function psyem_GetAllEventsForForApi($params = array())
{
	$resp 				= ['pagination' => [], 'data' => []];

	// limit offset
	$limit 				= @$params['limit'];
	$limit              = ($limit > 0) ? (int) $limit : 20;
	$current_page   	= @$params['paged'];
	$current_page   	= ($current_page > 0) ? (int) $current_page : 1;
	$offset 			= (($current_page - 1) * $limit);

	// search term
	$search_key 		= @$params['search_key'];
	$search_term        = (!empty($search_key)) ? $search_key : '';

	$from_date 			= @$params['from_date'];
	$to_date 			= @$params['to_date'];
	$signup_type 		= @$params['signup_type'];

	$args  = array(
		'post_type'      => 'psyem-events',
		'posts_per_page' => $limit,
		'paged'          => $current_page,
		'post_status'    => 'publish',
		'orderby'        => 'ID',
		'order'          => 'DESC'
	);

	$meta_query = array(
		array(
			'key'     => 'psyem_event_startdate',
			'value'   => '',
			'compare' => '!='
		),
		array(
			'key'     => 'psyem_event_starttime',
			'value'   => '',
			'compare' => '!='
		),
		array(
			'key'     => 'psyem_event_enddate',
			'value'   => '',
			'compare' => '!='
		),
		array(
			'key'     => 'psyem_event_endtime',
			'value'   => '',
			'compare' => '!='
		),
		array(
			'key'     => 'psyem_event_registration_type',
			'value'   => '',
			'compare' => '!='
		)
	);

	if (!empty($search_term)) {
		$search_term = sanitize_text_field($search_term);
		$args['search_term'] = $search_term;
	}

	if (!empty($from_date)) {
		$from_date = sanitize_text_field($from_date);
		if (psyem_IsValidDate($from_date)) {
			$args['from_date'] = $from_date;
			$meta_query[] = array(
				'key'     => 'psyem_event_startdate',
				'value'   => $from_date,
				'compare' => '>=',
			);
		}
	}

	if (!empty($to_date)) {
		$to_date = sanitize_text_field($to_date);
		if (psyem_IsValidDate($to_date)) {
			$args['to_date'] = $to_date;
			$meta_query[] = array(
				'key'     => 'psyem_event_enddate',
				'value'   => $to_date,
				'compare' => '<=',
			);
		}
	}

	if (!empty($signup_type) && in_array($signup_type, ['Paid', 'Free'])) {
		$signup_type = sanitize_text_field($signup_type);
		$args['signup_type'] = $signup_type;
		$meta_query[] = array(
			'key'     => 'psyem_event_registration_type',
			'value'   => $signup_type,
			'compare' => '='
		);
	}

	$args['meta_query'] = $meta_query;
	$query = new WP_Query($args);

	$resp['pagination'] = array(
		'current_page' 	=> $current_page,
		'total_page'   	=> @$query->max_num_pages ?? 0,
		'posts_count'   => @$query->post_count ?? 0,
		'limit'   		=> $limit,
	);

	if ($query->have_posts()) {
		foreach ($query->posts as $ppost) {

			$ppostId 		= @$ppost->ID;
			$postImage 		= get_the_post_thumbnail_url($ppostId, 'full');
			$defaultImage 	= PSYEM_ASSETS . '/images/default.jpg';
			$image 			= ($postImage && !empty($postImage)) ? $postImage : $defaultImage;

			$post_excerpt   = @$ppost->post_excerpt;
			$excerpt 		= psyem_GetCroppedExcerpt($post_excerpt);

			$postData 		= array(
				'ID'    	=> $ppostId,
				'title' 	=> @$ppost->post_title,
				'excerpt'   => @$excerpt,
				'image'     => $image
			);
			$meta_prefix 			= 'psyem_event_';
			$postData['meta_data'] 	= psyem_GetPostAllMetakeyValWithPrefix($ppostId, $meta_prefix);
			if (!empty($postData['meta_data'])) {
				$meta_data          = @$postData['meta_data'];
				if (isset($meta_data['psyem_event_partners'])) {
					unset($meta_data['psyem_event_partners']);
				}
				if (isset($meta_data['psyem_event_speakers'])) {
					unset($meta_data['psyem_event_speakers']);
				}
				if (isset($meta_data['psyem_event_coupons'])) {
					unset($meta_data['psyem_event_coupons']);
				}
				if (isset($meta_data['psyem_event_media_urls'])) {
					unset($meta_data['psyem_event_media_urls']);
				}
				if (isset($meta_data['psyem_event_attendees_info'])) {
					unset($meta_data['psyem_event_attendees_info']);
				}
				$postData['meta_data']      = $meta_data;
			}
			$resp['data'][$ppostId] 		= $postData;
		}
	}

	return $resp;
}

function psyem_GetPostPreviewUrl($posttype, $postid)
{
	$preview_url = site_url('?post_type=' . $posttype . '&p=' . $postid . '&preview=true');
	return esc_url($preview_url);
}

function psyem_ManageCreateOfflineRegistration($postData)
{
	global $wpdb;

	$resp      = array(
		'status'     => 'error',
		'message'    => __('Offline registration order has been failed to create', 'psyeventsmanager'),
		'data'       => []
	);

	$offline_event           = @$postData['offline_event'];
	$offline_firstname       = @$postData['offline_firstname'];
	$offline_lastname        = @$postData['offline_lastname'];
	$offline_email           = @$postData['offline_email'];
	$offline_company         = @$postData['offline_company'];
	$offline_tickets         = @$postData['offline_tickets'];

	$checkout_tickets   = $offline_tickets;
	$participant_name   = $offline_firstname . ' ' . $offline_lastname;
	$participant_email  = $offline_email;

	$EventId            = $offline_event;
	$psyemEventInfo     = psyem_GetSinglePostWithMetaPrefix('psyem-events', $EventId, 'psyem_event_');
	$psyemEventMeta     = @$psyemEventInfo['meta_data'];

	$isBookingAllowed   = psyem_IsEventBookingAllowed(0, $psyemEventInfo);
	if ($isBookingAllowed != 'Yes') {
		$resp      = array(
			'status'     => 'error',
			'message'    => __('Registration is not allowed for this event', 'psyeventsmanager'),
			'data'       => []
		);
		return $resp;
	}

	if (!empty($psyemEventInfo) && !empty($psyemEventMeta)) {
		try {
			$dbStatus         = 'Offline';
			$current_time     = current_time('mysql');
			$current_time_gmt = current_time('mysql', 1); // Get GMT time
			// Create order
			$orderTitle = ucfirst($participant_name) . ' - Event Order - ' . $EventId . ' -- ' . $dbStatus;

			$orderPostContent = @$psyemEventInfo['title'] . ' ' . strtolower($psyemEventInfo['title']) . ' ' . $participant_name . ' ' . $participant_email . ' ' . strtolower($participant_name) . ' ' . strtolower($participant_email);

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
				update_post_meta($inserted_order_id, 'psyem_order_total_slots', $checkout_tickets);

				update_post_meta($inserted_order_id, 'psyem_order_checkout_amount', 0.00);
				update_post_meta($inserted_order_id, 'psyem_order_total_amount', 0.00);
				update_post_meta($inserted_order_id, 'psyem_order_stripe_amount', 0.00);
				update_post_meta($inserted_order_id, 'psyem_order_recieved_amount', 0.00);
				update_post_meta($inserted_order_id, 'psyem_order_payment_source', $dbStatus);

				update_post_meta($inserted_order_id, 'psyem_order_used_slots', 0);
				update_post_meta($inserted_order_id, 'psyem_order_used_slots_info', []);

				update_post_meta($inserted_order_id, 'psyem_order_coupon', '');
				update_post_meta($inserted_order_id, 'psyem_order_coupon_data', []);

				$participantPostContent = $orderEnc . ' ' . @$psyemEventInfo['title'] . ' ' . strtolower($psyemEventInfo['title']) . ' ' . $participant_name . ' ' . $participant_email . ' ' . strtolower($participant_name) . ' ' . strtolower($participant_email);

				// check if exiting particpant
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
						'post_content'  => $participantPostContent,
						'post_type'     => 'psyem-participants',
						'post_date'     => $current_time,
						'post_date_gmt' => $current_time_gmt,
					);
					$insertPResp                = $wpdb->insert($wpdb->posts, $post_data);
					$inserted_participant_id    = @$wpdb->insert_id;
					if ($inserted_participant_id > 0) {
						$pNameArr 		  = psyem_SplitFullName($participant_name);
						$participantFName = @$pNameArr['first_name'];
						$participantLName = @$pNameArr['last_name'];

						update_post_meta($inserted_participant_id, 'psyem_participant_first_name', ucfirst($participantFName));
						update_post_meta($inserted_participant_id, 'psyem_participant_last_name', ucfirst($participantLName));
						update_post_meta($inserted_participant_id, 'psyem_participant_company', $offline_company);
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
					'message'    => __('Offline registration order has been successfully created', 'psyeventsmanager'),
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
	}
	return $resp;
}

function psyem_IsValidDate($date, $format = 'Y-m-d')
{
	$dateTime = DateTime::createFromFormat($format, $date);
	if ($dateTime && $dateTime->format($format) === $date) {
		return true;
	}
	return false;
}

function psyem_GetAllCategoriesWithPosts($taxonomy, $havePosts = true, $post_type = '')
{
	$resp = array();
	if (!empty($taxonomy)) {
		$lang               = psyem_GetCurrentLocale();
		$terms 				= get_terms(array(
			'taxonomy' 		=> $taxonomy,
			'hide_empty' 	=> $havePosts,
			'orderby' 		=> 'name',
			'order' 		=> 'ASC',
			'lang'           => $lang
		));

		if (!is_wp_error($terms) && !empty($terms)) {
			foreach ($terms as $term) {
				$term_taxonomy_id   = @$term->term_taxonomy_id;
				$term_id   			= @$term->term_id;
				$term_name 			= @$term->name;
				$term_slug 			= @$term->slug;
				$term_count 		= @$term->count;

				$category_icon_image_url = get_term_meta($term_id, 'category_icon_image_url', true);
				$defaultImage 			 = PSYEM_ASSETS . '/images/default.jpg';
				$term_image 			 = (!empty($category_icon_image_url)) ? $category_icon_image_url : $defaultImage;
				if ($term_id > 0) {
					$resp[$term_id] 	= array(
						'term_taxonomy_id' 	=> $term_taxonomy_id,
						'term_id' 	  		=> $term_id,
						'term_name'  		=> $term_name,
						'term_slug'  		=> $term_slug,
						'term_count' 		=> $term_count,
						'term_image' 		=> $term_image,
						'term_posts' 		=> []
					);

					if (!empty($post_type)) {
						$args = array(
							'post_type' 	 => $post_type,
							'posts_per_page' => -1,
							'post_status'    => 'publish',
							'orderby'        => 'post_title',
							'order'          => 'ASC',
							'tax_query' => array(
								array(
									'taxonomy' => $taxonomy,
									'field'    => 'term_id',
									'terms'    => $term_id,
								)
							)
						);
						$term_posts = get_posts($args);
						$resp[$term_id]['term_posts'] = $term_posts;
					}
				}
			}
		}
	}
	return $resp;
}

function psyem_GetEventPartnersWithCategory($event_partners = [])
{
	$resp = array();
	if (!empty($event_partners) && is_array($event_partners)) {
		foreach ($event_partners as $eventPartnerCatId => $eventPartners) {
			if ($eventPartnerCatId > 0 && (!empty($eventPartners) && is_array($eventPartners))) {
				$taxonomy 			= 'psyem-partner-category';
				$eventPartnerCat 	= get_term($eventPartnerCatId, $taxonomy);
				if (!empty($eventPartnerCat)) {
					$resp[$eventPartnerCatId] 	= array(
						'term_taxonomy_id' 	=> @$eventPartnerCat->term_taxonomy_id,
						'term_id' 	  		=> @$eventPartnerCat->term_id,
						'term_name'  		=> @$eventPartnerCat->name,
						'term_slug'  		=> @$eventPartnerCat->slug,
						'term_count' 		=> @$eventPartnerCat->count,
						'term_posts' 		=> []
					);

					foreach ($eventPartners as $eventPartnerId) {
						$partnerInfo = psyem_GetSinglePostWithMetaPrefix('psyem-partners', $eventPartnerId, 'psyem_partner_', 20);
						if (!empty($partnerInfo)) {
							$resp[$eventPartnerCatId]['term_posts'][] = $partnerInfo;
						}
					}
				}
			}
		}
	}
	return $resp;
}

function psyem_GetEventSpeakersWithCategory($event_speakers = [])
{
	$resp = array();
	if (!empty($event_speakers) && is_array($event_speakers)) {
		foreach ($event_speakers as $eventSpeakerCatId => $eventSpeakers) {
			if ($eventSpeakerCatId > 0 && (!empty($eventSpeakers) && is_array($eventSpeakers))) {
				$taxonomy 			= 'psyem-speaker-category';
				$eventSpeakerCat 	= get_term($eventSpeakerCatId, $taxonomy);
				if (!empty($eventSpeakerCat)) {
					$resp[$eventSpeakerCatId] 	= array(
						'term_taxonomy_id' 	=> @$eventSpeakerCat->term_taxonomy_id,
						'term_id' 	  		=> @$eventSpeakerCat->term_id,
						'term_name'  		=> @$eventSpeakerCat->name,
						'term_slug'  		=> @$eventSpeakerCat->slug,
						'term_count' 		=> @$eventSpeakerCat->count,
						'term_posts' 		=> []
					);

					foreach ($eventSpeakers as $eventSpeakerId) {
						$speakerInfo = psyem_GetSinglePostWithMetaPrefix('psyem-speakers', $eventSpeakerId, 'psyem_speaker_', 20);
						if (!empty($speakerInfo)) {
							$resp[$eventSpeakerCatId]['term_posts'][] = $speakerInfo;
						}
					}
				}
			}
		}
	}
	return $resp;
}

function psyem_ValidateApiAuthToken($auth_token)
{

	$resp = false;
	if (!empty($auth_token)) {

		$tokenArr = explode('__wpu__', $auth_token);
		$token    = @$tokenArr[0];
		$enckey   = @$tokenArr[1];
		$UID      = (!empty($enckey)) ? psyem_safe_b64decode_id($enckey) : 0;
		if ($UID > 0) {
			$psyem_user_auth_token = get_user_meta($UID, 'psyem_user_auth_token', true);
			$psyem_user_auth_time  = get_user_meta($UID, 'psyem_user_auth_time', true);

			if ($auth_token == $psyem_user_auth_token) {
				if (!empty($psyem_user_auth_time)) {
					$current_date_time     = current_time('mysql');
					$current_timestamp     = strtotime($current_date_time);
					$past_timestamp        = strtotime($psyem_user_auth_time);
					$difference_in_seconds = ($current_timestamp - $past_timestamp);

					// Check if the difference is greater than 6 hours (21600 seconds)
					if ($difference_in_seconds > 21600) {
						// Difference is greater than 6 hours
						delete_user_meta($UID, 'psyem_user_auth_token'); // Remove token
						delete_user_meta($UID, 'psyem_user_auth_time'); // Remove time
					} else {
						// Difference is within 6 hours
						$resp = true;
					}
				}
			}
		}
	}
	return $resp;
}

function psyem_GetEventTickets($event_id)
{
	$resp = [];

	if ($event_id > 0) {
		$event_regtype      = get_post_meta(@$event_id, 'psyem_event_registration_type', true);
		$event_tickets      = get_post_meta(@$event_id, 'psyem_event_tickets', true);
		$event_tickets      = (!empty($event_tickets)) ? $event_tickets : [];
		if ((!empty($event_tickets) && is_array($event_tickets) && count($event_tickets) > 0) && ($event_regtype != 'Free')) {
			foreach ($event_tickets as $ticket_id) {
				if ($ticket_id > 0) {
					$eventTicketInfo = psyem_GetSinglePostWithMetaPrefix('psyem-tickets', $ticket_id, 'psyem_ticket_');
					$ticketMeta      = @$eventTicketInfo['meta_data'];
					$ticketType      = @$ticketMeta['psyem_ticket_type'];
					$ticketPrice     = @$ticketMeta['psyem_ticket_price'];
					$ticketPCount    = @$ticketMeta['psyem_ticket_group_participant'];
					$allowAdd        = false;
					if ($ticketType == 'Individual' && $ticketPrice > 0) {
						$allowAdd        = true;
					}
					if ($ticketType == 'Group' && $ticketPrice  > 0 && $ticketPCount > 0) {
						$allowAdd        = true;
					}
					if ($allowAdd) {
						$eventTicketInfo['meta_data']['psyem_ticket_price'] = psyem_roundPrecision($ticketPrice);
						$resp[$ticket_id] = $eventTicketInfo;
					}
				}
			}
		}
	}
	return $resp;
}

function psyem_GetOrderTotalSlotsFromStripe($cart_data)
{

	$resp = 0;
	if (!empty($cart_data) && is_array($cart_data)) {
		foreach ($cart_data as $cartTicketId => $cartTicketInfo) {
			$ticketType 		= @$cartTicketInfo['type'];
			$choosen_count 		= @$cartTicketInfo['choosen_count'];
			$member_count 		= @$cartTicketInfo['member_count'];
			if ($ticketType == 'Individual' && $choosen_count > 0) {
				$resp = ($resp + $choosen_count);
			}
			if ($ticketType == 'Group' && $choosen_count > 0 && $member_count > 0) {
				$totalGParticpants = ($choosen_count * $member_count);
				$resp = ($resp + $totalGParticpants);
			}
		}
	}
	return $resp;
}

function psyem_ManageProjectsafeFormData($post = [])
{
	$resp = ['record_id' => 0, 'movenext' => 'Yes'];
	global $wpdb;

	$projectKey  = 'ProjectSafe';
	$form_type   = @$post['field_form_type'];
	$form_key    = @$post['field_form_key'];
	if (!empty($form_key)) {
		if ($form_type == 'Participant') {
			$_SESSION[$projectKey][$form_key]['Participant'] 	= $post;
		}

		$psyemSessionInfo 	= (isset($_SESSION[$projectKey][$form_key])) ? $_SESSION[$projectKey][$form_key] :  [];
		$ParticipantInfo  	= (isset($psyemSessionInfo['Participant'])) ? $psyemSessionInfo['Participant'] :  [];

		if ($form_type == 'Contact' && !empty($ParticipantInfo)) {
			$_SESSION[$projectKey][$form_key]['Contact'] 	= $post;

			$psyemSessionInfo 	= (isset($_SESSION[$projectKey][$form_key])) ? $_SESSION[$projectKey][$form_key] :  [];

			$ParticipantInfo  	= (isset($psyemSessionInfo['Participant'])) ? $psyemSessionInfo['Participant'] :  [];
			$ContactInfo		= (isset($psyemSessionInfo['Contact'])) ? $psyemSessionInfo['Contact'] :  [];

			$participant_name    	= trim(@$ParticipantInfo['field_first_name'] . ' ' . @$ParticipantInfo['field_last_name']);
			$participant_gender  	= trim(@$ParticipantInfo['field_gender']);
			$participant_email   	= trim(@$ContactInfo['field_email']);
			$participant_phone   	= trim(@$ContactInfo['field_phone']);
			$participant_region  	= trim(@$ContactInfo['field_region']);
			$participant_district 	= trim(@$ContactInfo['field_district']);
			$participant_address 	= trim(@$ContactInfo['field_address']);

			$dobDate				= trim(@$ParticipantInfo['field_dob_day']);
			$dobMonth				= trim(@$ParticipantInfo['field_dob_month']);
			$dobYear				= trim(@$ParticipantInfo['field_dob_year']);
			$pdob                 	= $dobYear . '-' . $dobMonth . '-' . $dobDate;
			$date 					= new DateTime($pdob);
			$pdobwords 				= $date->format('l, F j, Y');

			$Contact_sms			= trim(@$ContactInfo['field_contact_sms']);
			$Contact_sms			= (!empty($Contact_sms)) ? ucfirst($Contact_sms) : '';
			$Contact_email			= trim(@$ContactInfo['field_contact_email']);
			$Contact_email			= (!empty($Contact_email)) ? ucfirst($Contact_email) : '';
			$ContactBy              = $Contact_sms . ' - ' . $Contact_email;
			$Contact_source			= trim(@$ContactInfo['field_source']);

			// consents
			$agree_clinical         = trim(@$ParticipantInfo['field_agree_clinical']);
			$agree_clinical         = ($agree_clinical == 'on') ? 'Yes' : 'No';
			$agree_infosheet        = trim(@$ParticipantInfo['field_info_sheet']);
			$agree_infosheet        = ($agree_infosheet == 'on') ? 'Yes' : 'No';
			$agree_participation   	= trim(@$ParticipantInfo['field_participation']);
			$agree_participation    = ($agree_participation == 'on') ? 'Yes' : 'No';
			$agree_study         	= trim(@$ParticipantInfo['field_agree_study']);
			$agree_study         	= ($agree_study == 'on') ? 'Yes' : 'No';
			$agree_doctor         	= trim(@$ParticipantInfo['field_agree_doctor']);
			$agree_doctor         	= ($agree_doctor == 'on') ? 'Yes' : 'No';
			$agree_tnc         		= trim(@$ParticipantInfo['field_agree_tnc']);
			$agree_tnc         		= ($agree_tnc == 'on') ? 'Yes' : 'No';

			// questions
			$qt_sexual_experience         	= ucfirst(trim(@$ParticipantInfo['field_sexual_experience']));
			$qt_cervical_screening         	= ucfirst(trim(@$ParticipantInfo['field_cervical_screening']));
			$qt_undergoing_treatment       	= ucfirst(trim(@$ParticipantInfo['field_undergoing_treatment']));
			$qt_received_hpv         		= ucfirst(trim(@$ParticipantInfo['field_received_hpv']));
			$qt_pregnant         			= ucfirst(trim(@$ParticipantInfo['field_pregnant']));
			$qt_hysterectomy         		= ucfirst(trim(@$ParticipantInfo['field_hysterectomy']));

			$postContent 			= $participant_name . ' ' . $participant_gender . ' ' . $participant_email . ' ' . $participant_phone . ' ' . $participant_region . ' ' . $participant_district . ' ' . $participant_address;

			if (!empty($ParticipantInfo) && !empty($ContactInfo)) {
				$current_time     = current_time('mysql');
				$current_time_gmt = current_time('mysql', 1);

				$psTitle    = ucfirst($participant_name) . ' - Project Safe Request';
				$post_data  = array(
					'post_title'    => ucfirst($psTitle),
					'post_name'     => sanitize_title($psTitle),
					'post_status'   => 'publish',
					'post_content'  => $postContent,
					'post_excerpt'  => $postContent,
					'post_type'     => 'psyem-projectsafes',
					'post_date'     => $current_time,
					'post_date_gmt' => $current_time_gmt,
				);
				$insertPSResp        = $wpdb->insert($wpdb->posts, $post_data);
				$inserted_ps_id      = @$wpdb->insert_id;

				if ($inserted_ps_id  > 0) {
					$psEnc =  psyem_safe_b64encode_id($inserted_ps_id);
					// Update the post
					$psTitle = $psTitle . ' - ' . $psEnc;
					$updated_post_data = array(
						'ID'           => $inserted_ps_id,
						'post_title'   => ucfirst($psTitle),
						'post_name'    => sanitize_title($psTitle),
					);
					wp_update_post($updated_post_data);

					// // update order meta 
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_type', @$ParticipantInfo['field_projectsafe_type']);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_firstname', @$ParticipantInfo['field_first_name']);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_lastname', @$ParticipantInfo['field_last_name']);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_fullname', $participant_name);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_email', $participant_email);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_phone', $participant_phone);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_gender', ucfirst($participant_gender));

					update_post_meta($inserted_ps_id, 'psyem_projectsafe_region', $participant_region);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_district', $participant_district);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_address', $participant_address);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_contact_by', $ContactBy);

					update_post_meta($inserted_ps_id, 'psyem_projectsafe_dob_format', $pdobwords);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_dob', $pdob);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_source', $Contact_source);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_status', 'Request');

					update_post_meta($inserted_ps_id, 'psyem_projectsafe_sexual_experience', $qt_sexual_experience);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_cervical_screening', $qt_cervical_screening);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_undergoing_treatment', $qt_undergoing_treatment);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_received_hpv', $qt_received_hpv);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_pregnant', $qt_pregnant);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_hysterectomy', $qt_hysterectomy);

					update_post_meta($inserted_ps_id, 'psyem_projectsafe_check_clinical', $agree_clinical);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_check_infosheet', $agree_infosheet);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_check_participation', $agree_participation);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_check_study', $agree_study);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_check_doctor', $agree_doctor);
					update_post_meta($inserted_ps_id, 'psyem_projectsafe_check_tandc', $agree_tnc);

					try {
						$subject =  __('Your registration request for Project Safe has been successfully submitted', 'psyeventsmanager');
						$headers = array('Content-Type: text/html; charset=UTF-8');

						$message = '<p><strong> Hello ' . $participant_name . '</strong></p>';
						$message .= '<p>' . __('You are now registered to Project SAFE - Our partner PHASE Scientific will send you an email or sms for confirmation in 1-2 working days', 'psyeventsmanager') . '<p>';
						$message .= '<p>' . __('Please call Kinetics Integrated Medical and Health Center within 2 weeks from your sign-up date to arrange an appointment for the screening', 'psyeventsmanager') . '<p>';
						$message .= '<p>' . __('See you soon', 'psyeventsmanager') . '<p>';
						$message .= '<p>' . __('Thank You', 'psyeventsmanager') . '<p>';

						$psEmailResp = wp_mail($participant_email, $subject, $message, $headers, array());
					} catch (\Exception $e) {
						error_log('psyem_ManageProjectsafeFormData email ERROR :: ' . $e->getMessage());
					}
					$resp = ['record_id' => $inserted_ps_id, 'movenext' => 'No'];
				}
			}
		}
	}
	return $resp;
}

function psyem_GetConvertedYearMonthDay($number)
{
	// $number = 20250217;   
	$number_str = (string)$number;
	$year = substr($number_str, 0, 4);
	$month = substr($number_str, 4, 2);
	$day = substr($number_str, 6, 2);
	return array('year' => $year, 'month' => $month, 'day' => $day);
}

function psyem_getPostsByMetakeyAndValueData($post_type = '', $meta_key = '', $meta_val = '', $compare = '=', $meta_prefix = '')
{
	$resp = [];
	if (!empty($post_type) && !empty($meta_key) && !empty($meta_val)) {
		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => $meta_key,
					'value'   => $meta_val,
					'compare' => $compare, // can be '=', '!=', '>', '<', 'LIKE', etc.
				)
			),
			'posts_per_page' => -1,
		);

		$query = new WP_Query($args);

		if ($query->have_posts()) {
			foreach ($query->posts as $ppost) {
				$ppostId  		= @$ppost->ID;
				$postImage 		= get_the_post_thumbnail_url($ppostId, 'full');
				$defaultImage 	= PSYEM_ASSETS . '/images/default.jpg';
				$image 			= ($postImage && !empty($postImage)) ? $postImage : $defaultImage;

				$post_excerpt   = @$ppost->post_excerpt;
				$excerpt 		= psyem_GetCroppedExcerpt($post_excerpt);

				$postData = array(
					'ID'    		=> $ppostId,
					'title' 		=> @$ppost->post_title,
					'excerpt'      	=> $excerpt,
					'image'        	=> $image,
					'link'			=> (get_post_permalink($ppostId)),
					'edit_link'		=> (get_edit_post_link($ppostId)),
				);

				if (!empty($meta_prefix)) {
					$postData['meta_data'] = psyem_GetPostAllMetakeyValWithPrefix($ppostId, $meta_prefix);
				}

				$resp[$ppostId] 	= $postData;
			}
		}
	}

	return $resp;
}

function psyem_GetAllListingPosts($params = array())
{

	$resp = ['pagination' => [], 'data' => []];

	// limit offset
	$limit 				= @$params['limit'];
	$limit              = ($limit > 0) ? (int) $limit : 20;
	$current_page   	= @$params['cpage'];
	$current_page   	= ($current_page > 0) ? (int) $current_page : 1;
	$offset 			= (($current_page - 1) * $limit);
	$search_key 		= @$params['search_key'];
	$search_term        = (!empty($search_key)) ? $search_key : '';
	$post_type          = @$params['post_type'];
	$meta_prefix        = @$params['meta_prefix'];
	$search_cat         = @$params['search_cat'];
	$taxonomy           = @$params['taxonomy'];
	$lang               = psyem_GetCurrentLocale();

	$args  = array(
		'post_type'      => $post_type,
		'posts_per_page' => $limit,
		'paged'          => $current_page,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'lang'           => $lang,
	);
	if ($search_cat > 0) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $search_cat,
			)
		);
	}

	$meta_query = array();

	if (!empty($search_term)) {
		$search_term = sanitize_text_field($search_term);
		$args['search_term'] = $search_term;
	}

	$args['meta_query'] = $meta_query;
	$query 				= new WP_Query($args);

	$resp['pagination'] = array(
		'current_page' 	=> $current_page,
		'total_page'   	=> @$query->max_num_pages ?? 0,
		'posts_count'   => @$query->post_count ?? 0,
		'limit'   		=> $limit,
	);

	// Loop through the posts and get the required fields
	if ($query->have_posts()) {
		foreach ($query->posts as $ppost) {

			$ppostId 		= @$ppost->ID;
			$postImage 		= get_the_post_thumbnail_url($ppostId, 'full');
			$defaultImage 	= PSYEM_ASSETS . '/images/default.jpg';
			$image 			= ($postImage && !empty($postImage)) ? $postImage : $defaultImage;

			$postData 		= array(
				'ID'    	=> $ppostId,
				'title' 	=> @$ppost->post_title,
				'image'     => $image,
				'link'		=> get_post_permalink($ppostId),
				'date'		=> get_the_date('d F Y', $ppost)
			);

			$taxonomy = '';
			if (!empty($post_type)) {
				switch ($post_type) {
					case 'psyem-knowledges':
						$taxonomy = 'psyem-knowledges-category';
						break;
					case 'psyem-programmes':
						$taxonomy = 'psyem-programmes-category';
						break;
					case 'psyem-news':
						$taxonomy = 'psyem-news-category';
						break;
				}
			}

			$categories 			= (!empty($taxonomy)) ? get_the_terms($ppostId, $taxonomy) : [];
			$firstCategory          = [];
			if (!empty($categories) && is_array($categories)) {
				foreach ($categories as $cindex => $catObj) {
					$term_id = @$catObj->term_id;
					$category_icon_image_url = get_term_meta($term_id, 'category_icon_image_url', true);
					$catObj->image_url = (!empty($category_icon_image_url)) ? $category_icon_image_url : $defaultImage;
					$categories[$cindex] = $catObj;
					$firstCategory = ($cindex == 0) ? $catObj : $firstCategory;
				}
			}
			$postData['category'] = (!empty($firstCategory)) ? $firstCategory : [];
			$postData['categories'] = (!empty($categories)) ? $categories : [];

			$postData['meta_data'] 	= array();
			if (!empty($meta_prefix)) {
				$postData['meta_data'] 	= psyem_GetPostAllMetakeyValWithPrefix($ppostId, $meta_prefix);
			}
			$resp['data'][$ppostId] 		= $postData;
		}
	}
	return $resp;
}

function psyem_ConvertUsdToHkd($usdAmount, $exchangeRate = 0)
{
	if (!is_numeric($usdAmount) || $usdAmount < 0) {
		return 0.00;
	}

	if ($exchangeRate > 0) {
	} else {
		$psyem_options                  = psyem_GetOptionsWithPrefix();
		$psyem_currency_exchange_rate   = @$psyem_options['psyem_currency_exchange_rate'];
		$exchangeRate = ($psyem_currency_exchange_rate > 0) ? $psyem_currency_exchange_rate : 7.8;
	}

	$hkdAmount = ($usdAmount * $exchangeRate);
	return psyem_roundPrecision($hkdAmount);
}

function psyem_GenerateRandomString($type = "alphabets", $length = 3, $string = "")
{
	$string = !empty($string) ? $string : 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$digits = '1234567890';
	$randomString = '';
	if ($type == "alphabets") {
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $string[rand(0, strlen($string) - 1)];
		}
	} else if ($type == "numbers") {
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $digits[rand(0, strlen($digits) - 1)];
		}
	} else {
		$mixed = $string . $digits;
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $mixed[rand(0, strlen($mixed) - 1)];
		}
	}
	return strtoupper($randomString);
}

function psyem_UnsetDonationCheckoutData($postData = [])
{

	$resp = [];
	if (!empty($postData) && is_array($postData)) {
		if (isset($postData['action'])) {
			unset($postData['action']);
		}
		if (isset($postData['_nonce'])) {
			unset($postData['_nonce']);
		}
		if (isset($postData['amount_enc'])) {
			unset($postData['amount_enc']);
		}
		if (isset($postData['amount'])) {
			unset($postData['amount']);
		}
		if (isset($postData['client_secret_info'])) {
			unset($postData['client_secret_info']);
		}
		$resp = $postData;
	}
	return $resp;
}

function psyem_GetFormattedDatetime($format = 'Y-m-d H:i:s', $datetime = NULL, $timezone = NULL)
{
	$clocale  = psyem_GetCurrentLocale();
	$format   = ($format != NULL) ? $format : 'Y-m-d H:i:s';
	$timezone = ($timezone != NULL) ? $timezone : 'UTC';
	$locale   = ($clocale == 'he') ? 'zh_HK' : 'en_US';

	$date     = '';
	try {
		if (is_numeric($datetime) && !is_float($datetime) && $datetime != NULL) {
			$datetimeob1 = new DateTime($datetime, new DateTimeZone($timezone));
			$date = $datetimeob1->format($format);
		} elseif (is_float($datetime) && !is_numeric($datetime) && $datetime != NULL) {
			$dateObj = DateTime::createFromFormat('U.u', $datetime);
			$dateObj->setTimeZone(new DateTimeZone($timezone));
			$date = $dateObj->format($format);
		} elseif (!is_numeric($datetime) && $datetime != NULL) {
			$datetimeob1 = new DateTime($datetime, new DateTimeZone($timezone));
			$date        = $datetimeob1->format($format);
		} else {
			$datetimeob1 = new DateTime('', new DateTimeZone($timezone));
			$date      = $datetimeob1->format($format);
		}
	} catch (\Exception $e) {
		error_log('psyem_GetFormattedDatetime LOGO ERROR :: ' . $e->getMessage());
	}
	return $date;
}

function formatPriceWithComma($price = 0)
{
	if (is_numeric($price)) {
		$price = (float) $price;
		return number_format($price, 2, '.', ',');
	}
	return $price;
}

function psyem_SendEventOrderBookingEmail($event_id = 0, $order_id = 0, $participant_id = 0)
{
	$resp = array();
	if ($event_id > 0 && $order_id > 0 && $participant_id > 0) {
		$Event   		= psyem_GetSinglePostWithMetaPrefix('psyem-events', $event_id, 'psyem_event_');
		$Order     		= psyem_GetSinglePostWithMetaPrefix('psyem-orders', $order_id, 'psyem_order_');
		$Participant    = psyem_GetSinglePostWithMetaPrefix('psyem-participants', $participant_id, 'psyem_participant_');

		if (!empty($Event) && !empty($Order) && !empty($Participant)) {
			// prepare logo
			$pdfLogoHtml = '';
			try {
				$pdf_content_type   = 'Logo';
				$logoSamplePath     = PSYEM_ASSETS_PATH . '/images/sitelogo.png';
				$logo_image_data    = file_get_contents($logoSamplePath);
				$logo_base64        = base64_encode($logo_image_data);
				$logo_mime_type     = mime_content_type($logoSamplePath);
				$logo_image_src     = 'data:' . $logo_mime_type . ';base64,' . $logo_base64;
				$pdfLogoHtml        = trim(require PSYEM_PATH . 'admin/includes/psyemOrderTicket.php');
			} catch (\Exception $e) {
				error_log('psyem_SendEventOrderBookingEmail LOGO ERROR :: ' . $e->getMessage());
			}

			// prepare event info
			$pdf_content_type   = 'Event';
			$orderEventId       = $event_id;
			$orderEventInfo     = $Event;
			$orderEventInfo['order_id'] = $order_id;
			$orderEventMeta     = @$orderEventInfo['meta_data'];
			$pdfEventHtml       = trim(require PSYEM_PATH . 'admin/includes/psyemOrderTicket.php');

			// prepare footer
			$MasterSettings     = psyem_GetOptionsWithPrefix();
			$pdf_content_type   = 'Footer';
			$TandCPageLink      = (isset($MasterSettings['psyem_terms_url']) && !empty($MasterSettings['psyem_terms_url'])) ? $MasterSettings['psyem_terms_url'] : get_site_url();
			$pdfFooterHtml      = trim(require PSYEM_PATH . 'admin/includes/psyemOrderTicket.php');

			$subject 			= 'Event ticket for - ' . @$orderEventInfo['title'];
			$headers 			= array('Content-Type: text/html; charset=UTF-8');

			// prepare tickets
			$pdfTicketsHtml 	= '';
			$participantID 		=  $participant_id;
			$participantInfo 	=  $Participant;

			$scanKey            = psyem_safe_b64encode($participantID . '@_@' . $order_id);
			$psyem_event_verifyqr_page_id   = @$MasterSettings['psyem_event_verifyqr_page_id'];
			$verifyQrPageLink   = psyem_GetPageLinkByID($psyem_event_verifyqr_page_id);

			try {
				$get_scan_data = http_build_query(array(
					'ticketinfo'    => $scanKey,
					'order'         => @$order_id,
					'participant'   => @$participantID,
				));
				$qrContentUrl =  $verifyQrPageLink . '?' . $get_scan_data;
				$qrSamplePath = PSYEM_PATH . 'packages/phpqrcode/qrcode.png';
				$qrSampleUrl  = PSYEM_URL . 'packages/phpqrcode/qrcode.png';
				QRcode::png($qrContentUrl, $qrSamplePath, QR_ECLEVEL_L, 6, 2);
				$image_data   = file_get_contents($qrSamplePath);
				$base64       = base64_encode($image_data);
				$mime_type    = mime_content_type($qrSamplePath);
				$qrBase64     = 'data:' . $mime_type . ';base64,' . $base64;

				$participantInfo['order_id']        = $order_id;
				$participantInfo['qr_image_src']    = $qrBase64;
				$fullName = @$participantInfo['title'];
				$namesArr = psyem_SplitFullName($fullName);
				$participantInfo = array_merge($participantInfo, $namesArr);
				$participantMeta = @$participantInfo['meta_data'];
				$toEmail         = @$participantMeta['psyem_participant_email'];

				// prpare ticket
				$pdf_content_type = 'Ticket';
				$pdfTicketsHtml  .= trim(require PSYEM_PATH . 'admin/includes/psyemOrderTicket.php');
				if (!empty($pdfTicketsHtml) && !empty($toEmail)) {

					$message  = 'Hello ' . $fullName;
					$message  = '<p><strong>' . __('Your booking has been confirmed', 'psyeventsmanager') . '</strong></p>';
					$message .= '<p>' . __('Kindly find the attached document for event ticket reference', 'psyeventsmanager') . '<p>';
					$message .= '<p>' . __('Event Address', 'psyeventsmanager') . ': ' . @$orderEventMeta['psyem_event_address'] . '<p>';
					$message .= '<p>' . __('Booking Order', 'psyeventsmanager') . ': ' . @$order_id . '<p>';
					$message .= '<p>' . __('See you soon', 'psyeventsmanager') . '<p>';
					$message .= '<p>' . __('Thank You', 'psyeventsmanager') . '<p>';

					$pdfhtml = '
                            <html lang="en" dir="ltr">
                            <body style="background-color:#fff;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
                            <div style="width:100%; font-size: 16px; line-height: 24px; background: #fff; color: #555; padding: 20px 10px 20px 2px;">             
                                ' . $pdfLogoHtml . '
                                ' . $pdfEventHtml . '
                                ' . $pdfTicketsHtml . '
                                ' . $pdfFooterHtml . '
                            </div>
                            </body>
                            </html>
                            ';

					$dompdf         = new Dompdf();
					$options        = $dompdf->getOptions();
					$options->setDefaultFont('Courier');
					$dompdf->setOptions($options);
					$dompdf->loadHtml($pdfhtml);
					$dompdf->setPaper('A4', 'portrait');
					$dompdf->render();

					$fileName   = 'ticket_' . $orderEventId . '_' . $order_id . '_' . $participantID . '.pdf';
					$upload_dir = wp_upload_dir();
					$file_path  = $upload_dir['path'] . '/' . $fileName;
					file_put_contents($file_path, $dompdf->output());
					$file_url = $upload_dir['url'] . '/' . $fileName;

					$participantEmailResp = wp_mail($toEmail, $subject, $message, $headers, array($file_path));
					if ($participantEmailResp && filesize($file_path) > 0) {
						unlink($file_path);
					}
				}
			} catch (\Exception $e) {
				error_log('psyem_SendEventOrderBookingEmail QR IMAGE ERROR :: ' . $e->getMessage());
			}
		}
	}
	return $resp;
}


function psyem_GetEventAttendeesCount($event_id)
{

	$en_event_id                	= (function_exists('pll_get_post')) ? pll_get_post($event_id, 'en') : $event_id;
	$zh_event_id                	= (function_exists('pll_get_post')) ? pll_get_post($event_id, 'zh') : $event_id;

	$eventAttendeesEnInfoArr    	= [];
	$eventAttendeesZhInfoArr    	= [];
	if ($en_event_id > 0) {
		$eventAttendeesEnInfoArr    = get_post_meta(@$en_event_id, 'psyem_event_attendees_info', true);
		$eventAttendeesEnInfoArr    = (!empty($eventAttendeesEnInfoArr) && is_array($eventAttendeesEnInfoArr)) ? $eventAttendeesEnInfoArr : [];
	}
	if ($zh_event_id > 0) {
		$eventAttendeesZhInfoArr    = get_post_meta(@$zh_event_id, 'psyem_event_attendees_info', true);
		$eventAttendeesZhInfoArr    = (!empty($eventAttendeesZhInfoArr) && is_array($eventAttendeesZhInfoArr)) ? $eventAttendeesZhInfoArr : [];
	}

	$attendeesIdsArr                = array_merge($eventAttendeesEnInfoArr, $eventAttendeesZhInfoArr);
	$attendeesIdsArr                = array_values($attendeesIdsArr);
	$attendeesIdsArr                = array_unique($attendeesIdsArr);
	$attendeesIdsArr                = array_filter($attendeesIdsArr, function ($value) {
		return $value != 0;
	});
	$attendeesIdsArr                = array_values($attendeesIdsArr);
	return (is_array($attendeesIdsArr)) ? count($attendeesIdsArr) : 0;
}
