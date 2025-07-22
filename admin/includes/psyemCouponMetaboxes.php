<?php if (isset($coupon_metabox_type) && !empty($coupon_metabox_type)) {  ?>
    <?php if ($coupon_metabox_type == 'Config') {
        if (isset($coupon_configs_data) && !empty($coupon_configs_data)) {
            extract($coupon_configs_data);
        }
    ?>
        <div class="row mb-4">
            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('Coupon Type', 'psyeventsmanager') ?> </label>
                <select class="form-select" name="psyemCouponConfigs[psyem_coupon_type]" id="psyem_coupon_type">
                    <option value="Fixed" <?= (@$psyem_coupon_type == 'Fixed') ? 'selected="selected"' : '' ?>>
                        <?= __('Order Fixed Price', 'psyeventsmanager') ?>
                    </option>
                    <option value="Percentage" <?= (@$psyem_coupon_type == 'Percentage') ? 'selected="selected"' : '' ?>>
                        <?= __('Order Percentage Price', 'psyeventsmanager') ?>
                    </option>
                </select>
            </div>

            <div class="col-sm-3">
                <label class="form-label fw-bold"> <?= __('Coupon Code', 'psyeventsmanager') ?> </label>
                <input type="text" class="form-control strict_space" name="psyemCouponConfigs[psyem_coupon_unique_code]" value="<?= @$psyem_coupon_unique_code ?>" placeholder="<?= __('Enter coupon code', 'psyeventsmanager') ?>">
            </div>

            <div class="col-sm-3" id="psyem_coupon_discount_amount">
                <label class="form-label fw-bold"> <?= __('Coupon Discount Amount', 'psyeventsmanager') ?></label>
                <input type="text" class="form-control strict_space strict_numeric strict_decimal" name="psyemCouponConfigs[psyem_coupon_discount_amount]" value="<?= @$psyem_coupon_discount_amount ?>" placeholder="<?= __('Enter discount amount', 'psyeventsmanager') ?>">
            </div>

            <div class="col-sm-3" id="psyem_coupon_discount_percentage" style="display: none;">
                <label class="form-label fw-bold"><?= __('Coupon Discount Percentage', 'psyeventsmanager') ?> </label>
                <input type="text" class="form-control strict_space strict_integer" name="psyemCouponConfigs[psyem_coupon_discount_percentage]" value="<?= @$psyem_coupon_discount_percentage ?>" placeholder="<?= __('Enter discount percentage', 'psyeventsmanager') ?>">
            </div>

            <div class="col-sm-3">
                <label class="form-label fw-bold"><?= __('Coupon Expiry Date', 'psyeventsmanager') ?></label>
                <input type="date" class="form-control strict_space" name="psyemCouponConfigs[psyem_coupon_expiry_date]" value="<?= @$psyem_coupon_expiry_date ?>" placeholder="<?= __('Enter Coupon Expiry Date', 'psyeventsmanager') ?>">
            </div>
        </div>
    <?php } ?>
<?php } ?>