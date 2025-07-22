<?php ob_start(); ?>
<?php
/**
 * Template Name: Psyem Event checkout thankyou shortcode
 */
?>
<?php
$REQData            = (isset($_GET) && !empty($_GET)) ? $_GET : [];
$booking_order_id   = (isset($REQData['checkkey']) && !empty($REQData['checkkey'])) ? $REQData['checkkey'] :  '';

$psyem_options                  = psyem_GetOptionsWithPrefix();
$psyem_event_listing_page_id    = @$psyem_options['psyem_event_listing_page_id'];
?>
<div class="psyemOrderThankyouCont" style="display: none;">
    <div class="container">
        <div class="row">
            <div class="post-thankyou col-md-12 mb-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <?= __('Booking confirmed', 'psyeventsmanager') ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-success mb-5" role="alert">
                                            <?php
                                            esc_html_e('We are delighted to inform you that your booking has been successfully confirmed', 'psyeventsmanager');
                                            ?>,&nbsp;
                                            <?php
                                            esc_html_e('and your payment has been processed', 'psyeventsmanager');
                                            ?>.&nbsp;
                                            <?php
                                            esc_html_e('We look forward to serving you and ensuring you have a wonderful experience', 'psyeventsmanager');
                                            ?>.
                                        </div>
                                        <div class="alert alert-success" role="alert">
                                            <strong> <?= __('REFERENCE ID', 'psyeventsmanager') ?>: <?= @$booking_order_id ?> </strong>
                                            <br />
                                            <?= __('Click', 'psyeventsmanager') ?>
                                            <a href="<?= psyem_GetPageLinkByID($psyem_event_listing_page_id) ?>" class="alert-link">
                                                <?= __('here', 'psyeventsmanager') ?>
                                            </a>
                                            <?= __('to view more events or book new tickets', 'psyeventsmanager') ?>
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
<?php return ob_get_clean(); ?>