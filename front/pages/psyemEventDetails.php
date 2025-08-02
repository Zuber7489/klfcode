<?php

/**
 * Template Name: Psyem Events details
 */
?>
<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
get_header();
while (have_posts()) : the_post();
    $GET  = (isset($_GET) && !empty($_GET)) ? $_GET : array();
    $isPreview                      = (isset($GET['preview']) && ($GET['preview'] == true || $GET['preview'] == 'true')) ? true : false;
    $EventId                        = get_the_ID();
    $psyem_options                  = psyem_GetOptionsWithPrefix();
    $currency_exchange_rate         = @$psyem_options['psyem_currency_exchange_rate'];
    $psyem_event_checkout_page_id   = @$psyem_options['psyem_event_checkout_page_id'];


    $psyemEventInfo     = psyem_GetSinglePostWithMetaPrefix('psyem-events', $EventId, 'psyem_event_');
    $psyemEventMeta     = @$psyemEventInfo['meta_data'];
    $event_speakers     = get_post_meta(@$EventId, 'psyem_event_speakers', true);
    $event_partners     = get_post_meta(@$EventId, 'psyem_event_partners', true);
    $event_medias       = get_post_meta(@$EventId, 'psyem_event_media_urls', true);
    $checkout_key       = psyem_safe_b64encode_id($EventId);
    $event_checkout_url = psyem_GetPageLinkByID($psyem_event_checkout_page_id)  . '?checkkey=' . $checkout_key;
    $isBookingAllowed   = psyem_IsEventBookingAllowed(0, $psyemEventInfo);
    $eventRegType       = (isset($psyemEventMeta['psyem_event_registration_type'])) ? $psyemEventMeta['psyem_event_registration_type'] :  '';
    $bookingUrlHtml     = ($isBookingAllowed == 'Yes' && $isPreview == false && ($eventRegType == 'Free' || $eventRegType == 'Invitation')) ? '<a class="bookNowButton text-white" href="' . $event_checkout_url . '">' . ($eventRegType == 'Invitation' ? __('Register', 'psyeventsmanager') : __('Book Now', 'psyeventsmanager')) . '</a>' : '';

    $excerpt            = @$psyemEventInfo['excerpt'];
    $fetauredImage      = @$psyemEventInfo['image'];
    $eventTickets       = [123];
    $eventTickets       = psyem_GetEventTickets($EventId);
    $curreny_type       = psyem_GetCurrenyType();
    $curreny_sign       = psyem_GetCurrenySign();

    $event_startdate    = @$psyemEventMeta['psyem_event_startdate'];
    $event_starttime    = @$psyemEventMeta['psyem_event_starttime'];
    $start_date         = psyem_GetFormattedDatetime('d F Y', $event_startdate);
    $start_time         = psyem_GetFormattedDatetime('h:i a', $event_startdate . '' . $event_starttime);

    $event_enddate      = @$psyemEventMeta['psyem_event_enddate'];
    $event_endtime      = @$psyemEventMeta['psyem_event_endtime'];
    $end_date           = psyem_GetFormattedDatetime('d F Y', $event_enddate);
    $end_time           = psyem_GetFormattedDatetime('h:i a', $event_startdate . '' . $event_endtime);

    // Check if times are meaningful (not default 12:00 am times)
    $has_meaningful_start_time = !empty($event_starttime) && $start_time !== '12:00 am';
    $has_meaningful_end_time = !empty($event_endtime) && $end_time !== '12:00 am';

    $psyem_event_listing_page_id    = @$psyem_options['psyem_event_listing_page_id'];
