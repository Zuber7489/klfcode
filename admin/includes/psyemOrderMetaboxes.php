<style>
    .form-check-input:checked {
        background-color: #fffafa;
    }
</style>
<?php if (isset($order_metabox_type) && !empty($order_metabox_type)) {  ?>
    <!-- ORDER EVENT INFO - BGN -->
    <?php if ($order_metabox_type == 'Event') {
        if (!empty($order_info_data) && is_array($order_info_data) && count($order_info_data) > 0) {
            $order_event_meta = @$order_event_info['meta_data'];
    ?>

            <div class="row psyPostMetaboxEInfo mb-4">
                <div class="col-sm-3">
                    <label class="form-label fw-bold"><?= __('Event Start Date', 'psyeventsmanager') ?></label>
                    <p class="mb-1">
                        <?= psyem_GetFormattedDatetime('d F Y', @$order_event_meta['psyem_event_startdate']) ?>
                    </p>
                </div>
                <div class="col-sm-3">
                    <label class="form-label fw-bold"><?= __('Event Start Time', 'psyeventsmanager') ?></label>
                    <p class="mb-1"><?= psyem_GetFormattedDatetime('h:i A', @$order_event_meta['psyem_event_starttime']) ?></p>
                </div>
                <div class="col-sm-3">
                    <label class="form-label fw-bold"><?= __('Event End Date', 'psyeventsmanager') ?></label>
                    <p class="mb-1"><?= psyem_GetFormattedDatetime('d F Y', @$order_event_meta['psyem_event_enddate']) ?></p>
                </div>
                <div class="col-sm-3">
                    <label class="form-label fw-bold"><?= __('Event End Time', 'psyeventsmanager') ?></label>
                    <p class="mb-1"><?= psyem_GetFormattedDatetime('h:i A', @$order_event_meta['psyem_event_endtime']) ?></p>
                </div>
            </div>

            <div class="row psyPostMetaboxEInfo mb-4">
                <div class="col-sm-3">
                    <label class="form-label fw-bold"><?= __('Registration Total Price', 'psyeventsmanager') ?></label>
                    <p class="mb-1">
                        <?= (@$psyem_order_amount_currency == 'hkd') ? 'hk$' : '$' ?>
                        <?= formatPriceWithComma(@$order_meta_data['psyem_order_total_amount']) ?>
                    </p>
                </div>

                <div class="col-sm-3">
                    <label class="form-label fw-bold"><?= __('Registration Type', 'psyeventsmanager') ?></label>
                    <p class="mb-1"><?= @$order_event_meta['psyem_event_registration_type'] ?></p>
                </div>

                <div class="col-sm-3">
                    <label class="form-label fw-bold"><?= __('Registration Closing (In Days)', 'psyeventsmanager') ?></label>
                    <p class="mb-1"><?= @$order_event_meta['psyem_event_registration_closing'] ?></p>
                </div>

                <div class="col-sm-3">
                    <label class="form-label fw-bold"><?= __('Event Website', 'psyeventsmanager') ?></label>
                    <p class="mb-1"><?= @$order_event_meta['psyem_event_website'] ?></p>
                </div>
            </div>

            <div class="row psyPostMetaboxEInfo">
                <div class="col-sm-12">
                    <label class="form-label fw-bold"><?= __('Event Address', 'psyeventsmanager') ?></label>
                    <p class="mb-1"><?= @$order_event_meta['psyem_event_address'] ?></p>
                </div>
            </div>
    <?php }
    } ?>
    <!-- ORDER EVENT INFO - END -->

    <!-- ORDER INFO - BGN -->
    <?php if ($order_metabox_type == 'Informations') {
        if (!empty($order_info_data) && is_array($order_info_data) && count($order_info_data) > 0) {
    ?>
            <div class="row psyPostMetaboxOInfo mb-4 mt-2">
                <div class="col-sm-12">
                    <table class="table table-hover table-bordered table-responsive mb-0">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Order ID', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Total Slots', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Used Slots', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Order Amount', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Order Coupon', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Total Discount', 'psyeventsmanager') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= @$order_info_data['ID'] ?></td>
                                <td><?= @$order_meta_data['psyem_order_total_slots'] ?></td>
                                <td><?= @$order_meta_data['psyem_order_used_slots'] ?></td>
                                <td>
                                    <?= (@$psyem_order_amount_currency == 'hkd') ? 'hk$' : '$' ?>
                                    <?= formatPriceWithComma(@$order_meta_data['psyem_order_checkout_amount']) ?>
                                </td>
                                <td><?= @$order_meta_data['psyem_order_coupon'] ?></td>
                                <td>
                                    <?= (@$psyem_order_amount_currency == 'hkd') ? 'hk$' : '$' ?>
                                    <?= formatPriceWithComma(@$order_meta_data['psyem_order_total_discount']) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if (!empty($order_tickets_data) && is_array($order_tickets_data)): ?>
                <div class="row psyPostMetaboxOInfo mb-4 mt-2">
                    <div class="col-sm-12">
                        <h5> <?= __('Tickets Informations', 'psyeventsmanager') ?></h5>
                        <table class="table table-hover table-bordered table-responsive mb-0">
                            <thead>
                                <tr>
                                    <th scope="col"><?= __('Item', 'psyeventsmanager') ?></th>
                                    <th scope="col"><?= __('Quantity', 'psyeventsmanager') ?></th>
                                    <th scope="col"><?= __('Total', 'psyeventsmanager') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($order_tickets_data as $cartTicketId => $cartTicketInfo) :
                                    $ticketMeta = @$cartTicketInfo['ticket_meta'];
                                ?>
                                    <tr>
                                        <td>
                                            <h6 class="card-title mb-0 fw-normal">
                                                <?= @$cartTicketInfo['title'] ?>
                                            </h6>
                                            <strong><?= @$cartTicketInfo['type'] ?></strong>
                                        </td>
                                        <td><?= @$cartTicketInfo['choosen_count'] ?></td>
                                        <td>
                                            <p>
                                                <?= (@$psyem_order_amount_currency == 'hkd') ? 'hk$' : '$' ?>
                                                <?= formatPriceWithComma(@$cartTicketInfo['cart_price']) ?>
                                            </p>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>


            <div class="row psyPostMetaboxOInfo mb-4 mt-2">
                <div class="col-sm-12">
                    <h5> <?= __('Payment Informations', 'psyeventsmanager') ?></h5>
                    <table class="table table-hover table-bordered table-responsive mb-0">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Currency', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Intent ID', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Charge ID', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Payment Status', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Payment DateTime', 'psyeventsmanager') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= (@$psyem_order_amount_currency) ?></td>
                                <td><?= @$order_meta_data['psyem_order_intent_id'] ?></td>
                                <td><?= @$order_meta_data['psyem_order_charge_id'] ?></td>
                                <td>
                                    <span class="btn-sm btn btn-outline-primary">
                                        <?= @$order_meta_data['psyem_order_payment_status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    if (!empty(@$order_meta_data['psyem_order_payment_datetime'])) {
                                        echo date('Y-m-d h:i a', @$order_meta_data['psyem_order_payment_datetime']);
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if (isset($psyem_order_payment_payload) && !empty($psyem_order_payment_payload) && is_array($psyem_order_payment_payload)) { ?>
                <div class="row psyPostMetaboxOInfo mt-2">
                    <div class="col-sm-12">
                        <div class="accordion" id="PPAccordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingPaymentPayload">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ppCollapseOne" aria-expanded="true" aria-controls="ppCollapseOne">
                                        <?= __('Payment Payload', 'psyeventsmanager') ?>
                                    </button>
                                </h2>
                                <div id="ppCollapseOne" class="accordion-collapse collapse" aria-labelledby="headingPaymentPayload" data-bs-parent="#PPAccordionExample">
                                    <div class="accordion-body">
                                        <code>
                                            <pre> <?php print_r(@$psyem_order_payment_payload) ?> </pre>
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
    <?php }
    } ?>
    <!-- ORDER INFO - END -->

    <!-- ORDER INFO - BGN -->
    <?php
    if ($order_metabox_type == 'Participants') {
        if (!empty($order_participants_data) && is_array($order_participants_data) && count($order_participants_data) > 0) {
            $get_data = http_build_query(array(
                '_nonce'         => wp_create_nonce('_nonce'),
                'action'         => 'psyem_order_print_tickets',
                'order_id'       => @$order_info_data['ID']
            ));
            $printurl = admin_url('admin-post.php') . '?' . $get_data;
    ?>
            <div class="row psyPostMetaboxPInfo mb-4 mt-2">
                <div class="col-sm-2 text-start">
                    <a class="btn btn-sm btn-primary" href="<?= $printurl ?>">
                        <span class="dashicons dashicons-pdf"></span>
                        <?= __('Print All tickets', 'psyeventsmanager') ?>
                    </a>
                </div>
                <div class="col-sm-2 text-start">
                    <button class="btn btn-sm btn-primary sendSelectedOrderTicket" type="button" data-order="<?= (isset($order_info_data['ID'])) ? $order_info_data['ID'] : 0 ?>">
                        <span class="dashicons dashicons-email"></span>
                        <span class="spinner-border buttonLoader spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        <?= __('Send Tickets', 'psyeventsmanager') ?>
                    </button>
                </div>
                <div class="col-sm-3 text-center">
                    <a class="btn btn-sm btn-primary" download="participants-sample.csv" href="<?= @$psyem_sample_csv_url ?>">
                        <span class="dashicons dashicons-download"></span>
                        <?= __('Download Sample CSV', 'psyeventsmanager') ?>
                    </a>
                </div>
                <div class="col-sm-5 text-end">
                    <input type="hidden" name="participant_order_id" value="<?= (isset($order_info_data['ID'])) ? $order_info_data['ID'] : 0 ?>" />
                    <div class="input-group">
                        <input type="file" class="form-control ps-1" id="inputGroupFileCsv" aria-describedby="inputGroupFileCsvBtn" aria-label="Upload" name="participant_csv_file">
                        <button class="btn btn-primary btn-sm" type="button" id="inputGroupFileCsvBtn">
                            <span class="spinner-border buttonLoader spinner-border-sm" role="status"
                                aria-hidden="true" style="display: none;"></span>
                            <span class="dashicons dashicons-upload"></span>
                            <?= __('Import CSV', 'psyeventsmanager') ?>
                        </button>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="row psyPostMetaboxPInfo">
            <div class="col-sm-12">
                <table class="table table-hover table-bordered table-responsive mb-0">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input type="checkbox" class="form-check-input" id="selectAllParticipants">
                            </th>
                            <th scope="col"><?= __('ID', 'psyeventsmanager') ?></th>
                            <th scope="col"><?= __('Name', 'psyeventsmanager') ?></th>
                            <th scope="col"><?= __('Email', 'psyeventsmanager') ?></th>
                            <th scope="col"><?= __('Company', 'psyeventsmanager') ?></th>
                            <th scope="col"><?= __('Type', 'psyeventsmanager') ?></th>
                            <th scope="col"><?= __('Print', 'psyeventsmanager') ?></th>
                            <th scope="col"><?= __('Edit', 'psyeventsmanager') ?></th>
                            <th scope="col"><?= __('Actions', 'psyeventsmanager') ?></th>
                        </tr>
                    </thead>
                    <tbody class="participantsRowsCont">
                        <?php
                        if (!empty($order_participants_data) && is_array($order_participants_data) && count($order_participants_data) > 0) {
                            foreach ($order_participants_data as $participantType => $participantId) {
                                if (!empty($participantType) && $participantId > 0) {
                                    $participantInfo = psyem_GetSinglePostWithMetaPrefix('psyem-participants', $participantId, 'psyem_participant_');
                                    if (!empty($participantInfo)) {
                        ?>
                                        <tr>
                                            <th scope="row">
                                                <input type="checkbox" class="form-check-input selectParticipant" id="selectParticipant<?= @$participantId ?>" value="<?= @$participantId ?>">
                                            </th>
                                            <th scope="row"><?= @$participantId ?></th>
                                            <td><?= @$participantInfo['title'] ?></td>
                                            <td><?= @$participantInfo['meta_data']['psyem_participant_email'] ?></td>
                                            <td><?= @$participantInfo['meta_data']['psyem_participant_company'] ?></td>
                                            <td><?= (@$participantType == 'Main') ? 'Main' : 'Member' ?></td>
                                            <td>
                                                <?php
                                                $get_data = http_build_query(array(
                                                    '_nonce'         => wp_create_nonce('_nonce'),
                                                    'action'         => 'psyem_order_print_tickets',
                                                    'order_id'       => @$order_info_data['ID'],
                                                    'participant'    => @$participantId,
                                                ));
                                                $url = admin_url('admin-post.php') . '?' . $get_data;
                                                ?>
                                                <a class="btn btn-sm btn-primary" href="<?= @$url ?>" target="_blank">
                                                    <?= __('Print', 'psyeventsmanager') ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-primary" href="<?= @$participantInfo['edit_link'] ?>">
                                                    <?= __('Edit', 'psyeventsmanager') ?>
                                                </a>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary sendOrderTicket" data-order="<?= (isset($order_info_data['ID'])) ? $order_info_data['ID'] : 0 ?>" type="button" data-participant="<?= @$participantId ?>">
                                                    <span class="spinner-border buttonLoader spinner-border-sm" role="status"
                                                        aria-hidden="true" style="display: none;"></span>
                                                    <?= __('Send Ticket', 'psyeventsmanager') ?>
                                                </button>
                                            </td>
                                        </tr>
                            <?php
                                    }
                                }
                            }
                        } else {  ?>
                            <tr>
                                <th scope="row" class="text-center" colspan="7">
                                    <div class="alert alert-danger mb-0">
                                        <?= __('No records found', 'psyeventsmanager') ?>
                                    </div>
                                </th>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
<?php } ?>