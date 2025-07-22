<?php if (isset($partner_metabox_type) && !empty($partner_metabox_type)) {  ?>
    <?php if ($partner_metabox_type == 'Information') {
        if (isset($partner_info_data) && !empty($partner_info_data)) {
            extract($partner_info_data);
        }
    ?>
        <div class="row mb-4">
            <div class="col-sm-5">
                <label class="form-label fw-bold"> <?= __('Sponsorship Level', 'psyeventsmanager') ?></label>
                <input type="text" class="form-control strict_space" name="psyemPartnerInfos[psyem_partner_sponsorship_level]" value="<?= @$psyem_partner_sponsorship_level ?>" placeholder="<?= __('Enter Partner Sponsorship Level', 'psyeventsmanager') ?>">
            </div>
        </div>
    <?php } ?>
<?php } ?>