<?php if (isset($amount_metabox_type) && !empty($amount_metabox_type)) {  ?>
    <?php if ($amount_metabox_type == 'Information') {
        if (isset($amount_info_data) && !empty($amount_info_data)) {
            extract($amount_info_data);
        }
    ?>
        <div class="row mb-4">
            <div class="col-sm-3">
                <label class="form-label fw-bold"><?= __('Amount Type', 'psyeventsmanager') ?> </label>
                <select class="form-select" name="psyemAmountInfo[psyem_amount_type]" id="psyem_amount_type">
                    <option value="">
                        <?= __('-- Select --', 'psyeventsmanager') ?>
                    </option>
                    <option value="Monthly" <?= (@$psyem_amount_type == 'Monthly') ? 'selected="selected"' : '' ?>>
                        <?= __('Monthly Charge', 'psyeventsmanager') ?>
                    </option>
                    <option value="Onetime" <?= (@$psyem_amount_type == 'Onetime') ? 'selected="selected"' : '' ?>>
                        <?= __('One Time Charge', 'psyeventsmanager') ?>
                    </option>
                </select>
            </div>

            <div class="col-sm-3" id="psyem_amount_price">
                <label class="form-label fw-bold"> <?= __('Amount', 'psyeventsmanager') ?> </label>
                <input type="text" class="form-control strict_space strict_numeric strict_decimal" name="psyemAmountInfo[psyem_amount_price]" value="<?= @$psyem_amount_price ?>" placeholder="<?= __('Enter amount > 5$', 'psyeventsmanager') ?>">
            </div>
        </div>
    <?php } ?>
<?php } ?>