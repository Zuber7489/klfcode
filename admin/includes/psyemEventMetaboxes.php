<?php if (isset($event_metabox_type) && !empty($event_metabox_type)) {  ?>
    <!-- configs - BGN -->
    <?php if ($event_metabox_type == 'Config') { 
        if (isset($configs_data) && !empty($configs_data)) {
            extract($configs_data);
        }
    ?>
        <div class="row mb-4">
            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('Event Start Date', 'psyeventsmanager') ?></label>
                <input type="date" class="form-control strict_space" name="psyemConfigs[psyem_event_startdate]" value="<?= @$psyem_event_startdate ?>" placeholder="<?= __('Enter event start date', 'psyeventsmanager') ?>">
            </div>
            <div class="col-sm-3">
                <label class="form-label fw-bold"><?= __('Event Start Time', 'psyeventsmanager') ?></label>
                <input type="time" class="form-control strict_space" name="psyemConfigs[psyem_event_starttime]" value="<?= @$psyem_event_starttime ?>" placeholder="<?= __('Enter event start time', 'psyeventsmanager') ?>">
            </div>
            <div class="col-sm-3">
                <label class="form-label fw-bold"><?= __('Event End Date', 'psyeventsmanager') ?></label>
                <input type="date" class="form-control strict_space" name="psyemConfigs[psyem_event_enddate]" value="<?= @$psyem_event_enddate ?>" placeholder="<?= __('Enter event end date', 'psyeventsmanager') ?>">
            </div>
            <div class="col-sm-3">
                <label class="form-label fw-bold"><?= __('Event End Time', 'psyeventsmanager') ?></label>
                <input type="time" class="form-control strict_space" name="psyemConfigs[psyem_event_endtime]" value="<?= @$psyem_event_endtime ?>" placeholder="<?= __('Enter event end time', 'psyeventsmanager') ?>">
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-sm-3">
                <label class="form-label fw-bold"><?= __('Registration Type', 'psyeventsmanager') ?></label>
                <select class="form-select" name="psyemConfigs[psyem_event_registration_type]" id="psyem_event_registration_type">
                    <option value="Closed" <?= (@$psyem_event_registration_type == 'Closed') ? 'selected="selected"' : '' ?>>
                        <?= __('Registration Closed', 'psyeventsmanager') ?>
                    </option>
                    <option value="Paid" <?= (@$psyem_event_registration_type == 'Paid') ? 'selected="selected"' : '' ?>>
                        <?= __('Paid Registration', 'psyeventsmanager') ?>
                    </option>
                    <option value="Free" <?= (@$psyem_event_registration_type == 'Free') ? 'selected="selected"' : '' ?>>
                        <?= __('Free Registration', 'psyeventsmanager') ?>
                    </option>
                    <option value="Invitation" <?= (@$psyem_event_registration_type == 'Invitation') ? 'selected="selected"' : '' ?>>
                        <?= __('Invitation Only', 'psyeventsmanager') ?>
                    </option>
                </select>
            </div>

            <div class="col-sm-3">
                <label class="form-label fw-bold"><?= __('Registration Closing (In Days)', 'psyeventsmanager') ?></label>
                <input type="text" class="form-control strict_integer strict_space" name="psyemConfigs[psyem_event_registration_closing]" value="<?= @$psyem_event_registration_closing ?>" placeholder="<?= __('Enter  number of closing days', 'psyeventsmanager') ?>">
                <small> <?= __('x days before event start day', 'psyeventsmanager') ?> </small>
            </div>

            <div class="col-sm-6">
                <label class="form-label fw-bold"><?= __('Event Website', 'psyeventsmanager') ?></label>
                <input type="text" class="form-control strict_space" name="psyemConfigs[psyem_event_website]" value="<?= @$psyem_event_website ?>" placeholder="<?= __('Enter event website url', 'psyeventsmanager') ?>">
                <small><?= __('If applicable, Enter external website url', 'psyeventsmanager') ?> </small>
            </div>
        </div>

        <?php if($event_total_attendees > 0){?>
        <div class="row mb-4">
            <div class="col-sm-12 text-end">
                <a class="btn btn-primary text-white" href="<?= $export_attendees_url ?>" target="_blank">
                    <span class="dashicons dashicons-download"></span>
                    <?= __('Export Attendees', 'psyeventsmanager') ?>
                </a>
            </div>
        </div>
        <?php } ?>

        <div class="row mb-4" id="psyem_event_tickets_cont">
            <p> <?= __('Select applicable tickets for this event', 'psyeventsmanager') ?></p>
            <div class="col-sm-12">
                <label class="form-label fw-bold  d-block"><?= __('Event Tickets', 'psyeventsmanager') ?></label>
                <select class="form-select select2Single" name="psyem_event_tickets[]" multiple="multiple" style="width:98%;">
                    <option value="">
                        <?= __('Select Ticket', 'psyeventsmanager') ?>
                    </option>
                    <?php
                    if (isset($all_tickets) && !empty($all_tickets) && is_array($all_tickets)) {
                        foreach ($all_tickets as $ticketID => $ticketVal) {
                            $ticketMeta = @$ticketVal['meta_data'];
                    ?>
                            <option
                                value="<?= @$ticketID ?>"
                                <?= (in_array(@$ticketID, $event_tickets)) ? 'selected="selected"' : '' ?>>
                                <?= @$ticketVal['title'] ?> - (<?= @$ticketMeta['psyem_ticket_type'] ?>)
                            </option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-sm-9 mb-3">
                <label class="form-label fw-bold"><?= __('Event Address', 'psyeventsmanager') ?></label>
                <input type="text" class="form-control strict_space" name="psyemConfigs[psyem_event_address]" value="<?= @$psyem_event_address ?>" placeholder="<?= __('Enter event full addess', 'psyeventsmanager') ?>">
            </div>
            <div class="col-sm-3 mb-3 mt-4">
                <div class="d-grid gap-2 mx-auto">
                    <a class="btn btn-info text-white" href="<?= get_preview_post_link($current_event_id) ?>" target="_blank">
                        <span class="dashicons dashicons-welcome-view-site"></span> <?= __('Preview', 'psyeventsmanager') ?>
                    </a>
                </div>
            </div>
            <div class="col-sm-12">
                <label class="form-label fw-bold"><?= __('Event Disclaimer', 'psyeventsmanager') ?></label>
                <textarea class="form-control strict_space" name="psyemConfigs[psyem_event_disclaimer]" placeholder="<?= __('Enter event disclaimer details', 'psyeventsmanager') ?>"><?= @$psyem_event_disclaimer ?></textarea>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-sm-3 mb-3">
                <label class="form-label fw-bold"><?= __('Facebook Url', 'psyeventsmanager') ?></label>
                <input type="text" class="form-control strict_space" name="psyemConfigs[psyem_event_facebook_url]" value="<?= @$psyem_event_facebook_url ?>" placeholder="<?= __('Enter facebook url', 'psyeventsmanager') ?>">
            </div>

            <div class="col-sm-3 mb-3">
                <label class="form-label fw-bold"><?= __('Instagram Url', 'psyeventsmanager') ?></label>
                <input type="text" class="form-control strict_space" name="psyemConfigs[psyem_event_instagram_url]" value="<?= @$psyem_event_instagram_url ?>" placeholder="<?= __('Enter instagram url', 'psyeventsmanager') ?>">
            </div>


            <div class="col-sm-3 mb-3">
                <label class="form-label fw-bold"><?= __('LinkedIn Url', 'psyeventsmanager') ?></label>
                <input type="text" class="form-control strict_space" name="psyemConfigs[psyem_event_linkedin_url]" value="<?= @$psyem_event_linkedin_url ?>" placeholder="<?= __('Enter linkedin url', 'psyeventsmanager') ?>">
            </div>

            <div class="col-sm-3 mb-3">
                <label class="form-label fw-bold"><?= __('Twitter Url', 'psyeventsmanager') ?></label>
                <input type="text" class="form-control strict_space" name="psyemConfigs[psyem_event_twitter_url]" value="<?= @$psyem_event_twitter_url ?>" placeholder="<?= __('Enter twitter url', 'psyeventsmanager') ?>">
            </div>
        </div>
    <?php } ?>
    <!-- configs - END -->

    <!-- Partners - BGN -->
    <?php
    if ($event_metabox_type == 'Partners') {
        $partnerCatPosts = (isset($partnerCategoriesPosts) && array($partnerCategoriesPosts) && !empty($partnerCategoriesPosts)) ? $partnerCategoriesPosts : [];
    ?>
        <div class="row">
            <p> <?= __('Select event partners', 'psyeventsmanager') ?></p>
            <?php
            if (!empty($partnerCatPosts) && is_array($partnerCatPosts)) {
                foreach ($partnerCatPosts as $partnerCatID => $partnerCatVal) {
                    $cat_partners = @$partnerCatVal['term_posts'];
            ?>
                    <div class="col-sm-12 mb-3">
                        <label class="form-label fw-bold  d-block">
                            <?= __('Partner Type', 'psyeventsmanager') ?>: <?= @$partnerCatVal['term_name'] ?>
                        </label>
                        <select class="form-select select2Single" name="psyem_event_partners[<?= @$partnerCatVal['term_id'] ?>][]" multiple="multiple" style="width:98%;">
                            <option value="">
                                <?= __('Select partners', 'psyeventsmanager') ?>
                            </option>
                            <?php
                            if (!empty($cat_partners) && is_array($cat_partners)) {
                                foreach ($cat_partners as $partnerVal) {
                                    $pselected = '';

                                    if (isset($event_partners) && !empty($event_partners) && is_array($event_partners)) {
                                        foreach ($event_partners as $eventPartnerCatId => $eventPartner) {
                                            if (
                                                ($eventPartnerCatId == $partnerCatID) &&
                                                (!empty($eventPartner) && is_array($eventPartner)) &&
                                                (in_array(@$partnerVal->ID, $eventPartner))
                                            ) {
                                                $pselected = 'selected="selected"';
                                            }
                                        }
                                    }
                            ?>
                                    <option
                                        value="<?= @$partnerVal->ID ?>"
                                        <?= $pselected ?>>
                                        <?= @$partnerVal->post_title ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    <?php } ?>
    <!-- Partners - BGN -->


    <!-- Speakers - BGN -->
    <?php
    if ($event_metabox_type == 'Speakers') {
        $speakerCatPosts = (isset($speakerCategoriesPosts) && array($speakerCategoriesPosts) && !empty($speakerCategoriesPosts)) ? $speakerCategoriesPosts : [];

    ?>
        <div class="row">
            <p> <?= __('Select event speakers', 'psyeventsmanager') ?></p>
            <?php
            if (!empty($speakerCatPosts) && is_array($speakerCatPosts)) {
                foreach ($speakerCatPosts as $speakerCatID => $speakerCatVal) {
                    $cat_speakers = @$speakerCatVal['term_posts'];
            ?>
                    <div class="col-sm-12 mb-3">
                        <label class="form-label fw-bold  d-block">
                            <?= __('Speaker Type', 'psyeventsmanager') ?>: <?= @$speakerCatVal['term_name'] ?>
                        </label>
                        <select class="form-select select2Single" name="psyem_event_speakers[<?= @$speakerCatVal['term_id'] ?>][]" multiple="multiple" style="width:98%;">
                            <option value="">
                                <?= __('Select speakers', 'psyeventsmanager') ?>
                            </option>
                            <?php
                            if (!empty($cat_speakers) && is_array($cat_speakers)) {
                                foreach ($cat_speakers as $speakerVal) {
                                    $pselected = '';

                                    if (isset($event_speakers) && !empty($event_speakers) && is_array($event_speakers)) {
                                        foreach ($event_speakers as $eventspeakerCatId => $eventspeaker) {
                                            if (
                                                ($eventspeakerCatId == $speakerCatID) &&
                                                (!empty($eventspeaker) && is_array($eventspeaker)) &&
                                                (in_array(@$speakerVal->ID, $eventspeaker))
                                            ) {
                                                $pselected = 'selected="selected"';
                                            }
                                        }
                                    }
                            ?>
                                    <option
                                        value="<?= @$speakerVal->ID ?>"
                                        <?= $pselected ?>>
                                        <?= @$speakerVal->post_title ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    <?php } ?>
    <!-- Speakers - END -->

    <!-- EVENT MEDIA - BGN -->
    <?php
    if ($event_metabox_type == 'Media') {
        $psyem_event_media_urls = (isset($media_data)) ?  $media_data : [];
        $_psyemEventMediaUrls    = (!empty($psyem_event_media_urls)) ?  esc_attr(json_encode($psyem_event_media_urls)) : '';
    ?>
        <div class="row mb-3">
            <div class="col-md-12 text-center">
                <button type="button" id="psyemUploadEventMediaBtn" class="btn btn-primary">
                    <?= __('Upload Event Media', 'psyeventsmanager') ?>
                </button>
                <input type="hidden" id="psyem_event_media_urls" name="psyem_event_media_urls" value="<?= @$_psyemEventMediaUrls ?>">
            </div>
        </div>

        <hr />

        <div class="row" id="psyemEventMediaFilesCont">
            <?php

            if (!empty($psyem_event_media_urls) && is_array($psyem_event_media_urls)) {
                foreach ($psyem_event_media_urls as $eventMediaFile) {
            ?>
                    <div class="col-md-2 psyemEventMediaRow">
                        <div class="card p-0">
                            <div class="card-header border-0 bg-white p-1 text-end">
                                <button type="button" class="btn btn-danger btn-sm psyemRemoveEventMediaBtn" data-furl="<?= $eventMediaFile ?>">
                                    <?= __('Remove', 'psyeventsmanager') ?>
                                </button>
                            </div>
                            <div class="card-body p-1">
                                <img class="card-img" alt="" style="height: 12vh;" src="<?= $eventMediaFile ?>">
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    <?php } ?>
    <!-- EVENT MEDIA - END -->
<?php } ?>