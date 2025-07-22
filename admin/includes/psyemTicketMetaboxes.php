<?php if (isset($ticket_metabox_type) && !empty($ticket_metabox_type)) {  ?>
    <!-- configs - BGN -->
    <?php if ($ticket_metabox_type == 'Config') {
        if (isset($configs_data) && !empty($configs_data)) {
            extract($configs_data);
        }
    ?>
        <div class="row mb-4">
            <div class="col-sm-3">
                <label class="form-label fw-bold">
                    <?= __('Ticket Type', 'psyeventsmanager') ?>
                </label>
                <select class="form-select" name="psyemTicketConfigs[psyem_ticket_type]" id="psyem_ticket_type">
                    <option value="">
                        <?= __('-- Select --', 'psyeventsmanager') ?>
                    </option>
                    <option value="Individual" <?= (@$psyem_ticket_type == 'Individual') ? 'selected="selected"' : '' ?>>
                        <?= __('Individual', 'psyeventsmanager') ?>
                    </option>
                    <option value="Group" <?= (@$psyem_ticket_type == 'Group') ? 'selected="selected"' : '' ?>>
                        <?= __('Group', 'psyeventsmanager') ?>
                    </option>
                </select>
            </div>

            <div class="col-sm-3" id="psyem_ticket_price_cont">
                <label class="form-label fw-bold">
                    <?= __('Ticket Price (USD)', 'psyeventsmanager') ?>
                </label>
                <input type="text" class="form-control strict_space strict_numeric strict_decimal" name="psyemTicketConfigs[psyem_ticket_price]" value="<?= @$psyem_ticket_price > 0 ? $psyem_ticket_price : 0  ?>" placeholder="<?= __('Enter ticket price in usd', 'psyeventsmanager') ?>">
            </div>

            <div class="col-sm-3" id="psyem_ticket_group_participant_cont">
                <label class="form-label fw-bold">
                    <?= __('Participant Count', 'psyeventsmanager') ?>
                </label>
                <input type="number" class="form-control strict_space strict_integer" name="psyemTicketConfigs[psyem_ticket_group_participant]" value="<?= @$psyem_ticket_group_participant  > 0 ? @$psyem_ticket_group_participant : 0 ?>" placeholder="<?= __('Enter group ticket participants count', 'psyeventsmanager') ?>">
                <small><?= __('Group Ticket Participant Count', 'psyeventsmanager') ?></small>
            </div>
        </div>

        <div class="row mb-4" id="psyem_ticket_coupons_cont">
            <p>
                <?= __('Select applicable coupons for this ticket', 'psyeventsmanager') ?>
            </p>
            <div class="col-sm-12">
                <label class="form-label fw-bold  d-block">
                    <?= __('Ticket Coupons', 'psyeventsmanager') ?>
                </label>
                <select class="form-select select2Single" name="psyem_ticket_coupons[]" multiple="multiple" style="width:98%;">
                    <option value="">
                        <?= __('-- Select --', 'psyeventsmanager') ?>
                    </option>
                    <?php
                    if (isset($all_coupons) && !empty($all_coupons) && is_array($all_coupons)) {
                        foreach ($all_coupons as $couponID => $couponVal) {
                            $couponMeta = @$couponVal['meta_data'];
                    ?>
                            <option
                                value="<?= @$couponID ?>"
                                <?= (in_array(@$couponID, $coupons_data)) ? 'selected="selected"' : '' ?>>
                                <?= @$couponVal['title'] ?> - (<?= @$couponMeta['psyem_coupon_type'] ?>)
                            </option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>

    <?php } ?>
    <!-- configs - END -->
<?php } ?>