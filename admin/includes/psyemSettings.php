<?php
if (isset($psyem_options)) {
    extract($psyem_options);
}
?>
<div class="wrap" id="psyMainCont">

    <div class="row">
        <div class="col-sm-12">
            <h1 class="wp-heading-inline">
                <?= __('Psy Plugin Settings', 'psyeventsmanager') ?>
            </h1>
        </div>
    </div>

    <hr />

    <div class="row mb-5 mt-5">
        <div class="col-sm-12">
            <div class="accordion" id="PPAccordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingPaymentPayload">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ppCollapseOne" aria-expanded="true" aria-controls="ppCollapseOne">
                            <?= __('Project Safe Types With Form Shortcodes', 'psyeventsmanager') ?>
                        </button>
                    </h2>
                    <div id="ppCollapseOne" class="accordion-collapse collapse" aria-labelledby="headingPaymentPayload" data-bs-parent="#PPAccordionExample">
                        <div class="accordion-body projectSafeTypeFieldCont">

                            <div class="form-group pstypeRowCont">
                                <label class="control-label" for="for">
                                    <strong><?= __('Create Project Safe Type', 'psyeventsmanager') ?>:</strong>
                                </label>
                                <div class="input-group mb-3">
                                    <input type="text" name="projectsafe_title" class="form-control" placeholder="<?= __('Enter project safe type title', 'psyeventsmanager') ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary projectsafeTypeAddBtn" type="button" data-row="" data-task="Create">
                                            <i class="fa fa-plus"></i> <?= __('Add', 'psyeventsmanager') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="control-label" for="for">
                                    <strong><?= __('Project Safe Type shortcodes and title', 'psyeventsmanager') ?>:</strong>
                                </label>
                            </div>
                            <div class="row projectsafeTypesCont mt-3">
                                <?php
                                if (isset($projectsafeTypes) && !empty($projectsafeTypes) && is_array($projectsafeTypes)) {
                                    foreach ($projectsafeTypes as $projectSafeTypeSlug => $projectSafeTypeTitle) {
                                        $typeItemFilePath = PSYEM_PATH . 'admin/includes/psyemProjectsafeTypeItem.php';
                                        if (@is_file($typeItemFilePath) && @file_exists($typeItemFilePath)) {
                                            echo $itemHtml     = require $typeItemFilePath;
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="col-sm-12">
            <form class="form-horizontal" action="" id="SettingsForm" novalidate="novalidate">
                <div class="row mb-3">
                    <div class="col-sm-4 mt-3 mb-3 text-start">
                        <button type="button" class="btn btn-success savePsyemSettings" value="Save">
                            <span class="spinner-border buttonLoader spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                            <i class="fa fa-save"></i>
                            <?= __('Save Settings', 'psyeventsmanager') ?>
                        </button>
                    </div>

                    <div class="col-sm-4 mt-3 text-center">
                        <p>
                            <a href="<?= $speakerCategoriesUrl ?>" class="btn btn-info">
                                <?= __('Update Speakers Categories', 'psyeventsmanager') ?>
                            </a>
                        </p>
                    </div>

                    <div class="col-sm-4 mt-3 text-end">
                        <p>
                            <a href="<?= $partnerCategoriesUrl ?>" class="btn btn-info">
                                <?= __('Update Partners Categories', 'psyeventsmanager') ?>
                            </a>
                        </p>
                    </div>

                    <div class="col-sm-4 mt-3 mb-3 text-start">
                        <p>
                            <a href="<?= $newsCategoriesUrl ?>" class="btn btn-info">
                                <?= __('Update News Categories', 'psyeventsmanager') ?>
                            </a>
                        </p>
                    </div>

                    <div class="col-sm-4 mt-3 text-center">
                        <p>
                            <a href="<?= $programmesCategoriesUrl ?>" class="btn btn-info">
                                <?= __('Update Programmes Categories', 'psyeventsmanager') ?>
                            </a>
                        </p>
                    </div>

                    <div class="col-sm-4 mt-3 text-end">
                        <p>
                            <a href="<?= $knowledgesCategoriesUrl ?>" class="btn btn-info">
                                <?= __('Update Knowledge Categories', 'psyeventsmanager') ?>
                            </a>
                        </p>
                    </div>
                </div>

                <hr />

                <div class="row mt-3">
                    <div class="col-sm-12 mt-3">
                        <label class="control-label fw-bold" for="">
                            <?= __('Event Manager Shortcodes', 'psyeventsmanager') ?>
                        </label>
                    </div>


                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_event_listing_page_id">
                                <?= __('Events Listing Section Shortcode', 'psyeventsmanager') ?>
                            </label>
                        </div>
                        <code>[psyem-events-list]</code>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_event_listing_page_id">
                                <?= __('Events Listing page ID', 'psyeventsmanager') ?>
                            </label>

                            <select class="form-control form-control-sm " name="Settings[psyem_event_listing_page_id]">
                                <option value=""><?= __('-- Select --', 'psyeventsmanager') ?></option>
                                <?php if (isset($all_pages) && !empty($all_pages)): foreach ($all_pages as $spage): ?>
                                        <option value="<?= esc_attr($spage->ID) ?>" <?php selected(@$psyem_event_listing_page_id, @$spage->ID); ?>>
                                            <?= esc_html($spage->post_title) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_event_checkout_page_id">
                                <?= __('Event Checkout Section Shortcode', 'psyeventsmanager') ?>
                            </label>
                        </div>
                        <code>[psyem-event-checkout]</code>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_event_checkout_page_id">
                                <?= __('Event Checkout Checkout page ID', 'psyeventsmanager') ?>
                            </label>

                            <select class="form-control form-control-sm " name="Settings[psyem_event_checkout_page_id]">
                                <option value=""><?= __('-- Select --', 'psyeventsmanager') ?></option>
                                <?php if (isset($all_pages) && !empty($all_pages)): foreach ($all_pages as $spage): ?>
                                        <option value="<?= esc_attr($spage->ID) ?>" <?php selected(@$psyem_event_checkout_page_id, @$spage->ID); ?>>
                                            <?= esc_html($spage->post_title) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_event_thankyou_page_id">
                                <?= __('Event Order Thankyou Section Shortcode', 'psyeventsmanager') ?>
                            </label>
                        </div>
                        <code> [psyem-event-thankyou] </code>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_event_thankyou_page_id">
                                <?= __('Event Order Thankyou page ID', 'psyeventsmanager') ?>
                            </label>

                            <select class="form-control form-control-sm " name="Settings[psyem_event_thankyou_page_id]">
                                <option value=""><?= __('-- Select --', 'psyeventsmanager') ?></option>
                                <?php if (isset($all_pages) && !empty($all_pages)): foreach ($all_pages as $spage): ?>
                                        <option value="<?= esc_attr($spage->ID) ?>" <?php selected(@$psyem_event_thankyou_page_id, @$spage->ID); ?>>
                                            <?= esc_html($spage->post_title) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_event_verifyqr_page_id">
                                <?= __('Verify Participant QRCODE Shortcode', 'psyeventsmanager') ?>
                            </label>
                        </div>
                        <code> [psyem-event-order-verifyqr] </code>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_event_verifyqr_page_id">
                                <?= __('Verify QRCODE page ID', 'psyeventsmanager') ?>
                            </label>

                            <select class="form-control form-control-sm " name="Settings[psyem_event_verifyqr_page_id]">
                                <option value=""><?= __('-- Select --', 'psyeventsmanager') ?></option>
                                <?php if (isset($all_pages) && !empty($all_pages)): foreach ($all_pages as $spage): ?>
                                        <option value="<?= esc_attr($spage->ID) ?>" <?php selected(@$psyem_event_verifyqr_page_id, @$spage->ID); ?>>
                                            <?= esc_html($spage->post_title) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row mt-3">

                    <div class="col-sm-4 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="">
                                <?= __('Knowledge Hub Posts List Shortcode', 'psyeventsmanager') ?>
                            </label>
                        </div>
                        <code>[psyem-knowledgehub-list]</code>
                    </div>

                    <div class="col-sm-4 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="">
                                <?= __('News Posts List Shortcode', 'psyeventsmanager') ?>
                            </label>
                        </div>
                        <code>[psyem-news-list]</code>
                    </div>

                    <div class="col-sm-4 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="">
                                <?= __('Programmes Posts List Shortcode', 'psyeventsmanager') ?>
                            </label>
                        </div>
                        <code>[psyem-programmes-list]</code>
                    </div>
                </div>

                <hr />

                <div class="row mt-3">
                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_payment_mode">
                                <?= __('Monthly and Onetime Donation Section Shortcode', 'psyeventsmanager') ?>
                            </label>
                        </div>
                        <code>[psyem-donation-form]</code>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_payment_mode">
                                <?= __('Donation page ID', 'psyeventsmanager') ?>
                            </label>

                            <select class="form-control form-control-sm " name="Settings[psyem_donation_page_id]">
                                <option value=""><?= __('-- Select --', 'psyeventsmanager') ?></option>
                                <?php if (isset($all_pages) && !empty($all_pages)): foreach ($all_pages as $spage): ?>
                                        <option value="<?= esc_attr($spage->ID) ?>" <?php selected(@$psyem_donation_page_id, @$spage->ID); ?>>
                                            <?= esc_html($spage->post_title) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small><?= __('Will be use as change donation amount page link', 'psyeventsmanager') ?></small>
                        </div>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_payment_mode">
                                <?= __('Donation Checkout Shortcode', 'psyeventsmanager') ?>
                            </label>
                        </div>
                        <code>[psyem-donation-checkout]</code>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_payment_mode">
                                <?= __('Donation checkout page ID', 'psyeventsmanager') ?>
                            </label>

                            <select class="form-control form-control-sm " name="Settings[psyem_donation_checkout_page_id]">
                                <option value=""><?= __('-- Select --', 'psyeventsmanager') ?></option>
                                <?php if (isset($all_pages) && !empty($all_pages)): foreach ($all_pages as $spage): ?>
                                        <option value="<?= esc_attr($spage->ID) ?>" <?php selected(@$psyem_donation_checkout_page_id, @$spage->ID); ?>>
                                            <?= esc_html($spage->post_title) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small><?= __('Donation billing and payment checkout page', 'psyeventsmanager') ?></small>
                        </div>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_payment_mode">
                                <?= __('Onetime Donation Element Shortcode', 'psyeventsmanager') ?>
                            </label>
                        </div>
                        <code>[psyem-donation-onetime] 'class="psyemOnetimeDonationElm"'</code>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_payment_mode">
                                <?= __('One Time Donation page ID', 'psyeventsmanager') ?>
                            </label>

                            <select class="form-control form-control-sm " name="Settings[psyem_onetime_donation_page_id]">
                                <option value=""><?= __('-- Select --', 'psyeventsmanager') ?></option>
                                <?php if (isset($all_pages) && !empty($all_pages)): foreach ($all_pages as $spage): ?>
                                        <option value="<?= esc_attr($spage->ID) ?>" <?php selected(@$psyem_onetime_donation_page_id, @$spage->ID); ?>>
                                            <?= esc_html($spage->post_title) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small>
                                <?= __('Will be use to redirect on donation second details page. donation 3rd option page', 'psyeventsmanager') ?>
                            </small>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row mt-3">
                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_currency_exchange_rate">
                                <?= __('Currency Exchange Rate (USD [1$] to HKD [7.8hk$])', 'psyeventsmanager') ?>
                            </label>
                            <input type="text" name="Settings[psyem_currency_exchange_rate]" class="form-control strict_numeric strict_decimal strict_space" value="<?= (@$psyem_currency_exchange_rate > 0) ? $psyem_currency_exchange_rate : 7.8 ?>" placeholder="7.8" />
                        </div>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_payment_mode">
                                <?= __('Stripe Payment Mode', 'psyeventsmanager') ?>
                            </label>
                            <select class="form-control" name="Settings[psyem_stripe_payment_mode]">
                                <option value=""><?= __('-- Select --', 'psyeventsmanager') ?></option>
                                <option value="Sandbox" <?= (@$psyem_stripe_payment_mode == 'Sandbox') ? 'selected="selected"' : '' ?>>
                                    <?= __('Sandbox', 'psyeventsmanager') ?>
                                </option>
                                <option value="Live" <?= (@$psyem_stripe_payment_mode == 'Live') ? 'selected="selected"' : '' ?>>
                                    <?= __('Live', 'psyeventsmanager') ?>
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_publish_key">
                                <?= __('Stripe Publish Key', 'psyeventsmanager') ?>
                                <i class="fa fa-info-circle"></i>
                            </label>
                            <input type="text" name="Settings[psyem_stripe_publish_key]" class="form-control" value="<?= @$psyem_stripe_publish_key ?>" placeholder="<?= __('Enter stripe publish key', 'psyeventsmanager') ?>" />
                        </div>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_secret_key">
                                <?= __('Stripe Secret Key', 'psyeventsmanager') ?>
                                <i class="fa fa-info-circle"></i>
                            </label>
                            <input type="text" name="Settings[psyem_stripe_secret_key]" class="form-control" value="<?= @$psyem_stripe_secret_key ?>" placeholder="<?= __('Enter stripe publish key', 'psyeventsmanager') ?>" />
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row mt-3">
                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_payment_mode">
                                <?= __('Contact Us Page url', 'psyeventsmanager') ?>
                            </label>
                            <input type="text" name="Settings[psyem_contact_us_url]" class="form-control" value="<?= @$psyem_contact_us_url ?>" placeholder="<?= __('Enter Contact Us Page Url', 'psyeventsmanager') ?>" />
                        </div>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_payment_mode">
                                <?= __('Terms and Condition Page url', 'psyeventsmanager') ?>
                            </label>
                            <input type="text" name="Settings[psyem_terms_url]" class="form-control" value="<?= @$psyem_terms_url ?>" placeholder="<?= __('Enter Terms and Condition Page Url', 'psyeventsmanager') ?>" />
                        </div>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_payment_mode">
                                <?= __('Volunteer Page url', 'psyeventsmanager') ?>
                            </label>
                            <input type="text" name="Settings[psyem_volunteer_url]" class="form-control" value="<?= @$psyem_volunteer_url ?>" placeholder="<?= __('Enter Volunteer Page Url', 'psyeventsmanager') ?>" />
                        </div>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="form-group">
                            <label class="control-label fw-bold" for="psyem_stripe_payment_mode">
                                <?= __('Sponsor Page url', 'psyeventsmanager') ?>
                            </label>
                            <input type="text" name="Settings[psyem_sponsor_url]" class="form-control" value="<?= @$psyem_sponsor_url ?>" placeholder="<?= __('Enter Sponsor Page Url', 'psyeventsmanager') ?>" />
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row mb-3">
                    <div class="col-sm-12 text-start mt-5">
                        <button type="button" class="btn btn-success savePsyemSettings" value="Save">
                            <span class="spinner-border buttonLoader spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                            <i class="fa fa-save"></i>
                            <?= __('Save Settings', 'psyeventsmanager') ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>