<?php if (isset($donation_metabox_type) && !empty($donation_metabox_type)) {  ?>
    <?php if ($donation_metabox_type == 'Informations') {
        if (!empty($donation_info_data) && is_array($donation_info_data) && count($donation_info_data) > 0) {
    ?>

            <div class="row psyPostMetaboxOInfo mb-4 mt-2">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5> <?= __('Billing Information', 'psyeventsmanager') ?> </h5>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold"><?= __('Country', 'psyeventsmanager') ?></label>
                            <p class="mb-1"><?= @$donation_meta_data['psyem_donation_billing_country'] ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold"><?= __('City', 'psyeventsmanager') ?></label>
                            <p class="mb-1"><?= @$donation_meta_data['psyem_donation_billing_city'] ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold"><?= __('District', 'psyeventsmanager') ?></label>
                            <p class="mb-1"><?= @$donation_meta_data['psyem_donation_billing_district'] ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold"><?= __('Address', 'psyeventsmanager') ?></label>
                            <p class="mb-1"><?= @$donation_meta_data['psyem_donation_billing_address'] ?></p>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label fw-bold"><?= __('Address 2', 'psyeventsmanager') ?></label>
                            <p class="mb-1"><?= @$donation_meta_data['psyem_donation_billing_address2'] ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5> <?= __('Donor Information', 'psyeventsmanager') ?> </h5>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold"><?= __('First Name', 'psyeventsmanager') ?></label>
                            <p class="mb-1"><?= @$donation_meta_data['psyem_donation_first_name'] ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold"><?= __('Last Name', 'psyeventsmanager') ?></label>
                            <p class="mb-1"><?= @$donation_meta_data['psyem_donation_last_name'] ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold"><?= __('Email', 'psyeventsmanager') ?></label>
                            <p class="mb-1"><?= @$donation_meta_data['psyem_donation_email'] ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold"><?= __('Phone', 'psyeventsmanager') ?></label>
                            <p class="mb-1"><?= @$donation_meta_data['psyem_donation_phone'] ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold"><?= __('Company', 'psyeventsmanager') ?></label>
                            <p class="mb-1"><?= @$donation_meta_data['psyem_donation_company'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row psyPostMetaboxOInfo mb-4 mt-2">
                <div class="col-sm-12">
                    <h5> Payment Info </h5>
                    <table class="table table-hover table-bordered table-responsive mb-0">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Type', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Amount', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Intent ID', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Charge ID', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Payment Status', 'psyeventsmanager') ?></th>
                                <th scope="col"><?= __('Payment DateTime', 'psyeventsmanager') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= @$donation_meta_data['psyem_donation_amount_for'] ?></td>
                                <td>
                                    <?= (@$donation_meta_data['psyem_donation_amount_currency'] == 'hkd') ? 'hk$' : '$' ?>
                                    <?= formatPriceWithComma(@$donation_meta_data['psyem_donation_price']) ?>
                                </td>
                                <td><?= @$donation_meta_data['psyem_donation_intent_id'] ?></td>
                                <td><?= @$donation_meta_data['psyem_donation_charge_id'] ?></td>
                                <td>
                                    <span class="btn-sm btn btn-outline-primary">
                                        <?= @$donation_meta_data['psyem_donation_payment_status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    if (!empty(@$donation_meta_data['psyem_donation_payment_datetime'])) {
                                        echo date('Y-m-d h:i a', @$donation_meta_data['psyem_donation_payment_datetime']);
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if (isset($psyem_donation_payment_payload) && !empty($psyem_donation_payment_payload) && is_array($psyem_donation_payment_payload)) { ?>
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
                                            <pre> <?php print_r(@$psyem_donation_payment_payload) ?> </pre>
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
<?php } ?>