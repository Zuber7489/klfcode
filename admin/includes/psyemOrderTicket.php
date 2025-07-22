<?php ob_start(); ?>
<?php
if (isset($pdf_content_type) && $pdf_content_type == 'Logo') {
    if (isset($logo_image_src) && !empty($logo_image_src)) { ?>
        <table style="width: 100%; line-height: inherit; text-align: left; border-spacing: 0; margin-bottom: 10px;">
            <thead style="background: #fff;">
                <tr>
                    <th colspan="2" style="text-align: center;">
                        <img src="<?= @$logo_image_src ?>" alt="karenleungfoundation" style="width:300px;">
                    </th>
                </tr>
            </thead>
        </table>
<?php
    }
} ?>
<?php
if (isset($pdf_content_type) && $pdf_content_type == 'Event') {
    if (isset($orderEventInfo) && !empty($orderEventInfo) && is_array($orderEventInfo)) { ?>
        <table
            style="width: 100%; line-height: inherit; text-align: left; border-spacing: 0; margin-bottom: 10px; border: 1px solid #e7e7e7;">
            <thead style="background: #fff;">
                <tr>
                    <th colspan="2" style="text-align: center; border-top: 10px solid #b83f8d; padding: 0px;">
                        &nbsp;
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:10px; width:50%;">
                        <h1 style="text-align: left; font-size: 20px; margin-bottom: 10px; font-weight:400;">Ticket</h1>
                        <p style="margin-bottom: 5px; font-weight: 400; margin-top:5px; font-size:14px;">
                            <?= __('Confirmation No', 'psyeventsmanager') ?>: <?= @$orderEventInfo['order_id'] ?>
                        </p>
                        <p style="margin-bottom: 0; font-weight: 400; margin-top:5px; font-size:14px;">
                            <?= date('d F Y, h:i a') ?>
                        </p>
                    </td>
                    <td style="padding:10px;width:50%;">
                        <p style="font-size:15px;">
                            *<?= __('Print this ticket or save on your device & present it at the event', 'psyeventsmanager') ?>
                        </p>
                    </td>
                </tr>
                <tr style="border-top: 1px solid #e7e7e7;">
                    <td colspan="2" style="padding:10px;width:100%;">
                        <h1 style="text-align: left; font-size: 16px; margin-bottom:0;">
                            <?= __('Event', 'psyeventsmanager') ?>
                        </h1>
                        <p style="font-size:14px; margin-top:2px;">
                            <?= @$orderEventInfo['title'] ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="width:180px; padding:10px; font-size: 14px;">
                        <h3 style="margin-bottom:1px;"><?= __('Start Date', 'psyeventsmanager') ?></h3>
                        <p style="margin-top:1px;">
                            <?= @$orderEventInfo['meta_data']['psyem_event_startdate'] ?>
                        </p>
                    </td>
                    <td style="width:180px; padding:10px; font-size: 14px;">
                        <h3 style="margin-bottom:1px;"><?= __('Start Time', 'psyeventsmanager') ?></h3>
                        <p style="margin-top:1px;">
                            <?= date('h:i a', strtotime(@$orderEventInfo['meta_data']['psyem_event_starttime'])) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="width:180px; padding:10px; font-size: 14px;">
                        <h3 style="margin-bottom:1px;"><?= __('End Date', 'psyeventsmanager') ?></h3>
                        <p style="margin-top:1px;">
                            <?= @$orderEventInfo['meta_data']['psyem_event_enddate'] ?>
                        </p>
                    </td>
                    <td style="width:180px; padding:10px; font-size: 14px;">
                        <h3 style="margin-bottom:1px;"><?= __('End Time', 'psyeventsmanager') ?></h3>
                        <p style="margin-top:1px;">
                            <?= date('h:i a', strtotime(@$orderEventInfo['meta_data']['psyem_event_endtime'])) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:10px;width:100%;font-size: 14px;">
                        <h3 style="text-align: left; font-size: 18px; ">
                            <?= __('Location', 'psyeventsmanager') ?>
                        </h3>
                        <p style="font-size:14px; margin-top:10px;">
                            <?= @$orderEventInfo['meta_data']['psyem_event_address'] ?>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
<?php
    }
} ?>
<?php
if (isset($pdf_content_type) && $pdf_content_type == 'Ticket') {
    if (isset($participantInfo) && !empty($participantInfo)) { ?>
        <table
            style="width: 100%; line-height: inherit; text-align: left; border-spacing: 0; margin-bottom: 5px; border: 1px solid #e7e7e7;">
            <thead style=" background: #fff;">
                <tr>
                    <td style="padding:10px; width:50%;">
                        <h1 style="text-align: left; font-size: 22px; margin-bottom: 10px; font-weight:400;">
                            <?= __('Attendee Detail', 'psyeventsmanager') ?>
                        </h1>
                        <h3 style="width:180px; font-size: 14px; float: left;">
                            <span style="margin-bottom:5px; display:block;"><?= __('First Name', 'psyeventsmanager') ?></span>
                            <strong style="margin-top:5px; display:block; font-weight:300;">
                                <?= @$participantInfo['first_name'] ?>
                            </strong>
                        </h3>
                        <h3 style="width:180px; font-size: 14px; float: left;">
                            <span style="margin-bottom:5px; display:block;"><?= __('Last Name', 'psyeventsmanager') ?></span>
                            <strong style="margin-top:5px; display:block; font-weight:300;">
                                <?= @$participantInfo['last_name'] ?>
                            </strong>
                        </h3>

                        <h3 style="text-align: left; font-size: 14px; padding-bottom: 0; width:100%; margin-bottom: 10px; font-weight:600;">
                            <span style="margin-bottom:5px; display:block; font-size: 16px;font-weight:600;">
                                <?= __('Company', 'psyeventsmanager') ?>
                            </span>
                            <?= @$participantInfo['meta_data']['psyem_participant_company'] ?>
                        </h3>
                    </td>
                    <td style="padding:10px; width:50%;">
                        <p style="font-size:12px; text-align: right;">
                            <img src="<?= @$participantInfo['qr_image_src'] ?>" alt="" style="width:140px;" />
                        </p>
                    </td>
                </tr>
            </thead>
        </table>
<?php }
} ?>
<?php
if (isset($pdf_content_type) && $pdf_content_type == 'Footer') { ?>
    <table style="width: 100%; line-height: inherit; text-align: left; margin-bottom: 5px;">
        <thead style="background: #fff;">
            <tr>
                <td style="padding-left:0; width:100%;">
                    <h1 style="text-align: left; font-size: 10px; margin-bottom: 0; font-weight:400; color: #7e7e7e;">
                        <a href="<?= $TandCPageLink ?>" target="_blank">
                            <?= __('TERMS AND CONDITIONS OF SALE AND PURCHASE', 'psyeventsmanager') ?>
                        </a>
                    </h1>
                    <p style="text-align: left; font-size: 10px; margin-bottom: 10px; font-weight:400; color: #7e7e7e; margin-top:0;">
                        <?= __('All registrations are final, non-refundable and non-changeable', 'psyeventsmanager') ?>
                    </p>
                </td>
            </tr>
        </thead>
    </table>
<?php
} ?>
<?php return ob_get_clean(); ?>