?>
    <main id="content" <?php post_class('site-main psyemEventInfo'); ?> style="max-width: 100%; overflow: hidden;">
        <section class="topBradcampImage" style="background:url(<?= $fetauredImage ?>); background-position: center; background-repeat: no-repeat; background-size: cover;">
            <div class="container">
                <div class="row justify-content-between mrAll-0">
                    <div class="col-md-12 pAll-0">
                        <p class="bradCamp">
                            <a href="<?= psyem_GetPageLinkByID($psyem_event_listing_page_id) ?>">
                                <?= __('Our Events', 'psyeventsmanager') ?> >
                            </a>
                            <?= @$psyemEventInfo['title'] ?>
                        </p>
                    </div>
                </div>

                <div class="row justify-content-between align-items-end mrAll-0">
                    <div class="col-md-3 pAll-0">
                        <img src="<?= $fetauredImage ?>" class="imgWdth" alt="<?= @$psyemEventInfo['title'] ?>" />
                    </div>
                    <div class="col-md-9">
                        <h2 class="gTxt gTxttitle">
                            <?= @$psyemEventInfo['title'] ?>
                        </h2>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="row">
                <div class="col-md-3 ps-5 pe-5 pt-3 psyemEventDetailSidebar">
                    <h3 class="event-title">
                        <?= @$psyemEventInfo['title'] ?>
                    </h3>
                    <div class="event-date">
                        <?php
                        if ($start_date === $end_date) {
                            echo $start_date;
                        } else {
                            echo $start_date . ' - ' . $end_date;
                        }
                        ?>
                    </div>

                    <?php if ($has_meaningful_start_time || $has_meaningful_end_time) : ?>
                        <div class="event-time event-date">
                            <?php 
                            if ($has_meaningful_start_time && $has_meaningful_end_time) {
                                echo $start_time . ' - ' . $end_time;
                            } elseif ($has_meaningful_start_time) {
                                echo $start_time;
                            } elseif ($has_meaningful_end_time) {
                                echo $end_time;
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ((!empty($eventTickets) && is_array($eventTickets)) && ($eventRegType == 'Paid')) : ?>
                        <a href="javascript:void(0);" class="linkcontact gotoTickets">
                            <?= __('Learn More', 'psyeventsmanager') ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="col-md-9 psyemEventDetailCont">
                    <section class="contentDetailSec">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="aboutHeaDetailX">
                                            <?= __('About the event', 'psyeventsmanager') ?>
                                        </h3>
                                        <div class="bookingLink">
                                            <?= $bookingUrlHtml ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="post-content mb-5">
                                        <?php the_content(); ?>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="row eventLeftBorder">
                                        <div class="col-md-12">
                                            <div class="eventAddress">
                                                <h4 class="aboutPreTxt mb-1 fw200">
                                                    <?= __('Address', 'psyeventsmanager') ?>:
                                                </h4>
                                                <p class="eventAddressPre">
                                                    <?= @$psyemEventMeta['psyem_event_address'] ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="eventAddress">
                                                <h4 class="aboutPreTxt mb-1 fw200">
                                                    <?= __('Start date time', 'psyeventsmanager') ?>:
                                                </h4>
                                                <p class="eventAddressPre">
                                                    <?php 
                                                    echo $start_date;
                                                    if ($has_meaningful_start_time) {
                                                        echo ' - ' . $start_time;
                                                    }
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="eventAddress">
                                                <h4 class="aboutPreTxt mb-1 fw200">
                                                    <?= __('End date time', 'psyeventsmanager') ?>:
                                                </h4>
                                                <p class="eventAddressPre">
                                                    <?php 
                                                    echo $end_date;
                                                    if ($has_meaningful_end_time) {
                                                        echo ' - ' . $end_time;
                                                    }
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                        <?php if ($eventRegType == 'Free' || $eventRegType == 'Invitation'): ?>
                                            <div class="col-md-6">
                                                <div class="eventAddress">
                                                    <h4 class="aboutPreTxt mb-1 fw200">
                                                        <?= __('Registration Fee', 'psyeventsmanager') ?>:
                                                    </h4>
                                                    <p class="eventAddressPre">
                                                        <?php if ($eventRegType == 'Free'): ?>
                                                            <strong><?= $curreny_sign ?>0.00</strong>
                                                        <?php elseif ($eventRegType == 'Invitation'): ?>
                                                            <strong><?= __('Free', 'psyeventsmanager') ?></strong>
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-md-6">
                                            <div class="eventAddress">
                                                <h4 class="aboutPreTxt mb-1 fw200">
                                                    <?= __('Registration Type', 'psyeventsmanager') ?>:
                                                </h4>
                                                <p class="eventAddressPre">
                                                    <strong> <?= $eventRegType ?> </strong>
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- event excerpt -->
                                <?php if (!empty($excerpt)) : ?>
                                    <div class="col-md-12">
                                        <h3 class="aboutHeaDetail">
                                            <?= __('Additional Information', 'psyeventsmanager') ?>
                                        </h3>
                                        <div class="post-excerpt mb-5" id="EV<?= $EventId ?>">
                                            <?php
                                            echo (!empty($excerpt)) ? $excerpt : '';
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- event speakers -->
                                <?php
                                if (!empty($event_speakers) && is_array($event_speakers) && count($event_speakers) > 0) {
                                    $eventSpeakersCatData =  psyem_GetEventSpeakersWithCategory($event_speakers);
                                    if (!empty($eventSpeakersCatData) && is_array($eventSpeakersCatData) && count($eventSpeakersCatData) > 0) {
                                ?>
                                        <div class="col-md-12">
                                            <h3 class="eventHeadingSpe">
                                                <?= __('Event Speakers', 'psyeventsmanager') ?>
                                            </h3>
                                            <div class="row">
                                                <?php
                                                foreach ($eventSpeakersCatData as $eventCatSpeaker) {
                                                    $event_cat_speakers = @$eventCatSpeaker['term_posts'];
                                                    if (!empty($event_cat_speakers)) :
                                                ?>
                                                        <div class="col-md-12">
                                                            <h5 class="eventPartnerCateHead">
                                                                <?= @$eventCatSpeaker['term_name'] ?>
                                                            </h5>
                                                            <div class="row">
                                                                <?php foreach ($event_cat_speakers as $speakerInfo) : $speakerMeta = @$speakerInfo['meta_data']; ?>
                                                                    <div class="col-md-4">
                                                                        <div class="cardDetailSpeaker">
                                                                            <img src="<?= @$speakerInfo['image'] ?>" alt="<?= @$speakerInfo['title'] ?>" />
                                                                            <div class="speakerDetailContent">
                                                                                <h3><?= @$speakerInfo['title'] ?></h3>
                                                                                <p class="mb-1"><?= @$speakerMeta['psyem_speaker_designation'] ?></p>
                                                                                <a href="<?= @$speakerInfo['link'] ?>" class="linkcontact">
                                                                                    <?= __('More', 'psyeventsmanager') ?>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                <?php endif;
                                                } ?>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } ?>

                                <!-- event tickets -->
                                <?php if ((!empty($eventTickets) && is_array($eventTickets)) && ($eventRegType == 'Paid')) : ?>
                                    <div class="col-md-12 mb-2 mt-2">
                                        <h3 class="aboutHeaDetail">Tickets</h3>
                                        <form class="psyemCartForm">
                                            <input type="hidden" name="checkout_coupon" value="" />
                                            <input type="hidden" name="checkout_key" value="" />
                                            <div class="post-tickets ">
                                                <?php
                                                foreach ($eventTickets as $ticketId  => $ticketInfo) :
                                                    $ticketMeta = @$ticketInfo['meta_data'];

                                                    $ticketPrice = @$ticketMeta['psyem_ticket_price'];
                                                    if ($curreny_type != 'USD') {
                                                        $ticketPrice = psyem_ConvertUsdToHkd($ticketPrice, $currency_exchange_rate);
                                                    }
                                                    $ticketPrice     = psyem_roundPrecision($ticketPrice);
                                                ?>
                                                    <div class="product-line mb-5" id="ET<?= $ticketId ?>">
                                                        <p class="price"><?= $curreny_sign ?><?= @$ticketPrice ?></p>
                                                        <p class="p-name"><?= @$ticketInfo['title'] ?></p>
                                                        <p class="descTicket"><?php echo (!empty($ticketInfo['excerpt'])) ? $ticketInfo['excerpt'] : ''; ?></p>
                                                        <div class="counter_wrapper">
                                                            <div class="counter">
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">
                                                                        <button class="btn btn-outline-secondary p-1 m-0 btn-sm psyemMinusQtyBtn" type="button" data-ticket="<?= $ticketId ?>">
                                                                            <span class="dashicons dashicons-minus"></span>
                                                                        </button>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm psyemCartItemInput strict_integer strict_space" placeholder="0" id="psyemCartItemInput_<?= $ticketId ?>" name="checkout_tickets[<?= $ticketId ?>]" value="0" data-ticket="<?= $ticketId ?>">

                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-outline-secondary p-1 m-0 btn-sm psyemPlusQtyBtn" type="button" data-ticket="<?= $ticketId ?>">
                                                                            <span class="dashicons dashicons-plus"></span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <?php if (($isBookingAllowed == 'Yes' && $isPreview == false) && ($eventRegType == 'Paid')): ?>
                                                <div class="post-tickets-checkout">
                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <div class="d-flex justify-content-end align-items-center">
                                                                <div class="psyTicketInfo">
                                                                    <h5 class="card-title me-5">
                                                                        <?= __('Total', 'psyeventsmanager') ?>:
                                                                        <?= $curreny_sign ?>
                                                                        <span class="psyemCartTotal">0</span>
                                                                    </h5>
                                                                </div>
                                                                <div class="addToCartBtnCont">
                                                                    <button class="btn btn-primary btn-sm psyemCheckoutCartBtn" type="button" disabled="disabled">
                                                                        <?= __('Add To Cart', 'psyeventsmanager') ?>
                                                                        <span class="spinner-border buttonLoader spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                <?php endif; ?>

                                <!-- event partners -->
                                <?php
                                if (!empty($event_partners) && is_array($event_partners) && count($event_partners) > 0) {
                                    $eventPartnerCatData =  psyem_GetEventPartnersWithCategory($event_partners);
                                    if (!empty($eventPartnerCatData) && is_array($eventPartnerCatData) && count($eventPartnerCatData) > 0) {
                                ?>
                                        <div class="col-md-12">
                                            <h3 class="eventHeadingSpe">
                                                <?= __('Event Partners', 'psyeventsmanager') ?>
                                            </h3>
                                            <div class="row">
                                                <?php
                                                foreach ($eventPartnerCatData as $eventCatPartner) {
                                                    $event_cat_partners = @$eventCatPartner['term_posts'];
                                                ?>
                                                    <?php if (!empty($event_cat_partners)) : ?>
                                                        <div class="col-md-12">
                                                            <h5 class="eventPartnerCateHead">
                                                                <?= @$eventCatPartner['term_name'] ?>
                                                            </h5>
                                                            <div class="row">
                                                                <?php foreach ($event_cat_partners as $partnerInfo) : ?>
                                                                    <div class="col-md-2">
                                                                        <div class="cardDetailPartner">
                                                                            <a href="<?= @$partnerInfo['link'] ?>" class="eventPartCateImg">
                                                                                <img src="<?= @$partnerInfo['image'] ?>" alt="<?= @$partnerInfo['title'] ?> - <?= @$partnerInfo['meta_data']['psyem_partner_sponsorship_level'] ?>">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } ?>

                                <!-- event media -->
                                <?php
                                if (!empty($event_medias) && is_array($event_medias) && count($event_medias) > 0) {
                                ?>
                                    <div class="col-md-12">
                                        <h3 class="eventHeadingSpe">
                                            <?= __('Event Media', 'psyeventsmanager') ?>
                                        </h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="mediaEvent">
                                                    <?php
                                                    foreach ($event_medias as $event_media_url) {
                                                        if (!empty($event_media_url)) {
                                                    ?>
                                                            <li>
                                                                <img src="<?= @$event_media_url ?>" alt="..." />
                                                            </li>
                                                    <?php
                                                        }
                                                    } ?>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } ?>

                                <!-- event social -->
                                <?php if (
                                    !empty($psyemEventMeta['psyem_event_facebook_url']) ||
                                    !empty($psyemEventMeta['psyem_event_instagram_url']) ||
                                    !empty($psyemEventMeta['psyem_event_linkedin_url']) ||
                                    !empty($psyemEventMeta['psyem_event_twitter_url'])
                                ) { ?>
                                    <div class="col-md-12">
                                        <h3 class="eventHeadingSpe">
                                            <?= __('Social Media', 'psyeventsmanager') ?>
                                        </h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <ul class="socialMedia">
                                                    <?php if (isset($psyemEventMeta['psyem_event_facebook_url']) && !empty($psyemEventMeta['psyem_event_facebook_url'])) { ?>
                                                        <li>
                                                            <a href="<?= @$psyemEventMeta['psyem_event_facebook_url'] ?>" target="_blank" class="ms-2 me-2 text-decoration-none text-white">
                                                                <span class="dashicons dashicons-facebook-alt"></span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>

                                                    <?php if (isset($psyemEventMeta['psyem_event_instagram_url']) && !empty($psyemEventMeta['psyem_event_instagram_url'])) { ?>
                                                        <li>
                                                            <a href="<?= @$psyemEventMeta['psyem_event_instagram_url'] ?>" target="_blank" class="ms-2 me-2 text-decoration-none text-white">
                                                                <span class="dashicons dashicons-instagram"></span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>

                                                    <?php if (isset($psyemEventMeta['psyem_event_linkedin_url']) && !empty($psyemEventMeta['psyem_event_linkedin_url'])) { ?>
                                                        <li>
                                                            <a href="<?= @$psyemEventMeta['psyem_event_linkedin_url'] ?>" target="_blank" class="ms-2 me-2 text-decoration-none text-white">
                                                                <span class="dashicons dashicons-linkedin"></span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>

                                                    <?php if (isset($psyemEventMeta['psyem_event_twitter_url']) && !empty($psyemEventMeta['psyem_event_twitter_url'])) { ?>
                                                        <li>
                                                            <a href="<?= @$psyemEventMeta['psyem_event_twitter_url'] ?>" target="_blank" class="ms-2 me-2 text-decoration-none text-white">
                                                                <span class="dashicons dashicons-twitter"></span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php }  ?>

                                <!-- event disclaimer -->
                                <?php if (!empty($psyemEventMeta['psyem_event_disclaimer'])) { ?>
                                    <div class="col-md-12 ">
                                        <h3 class="eventHeadingSpe">
                                            <?= __('Event Disclaimer', 'psyeventsmanager') ?>
                                        </h3>
                                        <p class="col-md-12 psyemEventDisclaimer">
                                            <?= @$psyemEventMeta['psyem_event_disclaimer'] ?>
                                        </p>
                                    </div>
                                <?php }  ?>
                            </div>
                        </div>
                    </section>

                    <section class="contentPostInfoSec">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="post-links mb-3">
                                    <?php wp_link_pages(); ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?php if (has_tag()) : ?>
                                    <div class="post-tags mb-3">
                                        <?php the_tags('<span class="tag-links">' . esc_html__('Tagged ', 'psyeventsmanager'), ', ', '</span>'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                    </section>

                    <section class="contentCommentsSec">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="post-comments mb-3">
                                    <?php comments_template(); ?>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>


    </main>
    <script>
        let psyemCheckoutEncKey = "<?= $checkout_key ?>";
    </script>
<?php
endwhile;
get_footer();
